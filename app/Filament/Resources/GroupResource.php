<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupResource\Pages;
use App\Filament\Resources\GroupResource\RelationManagers;
use App\Models\Group;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\GroupResource\Widgets;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationLabel = 'Keloter';
    protected static ?string $modelLabel = 'Keloter';
    protected static ?string $pluralModelLabel = 'Keloter';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Group Tabs')
                ->tabs([
                    Tabs\Tab::make('Informasi Dasar')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make()
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Nama Keloter')
                                                ->required()
                                                ->placeholder('Masukkan nama Keloter')
                                                ->maxLength(255),

                                            TextInput::make('max_capacity')
                                                ->label('Kapasitas Maksimal')
                                                ->numeric()
                                                ->minValue(1)
                                                ->placeholder('Masukkan jumlah maksimal jamaah')
                                                ->suffixIcon('heroicon-o-users'),

                                            DatePicker::make('departure_date')
                                                ->label('Tanggal Keberangkatan')
                                                ->required()
                                                ->placeholder('Pilih tanggal keberangkatan')
                                                ->suffixIcon('heroicon-o-calendar'),

                                            DatePicker::make('return_date')
                                                ->label('Tanggal Kembali')
                                                ->required()
                                                ->placeholder('Pilih tanggal kembali')
                                                ->suffixIcon('heroicon-o-calendar')
                                                ->afterOrEqual('departure_date'),
                                        ]),

                                    Forms\Components\RichEditor::make('description')
                                        ->label('Deskripsi Keloter')
                                        ->placeholder('Masukkan deskripsi Keloter'),
                                ]),
                        ]),

                    Tabs\Tab::make('Itinerary')
                        ->icon('heroicon-o-map')
                        ->schema([
                            Card::make()
                                ->schema([
                                    Forms\Components\KeyValue::make('itinerary')
                                        ->keyLabel('Hari')
                                        ->valueLabel('Kegiatan')
                                        ->reorderable(),
                                ]),
                        ]),

                    Tabs\Tab::make('Jadwal Perjalanan')
                        ->icon('heroicon-o-calendar')
                        ->schema([
                            Section::make()
                                ->schema([
                                    Repeater::make('schedules')
                                        ->relationship()
                                        ->schema([
                                            Card::make()
                                                ->schema([
                                                    Grid::make(2)
                                                        ->schema([
                                                            DatePicker::make('date')
                                                                ->required(),
                                                            TextInput::make('day_title')
                                                                ->required(),
                                                            Textarea::make('description'),
                                                        ]),

                                                    Repeater::make('activities')
                                                        ->relationship()
                                                        ->schema([
                                                            Grid::make(2)
                                                                ->schema([
                                                                    TextInput::make('time')
                                                                        ->type('time')
                                                                        ->required(),
                                                                    TextInput::make('title')
                                                                        ->required(),
                                                                    TextInput::make('location'),
                                                                    Select::make('category')
                                                                        ->options([
                                                                            'ibadah' => 'Ibadah',
                                                                            'makan' => 'Makan',
                                                                            'perjalanan' => 'Perjalanan',
                                                                            'istirahat' => 'Istirahat'
                                                                        ])
                                                                ])
                                                        ])
                                                        ->collapsible(),
                                                ]),
                                        ])
                                        ->collapsible(),
                                ]),
                        ]),
                ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Keloter')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('departure_date')
                    ->label('Tanggal Keberangkatan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pilgrims_count')
                    ->label('Jumlah Jamaah')
                    ->counts('pilgrims'),
                Tables\Columns\TextColumn::make('max_capacity')
                    ->label('Kapasitas Maks'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->attribute('is_active'),
                Tables\Filters\Filter::make('departure_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('calendar')
                    ->icon('heroicon-o-calendar')
                    ->url(function (Group $record): string {
                        return static::getUrl('calendar', ['record' => $record]);
                    })
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
            RelationManagers\PilgrimsRelationManager::class,
            RelationManagers\SchedulesRelationManager::class,
            RelationManagers\TourLeadersRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'view' => Pages\ViewGroup::route('/{record}'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
            'calendar' => Pages\GroupCalendar::route('/{record}/calendar'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Widgets\GroupStatsWidget::class,

        ];
    }
}
