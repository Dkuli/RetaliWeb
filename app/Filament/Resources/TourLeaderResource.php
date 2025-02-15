<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TourLeaderResource\Pages;
use App\Models\TourLeader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\Action;
use Filament\Support\Enums\ActionSize;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class TourLeaderResource extends Resource
{
    protected static ?string $model = TourLeader::class;

    protected static ?string $navigationGroup = 'Tour Management';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Tour Leader';

    protected static ?string $pluralModelLabel = 'Tour Leaders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->description('Manage the tour leader\'s basic information.')
                    ->icon('heroicon-o-user')
                    ->columns(['sm' => 2])
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Full Name'),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(20),

                        TextInput::make('password')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))

                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->visible(fn (string $context): bool => $context === 'create'),
                    ]),

                Forms\Components\Section::make('Foto Profil')
                ->description('Unggah foto jamaah')
                ->icon('heroicon-o-camera')
                ->collapsible()
                ->schema([
                    FileUpload::make('avatar')
                        ->label('Foto')
                        ->image()
                        ->disk('public')
                        ->directory('avatar')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('1:1')
                        ->imageResizeTargetWidth('300')
                        ->imageResizeTargetHeight('300')
                        ->helperText('Upload foto ukuran 4x6 dengan latar belakang putih')
                ]),

                Forms\Components\Section::make('Status & Group')
                    ->description('Manage access and group assignment.')
                    ->icon('heroicon-o-cog')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active Status')
                            ->default(true)
                            ->helperText('Deactivate to temporarily suspend access.'),

                        Select::make('current_group_id')
                            ->relationship('currentGroup', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                ->label('Foto')
                ->circular(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('phone')
                    ->searchable()
                    ->icon('heroicon-m-phone'),

                TextColumn::make('currentGroup.name')
                    ->label('Current Group')
                    ->sortable()
                    ->badge(),

                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),


            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->indicator('Active Tour Leaders'),

                SelectFilter::make('current_group_id')
                    ->label('Current Group')
                    ->relationship('currentGroup', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('changePassword')
                        ->icon('heroicon-m-key')
                        ->form([
                            TextInput::make('new_password')
                                ->label('New Password')
                                ->password()
                                ->required()
                                ->minLength(8)
                                ->confirmed(),
                            TextInput::make('new_password_confirmation')
                                ->label('Confirm New Password')
                                ->password()
                                ->required(),
                        ])
                        ->action(function (TourLeader $record, array $data): void {
                            $record->update([
                                'password' => Hash::make($data['new_password']),
                            ]);

                            Notification::make()
                                ->success()
                                ->title('Password updated successfully')
                                ->send();
                        }),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activateBulk')
                        ->label('Activate Selected')
                        ->icon('heroicon-m-check-circle')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => true]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivateBulk')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => false]))
                        ->requiresConfirmation(),
                ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }
}
