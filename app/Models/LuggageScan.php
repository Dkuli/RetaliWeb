<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuggageScan extends Model
{
    use HasFactory;

    protected $fillable = [
        'luggage_id', 'tour_leader_id', 'scanned_at', 'latitude', 'longitude'
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function luggage()
    {
        return $this->belongsTo(Luggage::class);
    }

    public function tourLeader()
    {
        return $this->belongsTo(TourLeader::class);
    }
}
