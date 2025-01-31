<?php

namespace App\Filament\Resources\QuestionnaireResource\Pages;

use App\Filament\Resources\QuestionnaireResource;
use Filament\Actions\Action;
use App\Models\Questionnaire;
use Filament\Resources\Pages\Page;

class PreviewQuestionnaire extends Page
{
    protected static string $resource = QuestionnaireResource::class;
    protected static string $view = 'filament.resources.questionnaire-resource.pages.preview-questionnaire';

    public $record;

    public function mount($record)
    {
        $this->record = Questionnaire::findOrFail($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('publish')
                ->label('Publikasikan')
                ->color('success')
                ->icon('heroicon-o-check')
                ->action(function () {
                    $this->record->update(['status' => 'published']);
                    $this->redirect(QuestionnaireResource::getUrl('index'));
                })
                ->requiresConfirmation()
                ->modalHeading('Publikasi Kuesioner')
                ->modalDescription('Apakah yakin ingin mempublikasikan kuesioner ini?')
                ->visible(fn () => $this->record->status === 'draft')
        ];
    }
}
