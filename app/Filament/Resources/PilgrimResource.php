<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PilgrimResource\Pages;
use App\Models\Pilgrim;
use App\Models\Group;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Grid as InfoGrid;

class PilgrimResource extends Resource
{
    protected static ?string $model = Pilgrim::class;
    protected static ?string $navigationLabel = 'Jamaah';
    protected static ?string $modelLabel = 'Jamaah';
    protected static ?string $pluralModelLabel = 'Jamaah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Pribadi')
                    ->description('Informasi dasar jamaah')
                    ->icon('heroicon-o-user')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nama lengkap')
                                    ->suffixIcon('heroicon-o-user'),

                                TextInput::make('phone')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->required()
                                    ->placeholder('Contoh: 08123456789')
                                    ->suffixIcon('heroicon-o-phone'),

                                Select::make('gender')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'male' => 'Laki-laki',
                                        'female' => 'Perempuan'
                                    ])
                                    ->required()
                                    ->suffixIcon('heroicon-o-users'),

                                Select::make('groups')
                                    ->label('Keloter')
                                    ->multiple(false)
                                    ->relationship('group', 'name')
                                    ->createOptionForm([
                                        // form fields for creating new group if needed
                                    ])
                                    ->preload()
                                    ->required()
                                    ->suffixIcon('heroicon-o-user-group'),
                            ]),
                    ]),

                Grid::make(2)
                    ->schema([
                        Section::make('Informasi Kesehatan')
                            ->description('Catatan kesehatan jamaah')
                            ->icon('heroicon-o-heart')
                            ->collapsible()
                            ->schema([
                                Textarea::make('health_notes')
                                    ->label('Catatan Kesehatan')
                                    ->placeholder('Masukkan riwayat penyakit, alergi, atau kebutuhan khusus')
                                    ->rows(3)
                            ]),

                        Section::make('Foto Profil')
                            ->description('Unggah foto jamaah')
                            ->icon('heroicon-o-camera')
                            ->collapsible()
                            ->schema([
                                FileUpload::make('photo')
                                    ->label('Foto')
                                    ->image()
                                    ->disk('public')
                                    ->directory('jamaah-photos')
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('1:1')
                                    ->imageResizeTargetWidth('300')
                                    ->imageResizeTargetHeight('300')
                                    ->helperText('Upload foto ukuran 4x6 dengan latar belakang putih')
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular(),

                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('group.name')
                    ->label('Keloter')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'male' => 'info',
                        'female' => 'success',
                    }),

                TextColumn::make('phone')
                    ->label('Nomor Telepon')
                    ->icon('heroicon-m-phone')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->relationship('group', 'name')
                    ->label('Keloter')
                    ->searchable(),

                SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan'
                    ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('Kartu Identitas Jamaah')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        InfoGrid::make()
                            ->schema([
                                // Kartu Utama
                                InfoSection::make('Kartu Jamaah')
                                    ->schema([
                                        InfoGrid::make(3)
                                            ->schema([
                                                ImageEntry::make('photo')
                                                    ->label('')
                                                    ->circular()
                                                    ->width(120)
                                                    ->height(120)
                                                    ->defaultImageUrl(asset('images/default-avatar.png')),

                                                InfoGrid::make(1)
                                                    ->columnSpan(2)
                                                    ->schema([
                                                        TextEntry::make('name')
                                                            ->label('Nama Lengkap')
                                                            ->weight('bold')
                                                            ->size('lg')
                                                            ->columnSpanFull(),

                                                        TextEntry::make('group.name')
                                                            ->label('Keloter')
                                                            ->badge()
                                                            ->color('success')
                                                            ->columnSpanFull(),

                                                        TextEntry::make('phone')
                                                            ->label('Nomor Telepon')
                                                            ->icon('heroicon-m-phone')
                                                            ->columnSpanFull(),
                                                    ]),
                                            ]),
                                    ])
                                    ->extraAttributes([
                                        'class' => 'border rounded-xl p-6 bg-white shadow',
                                    ]),

                                // Informasi Tambahan
                                InfoSection::make('Informasi Detail')
                                    ->schema([
                                        InfoGrid::make(2)
                                            ->schema([
                                                TextEntry::make('gender')
                                                    ->label('Jenis Kelamin')
                                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                                        'male' => 'Laki-laki',
                                                        'female' => 'Perempuan',
                                                    })
                                                    ->icon('heroicon-m-user'),

                                                TextEntry::make('health_notes')
                                                    ->label('Catatan Kesehatan')
                                                    ->icon('heroicon-m-heart')
                                                    ->markdown(),

                                                TextEntry::make('group.departure_date')
                                                    ->label('Tanggal Keberangkatan')
                                                    ->date('d F Y')
                                                    ->icon('heroicon-m-calendar'),

                                                TextEntry::make('group.return_date')
                                                    ->label('Tanggal Kepulangan')
                                                    ->date('d F Y')
                                                    ->icon('heroicon-m-calendar'),
                                            ]),
                                    ])
                                    ->collapsible()
                                    ->extraAttributes([
                                        'class' => 'border rounded-xl p-6 bg-white shadow',
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPilgrims::route('/'),
            'create' => Pages\CreatePilgrim::route('/create'),
            'edit' => Pages\EditPilgrim::route('/{record}/edit'),
            'view' => Pages\ViewPilgrim::route('/{record}'),
        ];
    }
}
