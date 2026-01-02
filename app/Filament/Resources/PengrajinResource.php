<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengrajinResource\Pages;
use App\Models\Pengrajin;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class PengrajinResource extends Resource
{
    protected static ?string $model = Pengrajin::class;
    protected static ?string $modelLabel = 'Mitra Pengrajin';
    protected static ?string $pluralModelLabel = 'Tim Produksi';
    protected static ?string $navigationLabel = 'Data Pengrajin';
    protected static ?string $navigationGroup = 'Manajemen Produksi';
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Profil Pengrajin')
                            ->schema([
                                TextInput::make('nama_pengrajin')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->autocapitalize('words')
                                    ->prefixIcon('heroicon-m-user-circle'),

                                TextInput::make('email_pengrajin')
                                    ->label('Email (Wajib untuk Akses Login)')
                                    ->email()
                                    ->required()
                                    ->unique('users', 'email')
                                    ->prefixIcon('heroicon-m-envelope'),

                                TextInput::make('password')
                                    ->label('Password Login')
                                    ->password()
                                    ->required(fn(string $context): bool => $context === 'create')
                                    ->visible(fn(string $context): bool => $context === 'create')
                                    ->revealable(),
                            ])->columns(2),

                        Section::make('Alamat & Lokasi')
                            ->schema([
                                Textarea::make('alamat_pengrajin')
                                    ->label('Alamat Domisili')
                                    ->placeholder('Alamat lengkap untuk pengiriman bahan baku...')
                                    ->rows(3)
                                    ->maxLength(500),
                            ]),
                    ])->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Kontak Darurat')
                            ->description('Nomor yang bisa dihubungi untuk koordinasi jadwal.')
                            ->icon('heroicon-m-phone')
                            ->schema([
                                TextInput::make('telepon_pengrajin')
                                    ->label('WhatsApp / Telepon')
                                    ->tel()
                                    ->required()
                                    ->prefixIcon('heroicon-m-device-phone-mobile')
                                    ->helperText('Wajib aktif untuk koordinasi produksi.'),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_pengrajin')
                    ->label('Nama Mitra')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn(Pengrajin $record): string => $record->email_pengrajin ?? '-') // Email jadi subtext
                    ->icon('heroicon-m-user'),

                TextColumn::make('telepon_pengrajin')
                    ->label('Hubungi')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->color('success')
                    ->searchable()
                    ->url(fn(string $state): string => 'https://wa.me/' . preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $state)), true),

                TextColumn::make('alamat_pengrajin')
                    ->label('Domisili')
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Bergabung')
                    ->date('M Y')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
            ])
            ->defaultSort('nama_pengrajin', 'asc')
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->actions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengrajins::route('/'),
            'create' => Pages\CreatePengrajin::route('/create'),
            'edit' => Pages\EditPengrajin::route('/{record}/edit'),
        ];
    }
}
