<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimKeuanganResource\Pages;
use App\Models\TimKeuangan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group as ComponentsGroup;
use Filament\Forms\Components\Section as ComponentsSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class TimKeuanganResource extends Resource
{
    protected static ?string $model = TimKeuangan::class;
    protected static ?string $modelLabel = 'Staff Keuangan';
    protected static ?string $pluralModelLabel = 'Tim Finance';
    protected static ?string $navigationLabel = 'Staff Keuangan';
    protected static ?string $navigationGroup = 'Manajemen Internal';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ComponentsGroup::make()
                    ->schema([
                        ComponentsSection::make('Profil Pegawai')
                            ->description('Informasi jabatan dan identitas karyawan.')
                            ->icon('heroicon-m-identification')
                            ->schema([
                                TextInput::make('nik_karyawan')
                                    ->label('NIK (Nomor Induk)')
                                    ->placeholder('FIN-2025-001')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(20),

                                TextInput::make('nama_pegawai')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255)
                                    ->autocapitalize('words'),

                                Select::make('jabatan')
                                    ->label('Posisi / Jabatan')
                                    ->options([
                                        'Manager Keuangan' => 'Manager Keuangan',
                                        'Accounting' => 'Accounting',
                                        'Kasir' => 'Kasir / Front Desk',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->prefixIcon('heroicon-m-briefcase'),
                            ]),
                    ])->columnSpan(['lg' => 2]),

                ComponentsGroup::make()
                    ->schema([
                        ComponentsSection::make('Akses & Kontak')
                            ->schema([
                                FileUpload::make('foto_profil')
                                    ->label('Foto ID')
                                    ->image()
                                    ->avatar()
                                    ->directory('foto-staff')
                                    ->imageEditor()
                                    ->circleCropper(),

                                TextInput::make('email_keuangan')
                                    ->label('Email Kantor')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->prefixIcon('heroicon-m-at-symbol'),

                                TextInput::make('telepon')
                                    ->label('WhatsApp Aktif')
                                    ->tel()
                                    ->prefixIcon('heroicon-m-phone'),

                                Toggle::make('is_active')
                                    ->label('Status Aktif')
                                    ->default(true)
                                    ->helperText('Matikan jika pegawai resign.'),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_profil')
                    ->label('foto profil')
                    ->circular()
                    ->defaultImageUrl(url('image/logo-pkt.png')),

                TextColumn::make('nama_pegawai')
                    ->label('Pegawai')
                    ->searchable()
                    ->weight(FontWeight::Bold)
                    ->description(fn(TimKeuangan $record): string => $record->jabatan ?? 'Staff')
                    ->icon('heroicon-m-user'),

                TextColumn::make('email_keuangan')
                    ->label('Kontak')
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage('Email disalin')
                    ->color('gray'),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Aktif' : 'Non-Aktif')
                    ->color(fn(bool $state): string => $state ? 'success' : 'danger'),
            ])
            ->defaultSort('nama_pegawai', 'asc')
            ->filters([
                SelectFilter::make('jabatan')
                    ->options([
                        'Manager Keuangan' => 'Manager Keuangan',
                        'Kasir' => 'Kasir',
                        'Accounting' => 'Accounting',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Status Kepegawaian'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListTimKeuangans::route('/'),
            'create' => Pages\CreateTimKeuangan::route('/create'),
            'edit' => Pages\EditTimKeuangan::route('/{record}/edit'),
        ];
    }
}
