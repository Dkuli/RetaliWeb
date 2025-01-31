<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionnaireResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'tour_leader_id',
        'submitted_at',
        'status', // draft, submitted
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function tourLeader()
    {
        return $this->belongsTo(TourLeader::class);
    }

    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class);
    }
}
