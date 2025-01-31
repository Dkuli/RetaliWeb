<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScheduleActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_schedule_id',
        'time',
        'title',
        'description',
        'location',
        'category'
    ];

    protected $casts = [
        'time' => 'datetime'
    ];

    public function groupSchedule()
    {
        return $this->belongsTo(GroupSchedule::class);
    }
}
