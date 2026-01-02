<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\Penjualan;
use App\Models\Produk;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;
    protected static ?string $modelLabel = 'Kasir';
    protected static ?string $pluralModelLabel = 'Transaksi Penjualan';
    protected static ?string $navigationLabel = 'Kasir / POS';
    protected static ?string $navigationGroup = 'Penjualan dan Kasir';
    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Kasir / Point of Sale')
                            ->description('Pilih produk dan tentukan jumlah pesanan.')
                            ->icon('heroicon-m-shopping-cart')
                            ->schema([
                                Repeater::make('detailPenjualan')
                                    ->relationship()
                                    ->live()
                                    ->afterStateUpdated(fn(Get $get, Set $set) => self::updateTotals($get, $set))
                                    ->schema([
                                        Grid::make(12)->schema([
                                            Grid::make(6)->schema([
                                                Select::make('id_produk')
                                                    ->label('Produk')
                                                    ->options(Produk::where('stok_produk', '>', 0)->pluck('nama_produk', 'id'))
                                                    ->required()
                                                    ->searchable()
                                                    ->preload()
                                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                                    ->columnSpan(['md' => 4, 'sm' => 8])
                                                    ->reactive()
                                                    ->afterStateUpdated(function ($state, Set $set) {
                                                        $produk = Produk::find($state);
                                                        $set('harga_satuan', $produk?->harga ?? 0);
                                                        $set('sub_total', $produk?->harga ?? 0);
                                                        $set('stok_tersedia', $produk?->stok_produk ?? 0);
                                                        $set('jumlah', 1);
                                                    }),

                                                TextInput::make('jumlah')
                                                    ->label('Qty')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->minValue(1)
                                                    ->required()
                                                    ->columnSpan(['md' => 2, 'sm' => 4])
                                                    ->reactive()
                                                    ->suffix(fn(Get $get) => '/ ' . ($get('stok_tersedia') ?? 0))
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        $qty = (int) $get('jumlah');
                                                        $harga = (int) $get('harga_satuan');
                                                        $set('sub_total', $qty * $harga);
                                                    }),
                                            ]),

                                            Grid::make(6)->schema([
                                                TextInput::make('harga_satuan')
                                                    ->label('Harga')
                                                    ->prefix('Rp')
                                                    ->readOnly()
                                                    ->numeric()
                                                    ->columnSpan(['md' => 3, 'sm' => 4]),

                                                TextInput::make('sub_total')
                                                    ->label('Subtotal')
                                                    ->prefix('Rp')
                                                    ->readOnly()
                                                    ->numeric()
                                                    ->columnSpan(['md' => 3, 'sm' => 4])
                                                    ->extraInputAttributes(['class' => 'font-bold text-primary-600']),
                                            ]),

                                            Hidden::make('stok_tersedia'),
                                        ]),
                                    ])
                                    ->addActionLabel('Tambah Item (F2)')
                                    ->itemLabel(fn(array $state): ?string => Produk::find($state['id_produk'])?->nama_produk ?? null)
                                    ->collapsible()
                                    ->defaultItems(1),
                            ]),
                    ])->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Total & Pembayaran')
                            ->schema([
                                Placeholder::make('total_display')
                                    ->label('GRAND TOTAL')
                                    ->content(fn(Get $get) => 'Rp ' . number_format($get('total_harga') ?? 0, 0, ',', '.'))
                                    ->extraAttributes(['class' => 'text-3xl font-black text-primary-600 text-right']),

                                Hidden::make('total_harga')
                                    ->default(0)
                                    ->live(),

                                TextInput::make('jumlah_bayar')
                                    ->label('Uang Diterima (Cash)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0)
                                    ->required()
                                    ->minValue(fn(Get $get) => $get('total_harga'))
                                    ->validationMessages([
                                        'min' => 'Uang yang diterima kurang dari total belanja.',
                                    ])
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Get $get, Set $set) => self::hitungKembalian($get, $set)),

                                TextInput::make('kembalian')
                                    ->label('Kembalian')
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->numeric()
                                    ->default(0)
                                    ->extraInputAttributes(fn(Get $get) => [
                                        'class' => 'text-xl font-bold ' . ($get('kembalian') < 0 ? 'text-danger-600' : 'text-success-600')
                                    ]),
                            ]),

                        Section::make('Data Transaksi')
                            ->schema([
                                Select::make('id_pelanggan')
                                    ->label('Pelanggan')
                                    ->relationship('pelanggan', 'nama_pelanggan')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->createOptionForm([
                                        TextInput::make('nama_pelanggan')->required(),
                                        TextInput::make('telepon_pelanggan')->tel()->required(),
                                    ])
                                    ->helperText('Kosongkan untuk pelanggan umum (jika diset nullable)'),

                                DatePicker::make('tanggal')
                                    ->default(now())
                                    ->readOnly(),

                                Select::make('id_tim_keuangan')
                                    ->label('Kasir Bertugas')
                                    ->relationship('timKeuangan', 'nama_pegawai')
                                    ->default(Auth::id())
                                    ->dehydrated(),

                                Select::make('status_pembayaran')
                                    ->options([
                                        'lunas' => 'Lunas',
                                        'pending' => 'Pending',
                                        'gagal' => 'Gagal',
                                    ])
                                    ->default('lunas')
                                    ->required(),

                                Select::make('status_verifikasi')
                                    ->options([
                                        'terverifikasi' => 'Terverifikasi',
                                        'belum_terverifikasi' => 'Belum Terverifikasi',
                                        'ditolak' => 'Ditolak',
                                    ])
                                    ->default('belum_terverifikasi')
                                    ->required(),

                                Textarea::make('catatan')
                                    ->label('Catatan Tambahan')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->placeholder('Masukkan catatan tambahan untuk transaksi ini...'),

                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('pelanggan.nama_pelanggan')->searchable(),
                TextColumn::make('timKeuangan.nama_pegawai')->label('Kasir'),
                TextColumn::make('total_harga')->money('IDR')->weight('bold'),
                TextColumn::make('status_pembayaran')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'lunas' => 'success',
                        'pending' => 'warning',
                        'gagal' => 'danger',
                    }),
                TextColumn::make('status_verifikasi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'terverifikasi' => 'success',
                        'belum_terverifikasi' => 'warning',
                        'ditolak' => 'danger',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                EditAction::make(),

                Action::make('cetak_struk')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn(Penjualan $record) => route('cetak.invoice', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ]);
    }

    public static function updateTotals(Get $get, Set $set): void
    {
        $items = $get('detailPenjualan') ?? [];
        $grandTotal = 0;

        foreach ($items as $key => $item) {
            $qty = (int) ($item['jumlah'] ?? 0);
            $harga = (int) ($item['harga_satuan'] ?? 0);

            $subTotal = $qty * $harga;

            $set("detailPenjualan.{$key}.sub_total", $subTotal);

            $grandTotal += $subTotal;
        }

        $set('total_harga', $grandTotal);

        self::hitungKembalian($get, $set);
    }

    public static function hitungKembalian(Get $get, Set $set): void
    {
        $total = (int) $get('total_harga');
        $bayar = (int) $get('jumlah_bayar');

        $kembalian = $bayar - $total;

        $set('kembalian', $kembalian);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status_pembayaran', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status_pembayaran', 'pending')->count() > 10 ? 'danger' : 'warning';
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
