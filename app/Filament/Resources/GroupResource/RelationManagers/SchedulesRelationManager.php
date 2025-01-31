<?php

namespace App\Filament\Resources\GroupResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('date')
                ->required(),
            Forms\Components\TextInput::make('day_title')
                ->required(),
            Forms\Components\Textarea::make('description'),
            Forms\Components\Repeater::make('activities')
                ->relationship()
                ->schema([
                    Forms\Components\TimePicker::make('time')
                        ->required(),
                    Forms\Components\TextInput::make('title')
                        ->required(),
                    Forms\Components\TextInput::make('location'),
                    Forms\Components\Select::make('category')
                        ->options([
                            'prayer' => 'Prayer',
                            'transport' => 'Transport',
                            'meal' => 'Meal',
                            'tour' => 'Tour',
                            'rest' => 'Rest',
                        ]),
                ])
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date(),
                Tables\Columns\TextColumn::make('day_title'),
                Tables\Columns\TextColumn::make('activities_count')
                    ->counts('activities'),
            ])
            ->defaultSort('date');
    }
}
