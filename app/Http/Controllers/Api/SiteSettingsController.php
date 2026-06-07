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
                'career_email' => 'career@premiumtouchbd.com',
                'address' => 'Dhaka, Bangladesh',
                'map_url' => 'https://maps.google.com',
                'facebook_page_url' => 'https://facebook.com/premiumtouch',
                'instagram_page_url' => 'https://instagram.com',
                'linkedin_page_url' => 'https://linkedin.com'
            ]);
        }

        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $settings = SiteSetting::first() ?? new SiteSetting();

        $data = $request->only([
            'site_name', 'tagline', 'short_description', 'about_page_description',
            'phone', 'email', 'career_email', 'address', 'map_url', 'map_embed_url', 
            'facebook_page_url', 'instagram_page_url', 'linkedin_page_url',
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

        if ($request->hasFile('about_page_office_image')) {
            // Delete previous image if exists
            if ($settings->about_page_office_image && file_exists(public_path('uploads/about/' . $settings->about_page_office_image))) {
                @unlink(public_path('uploads/about/' . $settings->about_page_office_image));
            }
            $officeImg = $request->file('about_page_office_image');
            $officeImgName = 'about_office_' . time() . '.' . $officeImg->getClientOriginalExtension();
            $officeImg->move(public_path('uploads/about'), $officeImgName);
            $data['about_page_office_image'] = $officeImgName;
        }

        if ($request->input('clear_office_image') === '1') {
            if ($settings->about_page_office_image && file_exists(public_path('uploads/about/' . $settings->about_page_office_image))) {
                @unlink(public_path('uploads/about/' . $settings->about_page_office_image));
            }
            $data['about_page_office_image'] = null;
        }

        $settings->fill($data);
        $settings->save();

        return response()->json(['message' => 'Settings updated successfully', 'settings' => $settings]);
    }
}
