<?php

namespace Database\Seeders;

use App\Models\FormField;
use Illuminate\Database\Seeder;

class FormFieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            [
                'field_name' => 'full_name',
                'label' => 'Full Name',
                'type' => 'text',
                'placeholder' => 'e.g. John Doe',
                'options' => null,
                'is_required' => true,
                'is_enabled' => true,
                'order' => 1,
            ],
            [
                'field_name' => 'phone',
                'label' => 'Phone Number',
                'type' => 'tel',
                'placeholder' => 'e.g. +880 1711000000',
                'options' => null,
                'is_required' => true,
                'is_enabled' => true,
                'order' => 2,
            ],
            [
                'field_name' => 'email',
                'label' => 'Email Address',
                'type' => 'email',
                'placeholder' => 'e.g. john@example.com',
                'options' => null,
                'is_required' => true,
                'is_enabled' => true,
                'order' => 3,
            ],
            [
                'field_name' => 'project_type',
                'label' => 'Project Type',
                'type' => 'select',
                'placeholder' => null,
                'options' => [
                    'Residential Interior',
                    'Commercial Office',
                    'Hospitality / Restaurant',
                    'Retail Showroom',
                    'Architectural Design',
                    'Other'
                ],
                'is_required' => true,
                'is_enabled' => true,
                'order' => 4,
            ],
            [
                'field_name' => 'property_size',
                'label' => 'Property Size (Sq Ft)',
                'type' => 'text',
                'placeholder' => 'e.g. 2,400',
                'options' => null,
                'is_required' => true,
                'is_enabled' => true,
                'order' => 5,
            ],
            [
                'field_name' => 'budget_range',
                'label' => 'Budget Range',
                'type' => 'select',
                'placeholder' => null,
                'options' => [
                    'Below 5 Lakhs',
                    '5 - 10 Lakhs',
                    '10 - 20 Lakhs',
                    '20 - 50 Lakhs',
                    '50+ Lakhs'
                ],
                'is_required' => true,
                'is_enabled' => true,
                'order' => 6,
            ],
            [
                'field_name' => 'location',
                'label' => 'Project Location',
                'type' => 'text',
                'placeholder' => 'e.g. Banani, Dhaka',
                'options' => null,
                'is_required' => true,
                'is_enabled' => true,
                'order' => 7,
            ],
            [
                'field_name' => 'notes',
                'label' => 'Tell Us About Your Project',
                'type' => 'textarea',
                'placeholder' => 'Describe your design goals, timeline, or special requirements...',
                'options' => null,
                'is_required' => false,
                'is_enabled' => true,
                'order' => 8,
            ],
        ];

        foreach ($fields as $field) {
            FormField::updateOrCreate(
                ['field_name' => $field['field_name']],
                $field
            );
        }
    }
}
