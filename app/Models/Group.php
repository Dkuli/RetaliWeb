<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'departure_date',
        'return_date',
        'itinerary',
        'is_active',
        'max_capacity',
        'description'
    ];

    protected $casts = [
        'itinerary' => 'array',
        'departure_date' => 'date',
        'return_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function pilgrims()
    {
        return $this->belongsToMany(Pilgrim::class, 'group_pilgrim');
    }

    public function schedules()
    {
        return $this->hasMany(GroupSchedule::class);
    }

    public function tourLeaders()
    {
        return $this->hasMany(TourLeader::class, 'current_group_id');
    }

  

    public function contents()
    {
        return $this->hasMany(Content::class);
    }
}
