<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganResource\Pages;
use App\Models\Pelanggan;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;
    protected static ?string $modelLabel = 'Pembeli';
    protected static ?string $pluralModelLabel = 'Database Pembeli';
    protected static ?string $navigationLabel = 'Data Pembeli';
    protected static ?string $navigationGroup = 'Front Office';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identitas Pembeli')
                    ->description('Masukan data pelanggan baru untuk keperluan transaksi.')
                    ->icon('heroicon-m-identification')
                    ->schema([
                        Split::make([
                            Section::make()
                                ->schema([
                                    TextInput::make('nama_pelanggan')
                                        ->label('Nama Lengkap')
                                        ->placeholder('Nama sesuai panggilan')
                                        ->required()
                                        ->maxLength(255)
                                        ->autocapitalize('words')
                                        ->prefixIcon('heroicon-m-user'),

                                    TextInput::make('telepon_pelanggan')
                                        ->label('WhatsApp / HP')
                                        ->placeholder('0812xxxx')
                                        ->tel()
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->validationAttribute('Nomor Telepon')
                                        ->maxLength(20)
                                        ->prefixIcon('heroicon-m-phone')
                                        ->helperText('Nomor ini akan digunakan untuk notifikasi pesanan.'),
                                ]),

                            Section::make()
                                ->schema([
                                    TextInput::make('email_pelanggan')
                                        ->label('Email (Opsional)')
                                        ->placeholder('user@email.com')
                                        ->email()
                                        ->prefixIcon('heroicon-m-at-symbol'),

                                    Textarea::make('alamat_pelanggan')
                                        ->label('Alamat Domisili')
                                        ->placeholder('Alamat singkat atau patokan...')
                                        ->rows(3)
                                        ->maxLength(500),
                                ])->grow(false),
                        ])->from('md'),
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_pelanggan')
                    ->label('Nama Pembeli')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('primary'),

                TextColumn::make('telepon_pelanggan')
                    ->label('Kontak')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Nomor telepon disalin!')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->url(fn(string $state): string => 'https://wa.me/' . preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $state)), true)
                    ->color('success'),

                TextColumn::make('alamat_pelanggan')
                    ->label('Domisili')
                    ->limit(30)
                    ->tooltip(fn(TextColumn $column): ?string => $column->getState()),

                TextColumn::make('created_at')
                    ->label('Member Sejak')
                    ->date('d M Y')
                    ->sortable()
                    ->size(TextColumn\TextColumnSize::Small)
                    ->color('gray'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('no_email')
                    ->label('Tanpa Email')
                    ->query(fn(Builder $query) => $query->whereNull('email_pelanggan')),
            ])
            ->actions([
                EditAction::make()
                    ->iconButton(),
                DeleteAction::make()
                    ->iconButton(),
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
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}
