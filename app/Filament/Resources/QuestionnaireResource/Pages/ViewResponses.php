<?php

namespace App\Filament\Resources\QuestionnaireResource\Pages;

use App\Exports\QuestionnaireResponsesExport;
use App\Models\Questionnaire;
use App\Filament\Resources\QuestionnaireResource;
use Filament\Actions\Action;
use Filament\Pages\Actions;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Computed;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewResponses extends Page
{
    protected static string $resource = QuestionnaireResource::class;
    protected static string $view = 'filament.pages.questionnaire-responses';

    public Questionnaire $record;
    public $selectedTourLeader = null;
    public $activeTab = 'summary';

    #[Computed]
    public function responses()
    {
        return $this->record->responses()
            ->with(['tourLeader', 'answers.question'])
            ->get();
    }

    #[Computed]
    public function tourLeaders()
    {
        return $this->record->tourLeaderAssignments()
            ->with('tourLeader')
            ->get()
            ->mapWithKeys(fn ($item) => [
                $item->tour_leader_id => $item->tourLeader->name . " ({$item->status})"
            ]);
    }

    #[Computed]
    public function summary()
    {
        return [
            'total' => $this->record->responses()->count(),
            'completion_rate' => $this->calculateCompletionRate(),
            'questions' => $this->record->questions->map(fn ($question) => [
                'text' => $question->question_text,
                'type' => $question->type,
                'answers' => $this->processAnswers($question)
            ])
        ];
    }

    protected function calculateCompletionRate()
    {
        $assigned = $this->record->tourLeaderAssignments()->count();
        $completed = $this->record->tourLeaderAssignments()->where('status', 'completed')->count();

        return $assigned > 0 ? round(($completed / $assigned) * 100, 2) : 0;
    }

    protected function processAnswers($question)
    {
        return match ($question->type) {
            'multiple_choice' => $question->answers()
                ->select('selected_options')
                ->get()
                ->flatMap(fn ($answer) => $answer->selected_options)
                ->countBy(),
            default => $question->answers()
                ->select('answer_text')
                ->limit(5)
                ->pluck('answer_text')
        };
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label('Export Excel')
                ->action(fn () => Excel::download(
                    new QuestionnaireResponsesExport($this->record),
                    "responses-{$this->record->id}.xlsx"
                )),

            Action::make('export_pdf')
                ->label('Export PDF')
                ->action(function () {
                    return response()->streamDownload(function () {
                        echo Pdf::loadView('exports.questionnaire', [
                            'record' => $this->record,
                            'summary' => $this->summary
                        ])->stream();
                    }, "responses-{$this->record->id}.pdf");
                })
        ];
    }
}
