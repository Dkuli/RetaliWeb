<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\QuestionResource;

class QuestionnaireResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
            'response_status' => $this->tourLeaderAssignments()
                ->where('tour_leader_id', auth()->id())
                ->first()->status ?? 'pending',
        ];
    }
}
