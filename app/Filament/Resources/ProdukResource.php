<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Models\Produk;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;
    protected static ?string $modelLabel = 'Item Katalog';
    protected static ?string $pluralModelLabel = 'Katalog Produk';
    protected static ?string $navigationLabel = 'Katalog & Etalase';
    protected static ?string $navigationGroup = 'Penjualan dan Kasir';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Informasi Produk')
                            ->description('Detail visual dan deskripsi untuk pelanggan.')
                            ->schema([
                                TextInput::make('nama_produk')
                                    ->label('Nama Kerajinan')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->placeholder('Contoh: Vas Bunga Tanah Liat Motif Batik'),

                                TextInput::make('sku')
                                    ->label('SKU (Kode Stok)')
                                    ->placeholder('KRA-001')
                                    ->unique(ignoreRecord: true),

                                RichEditor::make('deskripsi')
                                    ->label('Cerita & Spesifikasi Produk')
                                    ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList', 'h2', 'h3'])
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Visualisasi')
                            ->schema([
                                FileUpload::make('gambar_produk')
                                    ->label('Galeri Foto')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/avif'])
                                    ->multiple()
                                    ->reorderable()
                                    ->directory('produk-images')
                                    ->columnSpanFull()
                                    ->maxSize(5120)
                                    ->helperText('Foto pertama akan menjadi cover utama.'),
                            ]),
                    ])->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Harga & Inventaris')
                            ->icon('heroicon-m-currency-dollar')
                            ->schema([
                                TextInput::make('harga')
                                    ->label('Harga Jual')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->minValue(0),

                                TextInput::make('stok_produk')
                                    ->label('Stok Tersedia')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Update otomatis jika ada penjualan.'),

                                TextInput::make('berat_gram')
                                    ->label('Berat (Gram)')
                                    ->numeric()
                                    ->suffix('gram')
                                    ->default(1000),
                            ]),

                        Section::make('Status Publikasi')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Tampilkan di Website?')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->helperText('Matikan jika produk ditarik/rusak.'),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar_produk')
                    ->label('Foto')
                    ->circular()
                    ->stacked()
                    ->limit(3),

                TextColumn::make('nama_produk')
                    ->label('Produk')
                    ->searchable()
                    ->weight(FontWeight::Bold)
                    ->description(fn(Produk $record): string => $record->sku ?? '-'),

                TextColumn::make('harga')
                    ->money('IDR')
                    ->sortable()
                    ->color('primary'),

                TextColumn::make('stok_produk')
                    ->label('Stok')
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state < 5 => 'warning',
                        default => 'success',
                    })
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('stok_habis')
                    ->label('Stok Habis')
                    ->query(fn($query) => $query->where('stok_produk', '<=', 0)),

                Filter::make('non_aktif')
                    ->label('Draft / Hidden')
                    ->query(fn($query) => $query->where('is_active', false)),
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}
