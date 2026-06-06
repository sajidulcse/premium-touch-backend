<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingsController extends Controller
{
    /**
     * Get site settings (Navbar, CTA, Footer)
     */
    public function index()
    {
        $settings = SiteSetting::first();

        // Fallback safety
        if (!$settings) {
            return response()->json([
                'logo' => '/default-logo.jpg',
                'site_name' => "Premium Touch\nInterior Decor Studio",
                'tagline' => 'Interior & Architectural Design',
                'short_description' => 'We design elegant, functional and modern interior spaces.',
                'phone' => '+8801000000000',
                'email' => 'info@example.com',
                'address' => 'Dhaka, Bangladesh',
                'map_url' => 'https://maps.google.com',
                'facebook_page_url' => 'https://facebook.com/premiumtouch'
            ]);
        }

        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $settings = SiteSetting::first() ?? new SiteSetting();

        $data = $request->only([
            'site_name', 'tagline', 'short_description', 
            'phone', 'email', 'address', 'map_url', 'map_embed_url', 
            'facebook_page_url',
            'stat_1_num', 'stat_1_label',
            'stat_2_num', 'stat_2_label',
            'stat_3_num', 'stat_3_label',
            'stat_4_num', 'stat_4_label'
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/logo'), $logoName);
            $data['logo'] = $logoName;
        }

        if ($request->hasFile('header_bg')) {
            $headerBg = $request->file('header_bg');
            $headerBgName = 'project_header_' . time() . '.' . $headerBg->getClientOriginalExtension();
            $headerBg->move(public_path('uploads/header'), $headerBgName);
            $data['header_bg'] = $headerBgName;
        }

        if ($request->hasFile('cta_bg')) {
            $ctaBg = $request->file('cta_bg');
            $ctaBgName = 'gallery_cta_' . time() . '.' . $ctaBg->getClientOriginalExtension();
            $ctaBg->move(public_path('uploads/cta'), $ctaBgName);
            $data['cta_bg'] = $ctaBgName;
        }

        $settings->fill($data);
        $settings->save();

        return response()->json(['message' => 'Settings updated successfully', 'settings' => $settings]);
    }
}
