<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Luggage extends Model
{
    use HasFactory;

    protected $fillable = [
        'luggage_number', 'pilgrim_name', 'phone', 'group'
    ];

    protected $casts = [
        'phone' => 'string',
    ];

    public function scans()
    {
        return $this->hasMany(LuggageScan::class);
    }
}
