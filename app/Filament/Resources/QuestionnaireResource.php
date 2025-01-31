<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionnaireResource\Pages;
use App\Models\Questionnaire;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\TourLeader;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\NewQuestionnaireAssigned;
use Filament\Notifications\Notification;
use App\Services\FcmService;

class QuestionnaireResource extends Resource
{
    protected static ?string $model = Questionnaire::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535),
                        Forms\Components\DateTimePicker::make('start_date')
                            ->required(),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->required(),
                        Forms\Components\Toggle::make('is_template')
                            ->label('Save as Template'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'closed' => 'Closed',
                            ])
                            ->required(),
                        Forms\Components\Repeater::make('questions')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('question_text')
                                    ->required()
                                    ->label('Question'),
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'multiple_choice' => 'Multiple Choice',
                                        'text' => 'Text',
                                        'rating' => 'Rating',
                                    ])
                                    ->required()
                                    ->reactive(),
                                Forms\Components\TagsInput::make('options')
                                    ->visible(fn ($get) => $get('type') === 'multiple_choice'),
                                Forms\Components\Toggle::make('is_required')
                                    ->label('Required')
                                    ->default(true),
                                Forms\Components\TextInput::make('order')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->orderColumn('order')
                            ->defaultItems(1)
                            ->createItemButtonLabel('Add Question')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'closed' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime(),
                Tables\Columns\BooleanColumn::make('is_template'),
                Tables\Columns\TextColumn::make('responses_count')
                    ->counts('responses')
                    ->label('Responses'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'closed' => 'Closed',
                    ]),
                Tables\Filters\Filter::make('is_template')
                    ->query(fn (Builder $query): Builder => $query->where('is_template', true))
                    ->label('Templates Only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Model $record): string => route('filament.admin.resources.questionnaires.preview', $record))
                    ->openUrlInNewTab()
                    ->visible(fn (Model $record): bool => $record->status === 'draft'),
                    Tables\Actions\Action::make('assign')
                    ->action(function (Model $record, array $data): void {
                        $fcmService = new FcmService();
                        $tourLeaderIds = $data['tour_leaders'];

                        foreach ($tourLeaderIds as $tourLeaderId) {
                            $record->tourLeaderAssignments()
                                ->updateOrCreate(
                                    ['tour_leader_id' => $tourLeaderId],
                                    ['assigned_at' => now(), 'status' => 'pending']
                                );

                            $tourLeader = TourLeader::find($tourLeaderId);
                            if ($tourLeader && $tourLeader->fcm_token) {
                                $fcmService->sendNotification(
                                    [$tourLeader->fcm_token],
                                    'Kuesioner Baru Ditugaskan',
                                    "Anda telah ditugaskan untuk mengisi kuesioner: {$record->title}"
                                );
                            }
                        }

                        Notification::make()
                            ->title('Kuesioner ditugaskan ke ' . count($tourLeaderIds) . ' Tour Leaders')
                            ->success()
                            ->send();
                    })
                    ->form([
                        Forms\Components\Select::make('tour_leaders')
                            ->label('Tour Leaders')
                            ->multiple()
                            ->options(TourLeader::all()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->visible(fn (Model $record): bool => $record->status === 'published'),
                Tables\Actions\Action::make('view_responses')
                    ->url(fn (Model $record): string => route('filament.admin.resources.questionnaires.responses', $record))
                    ->visible(fn (Model $record): bool => $record->responses()->exists()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestionnaires::route('/'),
            'create' => Pages\CreateQuestionnaire::route('/create'),
            'edit' => Pages\EditQuestionnaire::route('/{record}/edit'),
            'responses' => Pages\ViewResponses::route('/{record}/responses'),
            'preview' => Pages\PreviewQuestionnaire::route('/{record}/preview'),
        ];
    }
}
