<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionnaireResource;
use App\Models\Questionnaire;
use App\Models\QuestionnaireResponse;
use App\Models\TourLeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionnaireController extends Controller
{
    public function index(Request $request)
    {
        $tourLeader = TourLeader::find(auth()->id());

        $questionnaires = $tourLeader->assignedQuestionnaires()
            ->where('status', 'published')
            ->where('end_date', '>', now())
            ->with(['questions'])
            ->get();

        return QuestionnaireResource::collection($questionnaires);
    }

    public function show(Questionnaire $questionnaire)
    {
        return new QuestionnaireResource($questionnaire->load('questions'));
    }

    public function submit(Request $request, Questionnaire $questionnaire)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer_text' => 'required_without:answers.*.selected_options',
            'answers.*.selected_options' => 'required_without:answers.*.answer_text|array',
        ]);

        return DB::transaction(function () use ($request, $questionnaire) {
            $response = QuestionnaireResponse::create([
                'questionnaire_id' => $questionnaire->id,
                'tour_leader_id' => auth()->id(),
                'submitted_at' => now(),
                'status' => 'submitted'
            ]);

            foreach ($request->answers as $answer) {
                $response->answers()->create([
                    'question_id' => $answer['question_id'],
                    'answer_text' => $answer['answer_text'] ?? null,
                    'selected_options' => $answer['selected_options'] ?? null,
                ]);
            }

            $questionnaire->tourLeaderAssignments()
                ->where('tour_leader_id', auth()->id())
                ->update([
                    'status' => 'completed',
                    'completed_at' => now()
                ]);

            return response()->json([
                'message' => 'Questionnaire submitted successfully',
                'data' => $response->load('answers')
            ]);
        });
    }
}
