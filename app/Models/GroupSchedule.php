<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'date',
        'day_title',
        'description'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function activities()
    {
        return $this->hasMany(ScheduleActivity::class);
    }
}
