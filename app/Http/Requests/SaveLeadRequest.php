<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'location' => 'required|string|max:255',
            'project_address' => 'nullable|string|max:1000',
            'home_size' => 'required|numeric|min:100|max:100000',
            'flat_status' => 'required|string|in:New Flat,Under Construction,Renovation',
            'package_id' => 'required|integer|exists:packages,id',
            'otp' => 'required|string',
            'event_id' => 'nullable|string',

            
            // Rooms selection validation: e.g., rooms => [ { room_id: 1, quantity: 2 }, ... ]
            'rooms' => 'required|array|min:1',
            'rooms.*.room_id' => 'required|integer|exists:rooms,id',
            'rooms.*.quantity' => 'required|integer|min:0',
            
            // Add-ons selection validation (optional): e.g., addons => [ { addon_id: 1, quantity: 2 }, ... ]
            'addons' => 'nullable|array',
            'addons.*.addon_id' => 'required|integer|exists:addons,id',
            'addons.*.quantity' => 'required|integer|min:0',
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'rooms.required' => 'You must select at least one room for the estimation.',
            'rooms.*.room_id.exists' => 'The selected room type is invalid.',
            'rooms.*.quantity.min' => 'Room quantity cannot be negative.',
            'addons.*.addon_id.exists' => 'The selected add-on is invalid.',
            'addons.*.quantity.min' => 'Add-on quantity cannot be negative.',
        ];
    }
}
