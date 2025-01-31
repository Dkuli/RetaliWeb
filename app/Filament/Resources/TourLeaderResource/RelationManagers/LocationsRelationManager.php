<?php

namespace App\Filament\Resources\TourLeaderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'locations';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('latitude'),
                Tables\Columns\TextColumn::make('longitude'),
                Tables\Columns\TextColumn::make('accuracy'),
                Tables\Columns\TextColumn::make('speed'),
                Tables\Columns\TextColumn::make('tracked_at')
                    ->dateTime()
                    ->sortable()
            ])
            ->defaultSort('tracked_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('today')
                    ->query(fn ($query) => $query->whereDate('tracked_at', today())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}

