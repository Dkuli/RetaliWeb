<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TourLeaderResource\Pages;
use App\Filament\Resources\TourLeaderResource\RelationManagers;
use App\Models\TourLeader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;



class TourLeaderResource extends Resource
{
    protected static ?string $model = TourLeader::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('phone'),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                    ])->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active'),
                        Forms\Components\Select::make('current_group_id')
                            ->relationship('currentGroup', 'name'),
                        Forms\Components\DatePicker::make('activation_start'),
                        Forms\Components\DatePicker::make('activation_end'),
                    ])->columns(2),
            ])->columnSpan(['lg' => 2]),

            Forms\Components\Group::make([
                Forms\Components\Section::make('Avatar')
                    ->schema([
                        Forms\Components\FileUpload::make('avatar')
                            ->image()
                            ->directory('tour-leaders')
                    ]),
            ])->columnSpan(['lg' => 1]),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currentGroup.name')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('last_active')
                    ->getStateUsing(fn ($record) => $record->locations()->latest()->first()?->tracked_at)
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('current_group')
                    ->relationship('currentGroup', 'name'),
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LocationsRelationManager::class,
            RelationManagers\NotificationsRelationManager::class,
            RelationManagers\ContentsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTourLeaders::route('/'),
            'create' => Pages\CreateTourLeader::route('/create'),
            'view' => Pages\ViewTourLeader::route('/{record}'),
            'edit' => Pages\EditTourLeader::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            TourLeaderResource\Widgets\TourLeaderStatsWidget::class,
            TourLeaderResource\Widgets\LocationTrackingWidget::class,
        ];
    }
}
