<?php

namespace App\Exports;

use App\Models\Questionnaire;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuestionnaireResponsesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $questionnaire;

    public function __construct(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire->load(['responses.answers']);
    }

    public function collection()
    {
        return $this->questionnaire->responses;
    }

    public function headings(): array
    {
        $headings = [
            'Tour Leader',
            'Tanggal Submit',
            'Status'
        ];

        foreach ($this->questionnaire->questions as $question) {
            $headings[] = $question->question_text;
        }

        return $headings;
    }

    public function map($response): array
    {
        $row = [
            $response->tourLeader->name,
            $response->submitted_at->format('d/m/Y H:i'),
            $response->status
        ];

        foreach ($this->questionnaire->questions as $question) {
            $answer = $response->answers->firstWhere('question_id', $question->id);
            $row[] = $this->formatAnswer($answer, $question);
        }

        return $row;
    }

    private function formatAnswer($answer, $question)
    {
        if (!$answer) return '-';

        return match ($question->type) {
            'multiple_choice' => implode(', ', $answer->selected_options),
            'rating' => $answer->answer_text . '/5',
            default => $answer->answer_text
        };
    }
}
