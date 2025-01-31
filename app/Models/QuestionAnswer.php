<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_response_id',
        'question_id',
        'answer_text',
        'selected_options',
    ];

    protected $casts = [
        'selected_options' => 'array',
    ];

    public function response()
    {
        return $this->belongsTo(QuestionnaireResponse::class, 'questionnaire_response_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
