<?php

namespace Database\Seeders;

use App\Models\CareerOpening;
use Illuminate\Database\Seeder;

class CareerOpeningSeeder extends Seeder
{
    public function run(): void
    {
        CareerOpening::create([
            'title' => 'Senior Interior Architect',
            'type' => 'Full-Time',
            'location' => 'Dhaka, BD (On-site)',
            'exp' => '5+ Years',
            'desc' => 'Lead residential and triplex/duplex layout projects. Create material schedules, supervise visualization accuracy, and guide implementation detailing.',
            'status' => true,
            'position' => 1
        ]);

        CareerOpening::create([
            'title' => '3D Visualizer & Render Specialist',
            'type' => 'Full-Time',
            'location' => 'Dhaka, BD (On-site)',
            'exp' => '3+ Years',
            'desc' => 'Develop photorealistic renders, walkthrough animations, and lighting simulations using 3ds Max/V-Ray/Lumion for high-end clients.',
            'status' => true,
            'position' => 2
        ]);

        CareerOpening::create([
            'title' => 'Site Execution Supervisor',
            'type' => 'Full-Time',
            'location' => 'Dhaka, BD (Field-based)',
            'exp' => '2+ Years',
            'desc' => 'Coordinate vendor timelines, audit material delivery quality at sites, monitor wood fabrication/marble fittings, and prepare progress logs.',
            'status' => true,
            'position' => 3
        ]);
    }
}
