<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LuggageResource\Pages;
use App\Models\Luggage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Card;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ActionGroup;

class LuggageResource extends Resource
{
    protected static ?string $model = Luggage::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Luggage Information')
                    ->schema([
                        Forms\Components\TextInput::make('luggage_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('Enter unique luggage number'),

                        Forms\Components\TextInput::make('pilgrim_name')
                            ->required()
                            ->placeholder('Enter pilgrim name'),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->placeholder('Enter phone number'),

                        Forms\Components\TextInput::make('group')
                            ->required()
                            ->placeholder('Enter group name and date'),
                    ])->columns(2),

                Section::make('Scan History')
                    ->schema([
                        Forms\Components\Placeholder::make('scans')
                            ->content(function ($record) {
                                if (!$record) return 'Save the luggage first to view scan history.';

                                return view('filament.components.scan-history', [
                                    'scans' => $record->scans()->latest()->get()
                                ]);
                            })
                    ])
                    ->visible(fn ($record) => $record !== null)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('luggage_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pilgrim_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('group')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('scans_count')
                    ->counts('scans')
                    ->label('Scan Count')
                    ->sortable(),

                Tables\Columns\TextColumn::make('latest_scan')
                    ->getStateUsing(function ($record) {
                        $latestScan = $record->scans()->latest('scanned_at')->first();
                        return $latestScan ? $latestScan->scanned_at->diffForHumans() : '-';
                    })
                    ->label('Last Scanned'),
            ])
            ->filters([


                Tables\Filters\SelectFilter::make('group')
                    ->options(fn () => Luggage::distinct()->pluck('group', 'group')->toArray()),

                Tables\Filters\Filter::make('has_scans')
                    ->query(fn (Builder $query) => $query->whereHas('scans'))
                    ->label('With Scans'),

                Tables\Filters\Filter::make('no_scans')
                    ->query(fn (Builder $query) => $query->whereDoesntHave('scans'))
                    ->label('Without Scans'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->modalContent(fn ($record) => view('filament.resources.luggage.view', ['luggage' => $record])),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLuggage::route('/'),
            'create' => Pages\CreateLuggage::route('/create'),
            'edit' => Pages\EditLuggage::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
