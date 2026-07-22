<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveLeadRequest;
use App\Models\EstimatorLead;
use App\Models\Package;
use App\Models\Room;
use App\Services\EstimatorService;
use App\Services\SmsService;
use App\Jobs\SendMetaCapiEventJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EstimatorLeadController extends Controller
{
    protected $estimatorService;

    public function __construct(EstimatorService $estimatorService)
    {
        $this->estimatorService = $estimatorService;
    }

    /**
     * Get the dynamic configuration data (active packages, rooms, and add-ons) for the frontend wizard.
     */
    public function config()
    {
        $config = \Illuminate\Support\Facades\Cache::remember('estimator_config_v1', 60, function () {
            $packages = Package::where('status', true)->orderBy('display_order', 'asc')->get();
            
            // Eager load active add-ons for active rooms with package prices
            $rooms = Room::where('status', true)
                ->with(['addons' => function ($query) {
                    $query->where('status', true)->with('prices')->orderBy('name', 'asc');
                }])
                ->orderBy('name', 'asc')
                ->get();

            $siteSetting = \App\Models\SiteSetting::first();

            return [
                'packages'  => $packages,
                'rooms'     => $rooms,
                'site_name' => $siteSetting?->site_name ?? 'Our Interior Studio',
            ];
        });

        return response()->json($config);
    }

    /**
     * Calculate cost and save lead entry (Public endpoint).
     */
    public function store(SaveLeadRequest $request)
    {
        $phone = $request->input('phone');
        $otp = $request->input('otp');
        
        $cachedOtp = Cache::get('estimator_otp_' . $phone);
        
        if (!$cachedOtp || $cachedOtp != $otp) {
            return response()->json([
                'message' => 'The verification code entered is incorrect or has expired.',
                'errors' => [
                    'otp' => ['The verification code is invalid or expired.']
                ]
            ], 422);
        }
        
        // Verification succeeded, remove OTP from cache
        Cache::forget('estimator_otp_' . $phone);

        $lead = $this->estimatorService->saveLead($request->validated());

        // Dispatch Server-Side Meta Conversions API (CAPI) Event asynchronously
        $eventId = $request->input('event_id');
        SendMetaCapiEventJob::dispatch(
            'CustomizeProduct',
            $eventId,
            [
                'email' => $lead->email ?: null,
                'phone' => $lead->phone ?: null,
                'name' => $lead->name ?: null,
                'client_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'fbp' => $request->cookie('_fbp'),
                'fbc' => $request->cookie('_fbc'),
            ],
            [
                'content_name' => 'Cost Estimate Generated',
                'value' => (float) $lead->total_estimate,
                'currency' => 'BDT'
            ],
            $request->headers->get('referer')
        );

        // Dispatch Customer Lead Notification SMS
        try {
            $smsService = app(\App\Services\SmsService::class);
            $settings = $smsService->getSettings();
            
            $customerPhone = $lead->phone;
            
            if (!empty($customerPhone)) {
                $template = $settings['sms_template_lead'] ?? 'Premium Touch: A new lead has been submitted by {name}. Estimate: {cost} BDT.';
                $message = str_replace(
                    ['{name}', '{cost}'],
                    [$lead->name, number_format($lead->total_estimate)],
                    $template
                );
                
                $smsService->sendSms($customerPhone, $message);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send customer lead notification SMS: " . $e->getMessage());
        }

        return response()->json([
            'message'        => 'Estimate saved successfully.',
            'lead_id'        => $lead->id,
            'total_estimate' => $lead->total_estimate,
        ], 201);
    }


    /**
     * Generate and send OTP via SMS gateway.
     */
    public function sendOtp(Request $request, SmsService $smsService)
    {
        $request->validate([
            'phone' => 'required|string|max:50',
        ]);

        $phone = $request->input('phone');
        
        // Generate a 4-digit code
        $code = (string) rand(1000, 9999);
        
        // Cache code for 1 minute
        Cache::put('estimator_otp_' . $phone, $code, now()->addMinutes(1));
        
        // Get settings and format message
        $settings = $smsService->getSettings();
        $template = $settings['sms_template_otp'] ?? 'Your Premium Touch OTP is: {code}. Valid for 1 minute.';
        $message = str_replace('{code}', $code, $template);
        
        // Send SMS
        $result = $smsService->sendSms($phone, $message);
        
        // Log for transparency
        Log::info("Estimator OTP generated for phone {$phone}. OTP: {$code}. SMS Result: " . json_encode($result));
        
        $response = [
            'success' => true,
            'message' => 'Verification code sent successfully.'
        ];
        
        // If in mock mode or SMS is disabled, return OTP in payload for testing convenience
        $isMock = $smsService->isMockMode($settings) || $settings['sms_enabled'] !== '1';
        if ($isMock || app()->environment('local', 'testing')) {
            $response['otp_code'] = $code;
            $response['message'] .= ' [Local Dev Mode: OTP is ' . $code . ']';
        }
        
        return response()->json($response);
    }

    /**
     * Display a listing of estimator leads (Admin endpoint).
     */
    public function index(Request $request)
    {
        $query = EstimatorLead::with('package')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");

                // Parse and search by Estimate ID if it's numeric after stripping prefixes like EST, #, -
                $cleanId = preg_replace('/^(?:EST|#|-)+/i', '', $search);
                if (is_numeric($cleanId)) {
                    $q->orWhere('id', (int)$cleanId);
                }
            });
        }

        if ($request->filled('package_id')) {
            $query->where('package_id', $request->input('package_id'));
        }

        if ($request->filled('flat_status')) {
            $query->where('flat_status', $request->input('flat_status'));
        }

        $leads = $query->get();
        return response()->json($leads);
    }

    /**
     * Display the details of a specific estimator lead (Admin endpoint).
     */
    public function show($id)
    {
        $lead = EstimatorLead::with([
            'package',
            'roomItems.room',
            'addonItems.addon.room',
            'addonItems.addon.prices'
        ])->findOrFail($id);

        return response()->json($lead);
    }

    /**
     * Remove a lead entry (Admin endpoint).
     */
    public function destroy($id)
    {
        $lead = EstimatorLead::findOrFail($id);
        $lead->delete();

        return response()->json(['message' => 'Lead entry deleted successfully.']);
    }

    /**
     * Download the estimate report in PDF format.
     */
    public function downloadPdf($id)
    {
        $pdf = $this->estimatorService->generatePdf($id);
        return $pdf->download("estimate_{$id}.pdf");
    }

    /**
     * Fetch statistical reports for Estimator Dashboard (Admin endpoint).
     */
    public function reports()
    {
        // Total leads count
        $totalLeads = EstimatorLead::count();

        // Average total estimate
        $avgEstimate = EstimatorLead::avg('total_estimate') ?: 0;

        // Flat Status ratios
        $statusBreakdown = EstimatorLead::select('flat_status', DB::raw('count(*) as count'))
            ->groupBy('flat_status')
            ->get();

        // Package chosen counts
        $packageBreakdown = EstimatorLead::select('packages.name', DB::raw('count(estimator_leads.id) as count'))
            ->join('packages', 'packages.id', '=', 'estimator_leads.package_id')
            ->groupBy('packages.name')
            ->get();

        // Popular Rooms
        $popularRooms = DB::table('estimator_room_items')
            ->select('rooms.name', DB::raw('sum(estimator_room_items.quantity) as total_qty'))
            ->join('rooms', 'rooms.id', '=', 'estimator_room_items.room_id')
            ->groupBy('rooms.name')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        // Popular Addons
        $popularAddons = DB::table('estimator_addon_items')
            ->select('addons.name', DB::raw('sum(estimator_addon_items.quantity) as total_qty'))
            ->join('addons', 'addons.id', '=', 'estimator_addon_items.addon_id')
            ->groupBy('addons.name')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'total_leads'       => $totalLeads,
            'avg_estimate'      => round($avgEstimate, 2),
            'status_breakdown'  => $statusBreakdown,
            'package_breakdown' => $packageBreakdown,
            'popular_rooms'     => $popularRooms,
            'popular_addons'    => $popularAddons,
        ]);
    }

    /**
     * Create a ConsultationRequest using the details of the specified EstimatorLead.
     */
    public function requestConsultation($id)
    {
        $lead = EstimatorLead::with('package')->findOrFail($id);

        $consultation = \App\Models\ConsultationRequest::create([
            'full_name' => $lead->name,
            'phone' => $lead->phone,
            'email' => $lead->email,
            'project_type' => 'Interior Design (' . $lead->package->name . ' Package)',
            'property_size' => number_format($lead->home_size),
            'budget_range' => '৳' . number_format($lead->total_estimate),
            'location' => $lead->location,
            'notes' => 'Generated automatically from Cost Estimator Ref #EST-' . str_pad($lead->id, 5, '0', STR_PAD_LEFT) . '.',
            'source' => 'estimator', // Sources: cta_modal, contact_page, estimator
            'status' => 'New',
            'form_data' => [
                'flat_condition' => $lead->flat_status
            ],
        ]);

        return response()->json([
            'message' => 'Consultation request submitted successfully. Our team will contact you soon.',
            'data' => $consultation
        ], 201);
    }
}
