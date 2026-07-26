<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $settings->pdf_title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2b2b2b;
            line-height: 1.5;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 10px;
        }
        /* Header section */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #c9a45c;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .company-tagline {
            font-size: 11px;
            color: #64748b;
            margin: 2px 0 0 0;
            font-style: italic;
        }
        .company-details {
            text-align: right;
            font-size: 11px;
            color: #475569;
            line-height: 1.4;
        }
        .estimate-title-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #c9a45c;
            padding: 12px 15px;
            margin-bottom: 20px;
        }
        .estimate-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        .estimate-meta {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Two columns details */
        .details-table {
            width: 100%;
            margin-bottom: 25px;
            border-spacing: 0;
        }
        .details-table td {
            vertical-align: top;
            width: 50%;
            padding: 0;
        }
        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: #c9a45c;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 10px;
            margin-right: 15px;
        }
        .info-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .info-list li {
            margin-bottom: 6px;
            font-size: 12px;
        }
        .info-label {
            font-weight: 600;
            color: #475569;
            display: inline-block;
            width: 120px;
        }
        .info-value {
            color: #1e293b;
        }

        /* Main summary table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .items-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-weight: 600;
            text-align: left;
            padding: 8px 10px;
            font-size: 11px;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
        }
        .items-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }

        /* Pricing Box */
        .pricing-box {
            background-color: #1e293b;
            border-radius: 6px;
            padding: 18px 20px;
            margin-bottom: 25px;
        }
        .pricing-label {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 12px;
        }
        .pricing-breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .pricing-breakdown-table td {
            font-size: 12px;
            color: #cbd5e1;
            padding: 4px 0;
        }
        .pricing-breakdown-table .amt {
            text-align: right;
            font-weight: 600;
            color: #e2e8f0;
        }
        .pricing-divider {
            border: none;
            border-top: 1px solid #334155;
            margin: 8px 0;
        }
        .pricing-total-row td {
            color: #c9a45c !important;
            font-size: 14px !important;
            font-weight: 800 !important;
            padding-top: 8px !important;
        }
        .pricing-total-row .amt {
            color: #c9a45c !important;
        }
        .pricing-note {
            font-size: 10px;
            color: #64748b;
            font-style: italic;
            margin-top: 4px;
        }

        /* Terms & Footer */
        .terms-box {
            font-size: 10.5px;
            color: #64748b;
            background-color: #fafafa;
            border: 1px dashed #cbd5e1;
            padding: 12px;
            border-radius: 4px;
            margin-top: 30px;
        }
        .terms-title {
            font-weight: 700;
            color: #475569;
            margin-bottom: 5px;
        }
        .terms-text {
            white-space: pre-line;
            line-height: 1.4;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
        }
        .currency {
            font-size: 0.85em;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                @if($siteSettings->logo)
                <td style="vertical-align: middle; width: 100px; padding-right: 15px;">
                    <img src="{{ public_path('uploads/logo/' . $siteSettings->logo) }}" alt="Logo" style="max-width: 100px; max-height: 100px; object-fit: contain;">
                </td>
                @endif
                <td style="vertical-align: middle;">
                    <h1 class="company-name">{{ $siteSettings->site_name }}</h1>
                    <p class="company-tagline">{{ $siteSettings->tagline }}</p>
                </td>
                <td class="company-details" style="vertical-align: middle;">
                    <div>{{ $siteSettings->address }}</div>
                    <div>Phone: {{ $siteSettings->phone }}</div>
                    <div>Date: {{ $lead->created_at->format('M d, Y h:i A') }}</div>
                </td>
            </tr>
        </table>

        <!-- Estimate ID Title -->
        <div class="estimate-title-box">
            <h2 class="estimate-title">{{ $settings->pdf_title }}</h2>
            <div class="estimate-meta">Estimate Ref: #EST-{{ str_pad($lead->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>

        <!-- Details columns -->
        <table class="details-table">
            <tr>
                <td>
                    <div class="section-title">Client Information</div>
                    <ul class="info-list">
                        <li>
                            <span class="info-label">Name:</span>
                            <span class="info-value"><strong>{{ $lead->name }}</strong></span>
                        </li>
                        <li>
                            <span class="info-label">Phone:</span>
                            <span class="info-value">{{ $lead->phone }}</span>
                        </li>
                        <li>
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ $lead->email }}</span>
                        </li>
                        <li>
                            <span class="info-label">City/Location:</span>
                            <span class="info-value">{{ $lead->location }}</span>
                        </li>
                        @if($lead->project_address)
                        <li>
                            <span class="info-label">Project Address:</span>
                            <span class="info-value">{{ $lead->project_address }}</span>
                        </li>
                        @endif
                    </ul>
                </td>
                <td>
                    <div class="section-title">Project Scope</div>
                    <ul class="info-list">
                        <li>
                            <span class="info-label">Property Size:</span>
                            <span class="info-value"><strong>{{ number_format($lead->home_size) }} sqft</strong></span>
                        </li>
                        <li>
                            <span class="info-label">Flat Status:</span>
                            <span class="info-value">{{ $lead->flat_status }}</span>
                        </li>
                        <li>
                            <span class="info-label">Selected Package:</span>
                            <span class="info-value"><strong>{{ $lead->package->name }}</strong></span>
                        </li>
                        <li>
                            <span class="info-label">Base Rate:</span>
                            <span class="info-value">BDT {{ number_format($lead->package->base_rate) }} / sqft</span>
                        </li>
                    </ul>
                </td>
            </tr>
        </table>

        <!-- Budget Summary Box -->
        @php
            $baseCost     = (float)$lead->home_size * (float)$lead->package->base_rate;
            $addonsTotal  = (float)$lead->total_estimate - $baseCost;
            $addonsTotal  = max(0, $addonsTotal);
        @endphp
        <div class="pricing-box">
            <div class="pricing-label">Cost Breakdown &amp; Total Estimate</div>
            <table class="pricing-breakdown-table">
                <tr>
                    <td>Base Cost <span style="color:#64748b; font-size:11px;">({{ number_format($lead->home_size) }} sqft &times; BDT {{ number_format($lead->package->base_rate) }})</span></td>
                    <td class="amt">BDT {{ number_format($baseCost) }}</td>
                </tr>
                <tr>
                    <td>Selected Add-ons</td>
                    <td class="amt">BDT {{ number_format($addonsTotal) }}</td>
                </tr>
                <tr>
                    <td colspan="2"><hr class="pricing-divider"></td>
                </tr>
                <tr class="pricing-total-row">
                    <td>Total Estimated Cost</td>
                    <td class="amt">BDT {{ number_format($lead->total_estimate) }}</td>
                </tr>
            </table>
            <div class="pricing-note">Approx. estimate. Final price confirmed after site measurement, design and Material selection.</div>
        </div>

        <!-- Selected Rooms -->
        <div style="margin-bottom: 20px;">
            <div class="section-title" style="margin-right: 0;">Selected Rooms &amp; Quantities</div>
            <table class="items-table" style="margin-bottom: 10px;">
                <thead>
                    <tr>
                        <th style="width: 70%;">Room Name</th>
                        <th class="text-center" style="width: 30%;">Selected Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lead->roomItems as $item)
                        <tr>
                            <td>{{ $item->room->name }}</td>
                            <td class="text-center"><strong>{{ $item->quantity }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Selected Add-ons -->
        @if($lead->addonItems->count() > 0)
            <div style="margin-bottom: 20px; page-break-before: auto;">
                <div class="section-title" style="margin-right: 0;">Selected Add-ons Details</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Add-on Name</th>
                            <th style="width: 25%;">Room Area</th>
                            <th class="text-right" style="width: 15%;">Unit Price</th>
                            <th class="text-center" style="width: 10%;">Qty</th>
                            <th class="text-right" style="width: 20%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lead->addonItems as $item)
                            @php
                                $priceRecord = $item->addon->prices->firstWhere('package_id', $lead->package_id);
                                $unitPrice   = $priceRecord ? (float)$priceRecord->price : 0.00;
                                $totalPrice  = $unitPrice * $item->quantity;
                            @endphp
                            <tr>
                                <td>{{ $item->addon->name }}</td>
                                <td>{{ $item->addon->room->name }}</td>
                                <td class="text-right">BDT {{ number_format($unitPrice) }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">BDT {{ number_format($totalPrice) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Terms and conditions -->
        @if($settings->pdf_terms_conditions)
            <div class="terms-box">
                <div class="terms-title">Terms &amp; Conditions:</div>
                <div class="terms-text">{{ $settings->pdf_terms_conditions }}</div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            @if($settings->pdf_footer_text)
                <div>{{ $settings->pdf_footer_text }}</div>
            @endif
            <div style="margin-top: 5px;">Generated automatically by {{ $siteSettings->site_name }} Estimator System.</div>
        </div>
    </div>
</body>
</html>
