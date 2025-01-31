<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'accuracy' => $this->accuracy,
            'speed' => $this->speed,
            'altitude' => $this->altitude,
            'heading' => $this->heading,
            'battery_level' => $this->battery_level,
            'activity_type' => $this->activity_type,
            'is_moving' => $this->is_moving,
            'tracked_at' => $this->tracked_at,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'device_info' => $this->device_info,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
