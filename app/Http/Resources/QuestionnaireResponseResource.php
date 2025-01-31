<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\QuestionAnswerResource;

class QuestionnaireResponseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'questionnaire_id' => $this->questionnaire_id,
            'tour_leader_id' => $this->tour_leader_id,
            'submitted_at' => $this->submitted_at,
            'status' => $this->status,
            'answers' => QuestionAnswerResource::collection($this->whenLoaded('answers')),
        ];
    }
}
