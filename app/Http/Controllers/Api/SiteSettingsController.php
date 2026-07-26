<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Traits\HasImageUploads;

class SiteSettingsController extends Controller
{
    use HasImageUploads;
    /**
     * Get site settings (Navbar, CTA, Footer)
     */
    public function index()
    {
        $settings = SiteSetting::first();

        // Fetch marketing settings from SystemSetting to expose to frontend safely
        $marketingSettings = \Illuminate\Support\Facades\Cache::remember('public_marketing_settings', 3600, function () {
            return \App\Models\SystemSetting::whereIn('key', [
                'analytics_enabled',
                'gtm_id',
                'meta_pixel_id'
            ])->pluck('value', 'key')->toArray();
        });

        $extraSettings = [
            'analytics_enabled' => filter_var($marketingSettings['analytics_enabled'] ?? env('ANALYTICS_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
            'gtm_id' => $marketingSettings['gtm_id'] ?? env('VITE_GTM_ID', ''),
            'meta_pixel_id' => $marketingSettings['meta_pixel_id'] ?? env('VITE_META_PIXEL_ID', '')
        ];

        // Fallback safety
        if (!$settings) {
            $response = [
                'logo' => '/default-logo.jpg',
                'site_name' => "Premium Touch\nInterior Decor Studio",
                'tagline' => 'Interior & Architectural Design',
                'short_description' => 'We design elegant, functional and modern interior spaces.',
                'about_page_description' => 'We are a boutique interior and architectural design studio dedicated to creating elegant, functional, and modern spaces. Our focus is blending luxury aesthetics with daily utility to transform spaces into highly personalized sanctuaries.',
                'about_page_office_image' => null,
                'phone' => '+8801000000000',
                'email' => 'info@example.com',
                'career_email' => 'career@premiumtouchbd.com',
                'address' => 'Dhaka, Bangladesh',
                'map_url' => 'https://maps.google.com',
                'facebook_page_url' => 'https://facebook.com/premiumtouch',
                'instagram_page_url' => 'https://instagram.com',
                'linkedin_page_url' => 'https://linkedin.com'
            ];
        } else {
            $response = $settings->toArray();
        }

        $response = array_merge($response, $extraSettings);

        return response()->json($response);
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
            // Delete previous image if exists
            if ($settings->logo && file_exists(public_path('uploads/logo/' . $settings->logo))) {
                @unlink(public_path('uploads/logo/' . $settings->logo));
            }
            $logo = $request->file('logo');
            $logoName = 'logo_' . time();
            $data['logo'] = $this->optimizeAndSaveImage($logo, public_path('uploads/logo'), $logoName, 1920, 80, true);
        }

        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $ext = strtolower($favicon->getClientOriginalExtension());

            // Delete previous favicon if exists
            if ($settings->favicon && file_exists(public_path('uploads/logo/' . $settings->favicon))) {
                @unlink(public_path('uploads/logo/' . $settings->favicon));
            }

            if (in_array($ext, ['ico', 'svg'])) {
                // Direct file upload for .ico and .svg files (GD doesn't support processing ICO/SVG)
                $faviconName = 'favicon_' . time() . '.' . $ext;
                $favicon->move(public_path('uploads/logo'), $faviconName);
                $data['favicon'] = $faviconName;
            } else {
                try {
                    $faviconName = 'favicon_' . time();
                    $data['favicon'] = $this->optimizeAndSaveImage($favicon, public_path('uploads/logo'), $faviconName, 512, 80, true);
                } catch (\Exception $e) {
                    // Fail-safe fallback to direct file upload if GD fails
                    $faviconName = 'favicon_' . time() . '.' . ($ext ?: 'png');
                    $favicon->move(public_path('uploads/logo'), $faviconName);
                    $data['favicon'] = $faviconName;
                }
            }
        }

        if ($request->hasFile('header_bg')) {
            // Delete previous image if exists
            if ($settings->header_bg && file_exists(public_path('uploads/header/' . $settings->header_bg))) {
                @unlink(public_path('uploads/header/' . $settings->header_bg));
            }
            $headerBg = $request->file('header_bg');
            $headerBgName = 'project_header_' . time();
            $data['header_bg'] = $this->optimizeAndSaveImage($headerBg, public_path('uploads/header'), $headerBgName, 1920, 80, true);
        }

        if ($request->hasFile('cta_bg')) {
            // Delete previous image if exists
            if ($settings->cta_bg && file_exists(public_path('uploads/cta/' . $settings->cta_bg))) {
                @unlink(public_path('uploads/cta/' . $settings->cta_bg));
            }
            $ctaBg = $request->file('cta_bg');
            $ctaBgName = 'gallery_cta_' . time();
            $data['cta_bg'] = $this->optimizeAndSaveImage($ctaBg, public_path('uploads/cta'), $ctaBgName, 1920, 80, true);
        }

        if ($request->hasFile('about_page_office_image')) {
            // Delete previous image if exists
            if ($settings->about_page_office_image && file_exists(public_path('uploads/about/' . $settings->about_page_office_image))) {
                @unlink(public_path('uploads/about/' . $settings->about_page_office_image));
            }
            $officeImg = $request->file('about_page_office_image');
            $officeImgName = 'about_office_' . time();
            $data['about_page_office_image'] = $this->optimizeAndSaveImage($officeImg, public_path('uploads/about'), $officeImgName, 1920, 80, true);
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
