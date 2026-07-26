<?php

namespace App\Services;

use App\Models\Addon;
use App\Models\EstimatorLead;
use App\Models\EstimatorSetting;
use App\Models\Package;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class EstimatorService
{
    /**
     * Calculate the total cost estimate for given inputs.
     *
     * Formula:
     *   Base Cost     = Home Size × Package Base Rate
     *   Total Estimate = Base Cost + Σ(Addon Price × Addon Quantity)
     */
    public function calculateEstimate(float $homeSize, int $packageId, array $addonSelections): array
    {
        $package = Package::findOrFail($packageId);

        // 1. Base cost from home size × package base rate
        $baseCost = $homeSize * (float) $package->base_rate;

        // 2. Add-ons cost from package-specific per-addon prices
        $addonsTotal = 0.00;

        if (!empty($addonSelections)) {
            $addonIds = array_column($addonSelections, 'addon_id');
            $addonPrices = \App\Models\AddonPackagePrice::where('package_id', $packageId)
                ->whereIn('addon_id', $addonIds)
                ->get()
                ->keyBy('addon_id');

            foreach ($addonSelections as $selection) {
                $addonId = $selection['addon_id'];
                $qty     = (int) $selection['quantity'];

                if ($qty > 0 && isset($addonPrices[$addonId])) {
                    $addonsTotal += (float) $addonPrices[$addonId]->price * $qty;
                }
            }
        }

        // 3. Final estimate
        $totalEstimate = $baseCost + $addonsTotal;

        return [
            'base_cost'      => $baseCost,
            'addons_total'   => $addonsTotal,
            'total_estimate' => $totalEstimate,
            'package'        => $package,
        ];
    }

    /**
     * Persist the lead and its associated selected rooms and add-ons inside a transaction.
     */
    public function saveLead(array $data): EstimatorLead
    {
        return DB::transaction(function () use ($data) {
            // 1. Calculate final estimate
            $calculations = $this->calculateEstimate(
                (float) $data['home_size'],
                (int)   $data['package_id'],
                $data['addons'] ?? []
            );

            // 2. Create the Lead record
            $lead = EstimatorLead::create([
                'name'            => $data['name'],
                'phone'           => $data['phone'],
                'email'           => $data['email'],
                'location'        => $data['location'],
                'project_address' => $data['project_address'] ?? null,
                'home_size'       => $data['home_size'],
                'flat_status'     => $data['flat_status'],
                'package_id'      => $data['package_id'],
                'total_estimate'  => $calculations['total_estimate'],
            ]);

            // 3. Save room items (only those with quantity > 0)
            if (!empty($data['rooms'])) {
                foreach ($data['rooms'] as $roomItem) {
                    $qty = (int) $roomItem['quantity'];
                    if ($qty > 0) {
                        $lead->roomItems()->create([
                            'room_id'  => $roomItem['room_id'],
                            'quantity' => $qty,
                        ]);
                    }
                }
            }

            // 4. Save addon items (only those with quantity > 0)
            if (!empty($data['addons'])) {
                foreach ($data['addons'] as $addonItem) {
                    $qty = (int) $addonItem['quantity'];
                    if ($qty > 0) {
                        $lead->addonItems()->create([
                            'addon_id' => $addonItem['addon_id'],
                            'quantity' => $qty,
                        ]);
                    }
                }
            }

            return $lead;
        });
    }

    /**
     * Generate estimate PDF for a specific lead.
     */
    public function generatePdf(int $leadId)
    {
        $lead = EstimatorLead::with([
            'package',
            'roomItems.room',
            'addonItems.addon.room'
        ])->findOrFail($leadId);

        $settings = EstimatorSetting::first();
        if (!$settings) {
            // Fallback default setting instance
            $settings = new EstimatorSetting([
                'pdf_title'        => 'Home Interior Cost Estimate',
                'pdf_company_name' => 'Premium Touch',
                'pdf_address'      => 'Dhaka, Bangladesh',
                'pdf_phone'        => '+880 1700-000000',
            ]);
        }

        $siteSettings = \App\Models\SiteSetting::first();

        // Render blade view and load into dompdf wrapper
        $pdf = Pdf::loadView('pdf.estimate', [
            'lead'         => $lead,
            'settings'     => $settings,
            'siteSettings' => $siteSettings
        ]);

        // Configure options (custom paper size, margins, etc.)
        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }
}
