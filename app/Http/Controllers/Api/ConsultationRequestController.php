<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConsultationRequest;
use App\Jobs\SendMetaCapiEventJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsultationRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        
        $query = ConsultationRequest::query();
        
        if ($status && $status !== 'All') {
            $query->where('status', $status);
        }
        
        $requests = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json($requests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $enabledFields = \App\Models\FormField::where('is_enabled', true)->orderBy('order', 'asc')->get();
        
        $validationRules = [
            'source' => 'required|string|in:cta_modal,contact_page',
            'event_id' => 'nullable|string',
        ];

        foreach ($enabledFields as $field) {
            $rule = $field->is_required ? 'required' : 'nullable';
            if ($field->type === 'email') {
                $rule .= '|email';
            }
            $validationRules[$field->field_name] = $rule;
        }

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Map core fields for backward compatibility
        $data = $request->only(['source']);
        $data['full_name'] = $request->input('full_name', '');
        $data['phone'] = $request->input('phone', '');
        $data['email'] = $request->input('email', '');
        $data['notes'] = $request->input('notes', '');
        
        $data['project_type'] = $request->input('project_type', '');
        $data['property_size'] = $request->input('property_size', '');
        $data['budget_range'] = $request->input('budget_range', '');
        $data['location'] = $request->input('location', '');

        // Store all dynamic fields inside the form_data JSON column
        $formData = [];
        foreach ($enabledFields as $field) {
            $formData[$field->field_name] = $request->input($field->field_name);
        }
        $data['form_data'] = $formData;

        $consultation = ConsultationRequest::create($data);

        // Dispatch Server-Side Meta Conversions API (CAPI) Event asynchronously
        $eventId = $request->input('event_id');
        SendMetaCapiEventJob::dispatch(
            'Lead',
            $eventId,
            [
                'email' => $data['email'] ?: null,
                'phone' => $data['phone'] ?: null,
                'name' => $data['full_name'] ?: null,
                'client_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'fbp' => $request->cookie('_fbp'),
                'fbc' => $request->cookie('_fbc'),
            ],
            [
                'content_name' => 'Consultation Request',
                'category' => $data['project_type'] ?: 'General Consultation',
                'value' => 0.00,
                'currency' => 'BDT'
            ],
            $request->headers->get('referer')
        );

        return response()->json([
            'message' => 'Consultation request submitted successfully. Our team will contact you soon.',
            'data' => $consultation
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $consultation = ConsultationRequest::find($id);

        if (!$consultation) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        return response()->json($consultation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $consultation = ConsultationRequest::find($id);

        if (!$consultation) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'nullable|string|in:New,Contacted,Qualified,Closed',
            'internal_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $consultation->update($request->only(['status', 'internal_notes']));

        return response()->json([
            'message' => 'Request updated successfully.',
            'data' => $consultation
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $consultation = ConsultationRequest::find($id);

        if (!$consultation) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        $consultation->delete();

        return response()->json(['message' => 'Request deleted successfully.']);
    }
}
