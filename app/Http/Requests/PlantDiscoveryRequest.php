<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class PlantDiscoveryRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();  // Set to true to authorize all users for now, adjust for your auth logic.
    }
    /**
     * @bodyParam plant_id integer required ID of the discovered plant. Example: 8
     * @bodyParam latitude float required GPS latitude. Example: 35.7861837
     * @bodyParam longitude float required GPS longitude. Example: 37.5015554
     */
    public function rules(): array
    {
        return [
            'latitude' => 'nullable|numeric|between:-90,90',  // Latitude validation
            'longitude' => 'nullable|numeric|between:-180,180',  // Longitude validation
            'area_name' => 'required|string',
            'is_protected_area' => 'nullable|boolean',
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB
        ];
    }
}
