<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionnaireTourLeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'tour_leader_id',
        'assigned_at',
        'completed_at',
        'status', // pending, completed, expired
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function tourLeader()
    {
        return $this->belongsTo(TourLeader::class);
    }
}
