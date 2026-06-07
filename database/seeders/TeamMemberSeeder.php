<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        // Executive 1: CEO
        TeamMember::create([
            'name' => 'Tasnim Alam Chowdhury',
            'role' => 'CEO',
            'quote' => 'Great interior architecture is not just about choosing colors or premium fabrics. It is about understanding the physical rhythm of human lives inside a structure and molding materials to elevate that experience.',
            'desc' => 'With over 12 years of experience leading projects across residential, office, and hospitality sectors, Tasnim guides the aesthetic vision, corporate strategy, and quality standards of Premium Touch Studio. She collaborates closely with clients to shape bespoke narratives.',
            'image' => 'team/about_creator.png',
            'linkedin' => 'https://linkedin.com/in/tasnim-alam',
            'instagram' => 'https://instagram.com/tasnim.alam',
            'facebook' => 'https://facebook.com/tasnim.alam',
            'email' => 'tasnim@premiumtouch.com',
            'website' => 'https://tasnimalam.com',
            'is_executive' => true,
            'position' => 1
        ]);

        // Executive 2: Managing Director
        TeamMember::create([
            'name' => 'Farhan Chowdhury',
            'role' => 'Managing Director',
            'quote' => 'Bespoke spaces require a perfect harmony between bold creative vision and flawless site execution. We translate dreams into structural realities.',
            'desc' => 'With a strong background in project management and structural design, Farhan oversees business operations, execution pipelines, and partner collaborations, ensuring every project is delivered on time with unmatched quality.',
            'image' => 'team/about_md.png',
            'linkedin' => 'https://linkedin.com/in/farhan-chowdhury',
            'instagram' => 'https://instagram.com/farhan.chowdhury',
            'facebook' => 'https://facebook.com/farhan.chowdhury',
            'email' => 'farhan@premiumtouch.com',
            'website' => 'https://farhanchowdhury.com',
            'is_executive' => true,
            'position' => 2
        ]);

        // Collaborators
        TeamMember::create([
            'name' => 'Sarah Kabir',
            'role' => 'Principal Interior Architect',
            'desc' => 'Focuses on commercial developments, spatial flows, and custom layout styling.',
            'image' => 'team/team_sarah.png',
            'is_executive' => false,
            'position' => 3
        ]);

        TeamMember::create([
            'name' => 'Rakib Hassan',
            'role' => 'Senior 3D Visualizer & Modeler',
            'desc' => 'Expert in photorealistic engine renders, lightning blueprints, and material simulations.',
            'image' => 'team/team_rakib.png',
            'is_executive' => false,
            'position' => 4
        ]);

        TeamMember::create([
            'name' => 'Maria Ahmed',
            'role' => 'Bespoke Furniture Designer',
            'desc' => 'Curates custom cabinets, wood joinery details, and soft furnishing textures.',
            'image' => 'team/team_maria.png',
            'is_executive' => false,
            'position' => 5
        ]);

        TeamMember::create([
            'name' => 'Tanvir Rahman',
            'role' => 'Lead Site Execution Engineer',
            'desc' => 'Supervises turnkey installations, vendor timelines, and site detailing quality.',
            'image' => 'team/team_tanvir.png',
            'is_executive' => false,
            'position' => 6
        ]);
    }
}

