<?php
// app/Http/Requests/StoreLocationRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric',
            'speed' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
            'heading' => 'nullable|numeric',
            'battery_level' => 'nullable|numeric|between:0,100',
            'activity_type' => 'nullable|string',
            'is_moving' => 'nullable|boolean',
            'tracked_at' => 'nullable|date',
            'device_info' => 'nullable|array',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
        ];
    }
}

class StoreBatchLocationsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'locations' => 'required|array|min:1',
            'locations.*.latitude' => 'required|numeric|between:-90,90',
            'locations.*.longitude' => 'required|numeric|between:-180,180',
            'locations.*.accuracy' => 'nullable|numeric',
            'locations.*.speed' => 'nullable|numeric',
            'locations.*.altitude' => 'nullable|numeric',
            'locations.*.heading' => 'nullable|numeric',
            'locations.*.battery_level' => 'nullable|numeric|between:0,100',
            'locations.*.activity_type' => 'nullable|string',
            'locations.*.is_moving' => 'nullable|boolean',
            'locations.*.tracked_at' => 'nullable|date',
            'locations.*.device_info' => 'nullable|array',
            'locations.*.address' => 'nullable|string',
            'locations.*.city' => 'nullable|string',
            'locations.*.country' => 'nullable|string',
        ];
    }
}
