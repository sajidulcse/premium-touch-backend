<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EstimatorSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasImageUploads;

class EstimatorSettingController extends Controller
{
    use HasImageUploads;
    /**
     * Display the current settings.
     */
    public function show()
    {
        $settings = EstimatorSetting::first();

        if (!$settings) {
            // Provide default fallback settings
            $settings = EstimatorSetting::create([
                'pdf_title' => 'Home Interior Cost Estimate',
                'pdf_company_name' => 'Premium Touch',
                'pdf_address' => 'Dhaka, Bangladesh',
                'pdf_phone' => '+880 1700-000000',
                'pdf_footer_text' => 'Thank you for choosing Premium Touch.',
                'pdf_terms_conditions' => 'This is an approximate cost estimation based on standard layouts.',
            ]);
        }

        return response()->json($settings);
    }

    /**
     * Update the current settings.
     */
    public function update(Request $request)
    {
        $settings = EstimatorSetting::first();

        if (!$settings) {
            $settings = new EstimatorSetting();
        }

        $validated = $request->validate([
            'pdf_title' => 'required|string|max:255',
            'pdf_company_name' => 'required|string|max:255',
            'pdf_address' => 'required|string|max:255',
            'pdf_phone' => 'required|string|max:50',
            'pdf_footer_text' => 'nullable|string',
            'pdf_terms_conditions' => 'nullable|string',
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = $request->only([
            'pdf_title',
            'pdf_company_name',
            'pdf_address',
            'pdf_phone',
            'pdf_footer_text',
            'pdf_terms_conditions',
        ]);

        if ($request->hasFile('logo_file')) {
            // Delete old logo if exists
            if ($settings->logo && file_exists(public_path('uploads/estimator/' . $settings->logo))) {
                @unlink(public_path('uploads/estimator/' . $settings->logo));
            }

            $logo = $request->file('logo_file');
            $logoName = 'estimator_logo_' . time();
            $data['logo'] = $this->optimizeAndSaveImage($logo, public_path('uploads/estimator'), $logoName, 1920, 80, true);
        }

        $settings->fill($data);
        $settings->save();

        return response()->json([
            'message' => 'Estimator template settings updated successfully.',
            'settings' => $settings
        ]);
    }
}
