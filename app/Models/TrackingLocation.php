<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingLocation extends Model
{
    protected $fillable = [
        'tour_leader_id',
        'latitude',
        'longitude',
        'accuracy',
        'speed',
        'battery_level',
        'address',
        'tracked_at'
    ];

    protected $casts = [
        'tracked_at' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
        'accuracy' => 'float',
        'speed' => 'float',
        'battery_level' => 'integer'
    ];

    public function tourLeader()
    {
        return $this->belongsTo(TourLeader::class);
    }
}
