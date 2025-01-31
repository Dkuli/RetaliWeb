<?php

namespace App\Filament\Resources\QuestionnaireResource\Pages;

use App\Filament\Resources\QuestionnaireResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms;
use App\Exports\QuestionnaireTemplateExport;
use App\Imports\QuestionnairesImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;

class ListQuestionnaires extends ListRecords
{
    protected static string $resource = QuestionnaireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('download-template')
                ->label('Download Template')
                ->icon('heroicon-o-document-arrow-down')
                ->color('gray')
                ->action(function () {
                    return Excel::download(
                        new QuestionnaireTemplateExport(),
                        'questionnaire-template.xlsx'
                    );
                }),
            Action::make('import')
                ->label('Import dari Template')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    Forms\Components\FileUpload::make('file')
                        ->label('File Excel')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel'
                        ])
                        ->required()
                        ->rules('mimes:xlsx,xls')
                        ->helperText('Download template terlebih dahulu untuk format yang benar')
                ])
                ->action(function (array $data) {
                    try {
                        Excel::import(new QuestionnairesImport, $data['file']);

                        Notification::make()
                            ->title('Import Berhasil')
                            ->body('Kuesioner berhasil diimpor dari file template')
                            ->success()
                            ->send();

                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Import Gagal')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();

                        throw $e;
                    }
                })
        ];
    }
}
