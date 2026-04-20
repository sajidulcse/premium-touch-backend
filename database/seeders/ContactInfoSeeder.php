<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactInfo;

class ContactInfoSeeder extends Seeder
{
    public function run(): void
    {
        // Prevent duplicate contact info
        if (ContactInfo::count() === 0) {
            ContactInfo::create([
                'phone'   => '+8801712345678',
                'email'   => 'info@paintexhouse.com',
                'address' => 'House 12, Road 5, Dhanmondi, Dhaka-1209, Bangladesh'
            ]);
        }
    }
}
