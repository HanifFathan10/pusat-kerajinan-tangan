<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalProduksiResource\Pages;
use App\Models\JadwalProduksi;
use App\Models\BahanBaku;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class JadwalProduksiResource extends Resource
{
    protected static ?string $model = JadwalProduksi::class;
    protected static ?string $modelLabel = 'Jadwal Kerja';
    protected static ?string $pluralModelLabel = 'Monitoring Produksi';
    protected static ?string $navigationLabel = 'Jadwal Produksi';
    protected static ?string $navigationGroup = 'Manajemen Produksi';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Lingkup Pekerjaan')
                            ->description('Tentukan produk dan penanggung jawab.')
                            ->icon('heroicon-m-clipboard-document-list')
                            ->schema([
                                Select::make('id_produk')
                                    ->label('Produk')
                                    ->relationship('produk', 'nama_produk')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->prefixIcon('heroicon-m-cube')
                                    ->createOptionForm([
                                        TextInput::make('nama_produk')->required(),
                                        TextInput::make('harga')->numeric()->required(),
                                    ]),

                                Select::make('id_pengrajin')
                                    ->label('Mitra Pengrajin')
                                    ->relationship('pengrajin', 'nama_pengrajin')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->prefixIcon('heroicon-m-user')
                                    ->helperText('Pilih pengrajin yang tersedia pada tanggal tersebut.'),

                                TextInput::make('jumlah_target')
                                    ->label('Target Produksi (Pcs)')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required()
                                    ->suffix('Pcs')
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Section::make('Kebutuhan Material')
                            ->description('Tentukan bahan baku yang akan diambil dari gudang.')
                            ->schema([
                                Repeater::make('jadwalBahan')
                                    ->relationship()
                                    ->schema([
                                        Select::make('bahan_baku_id')
                                            ->label('Pilih Bahan')
                                            ->options(BahanBaku::pluck('nama_bahan', 'id'))
                                            ->searchable()
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(
                                                fn($state, Forms\Set $set) =>
                                                $set('sisa_stok_info', BahanBaku::find($state)?->stok ?? 0)
                                            )
                                            ->columnSpan(2),

                                        TextInput::make('jumlah_dipakai')
                                            ->label('Qty')
                                            ->numeric()
                                            ->required()
                                            ->suffix('Unit')
                                            ->columnSpan(1),

                                        Placeholder::make('sisa_stok_info')
                                            ->label('Sisa Stok Gudang')
                                            ->content(fn($state) => $state . ' Unit')
                                            ->columnSpanFull()
                                            ->hidden(fn($get) => $get('bahan_baku_id') == null),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(1)
                                    ->addActionLabel('Tambah Bahan Lain'),
                            ])->columnSpanFull(),

                        Section::make('Instruksi Khusus')
                            ->schema([
                                Textarea::make('catatan')
                                    ->label('Catatan Produksi')
                                    ->placeholder('Contoh: Gunakan lem grade A, packing dengan bubble wrap tebal.')
                                    ->rows(3),
                            ]),
                    ])->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Timeline & Status')
                            ->icon('heroicon-m-clock')
                            ->schema([
                                DatePicker::make('tanggal_mulai')
                                    ->label('Tanggal Mulai')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d M Y')
                                    ->disabled(fn(?JadwalProduksi $record) => $record?->status_produksi === 'selesai'),

                                DatePicker::make('tanggal_selesai')
                                    ->label('Deadline')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d M Y')
                                    ->afterOrEqual('tanggal_mulai')
                                    ->disabled(fn(?JadwalProduksi $record) => $record?->status_produksi === 'selesai'),

                                ToggleButtons::make('prioritas')
                                    ->label('Tingkat Prioritas')
                                    ->options([
                                        'low' => 'Santai (Low)',
                                        'normal' => 'Normal',
                                        'urgent' => 'Mendesak (Urgent)',
                                    ])
                                    ->colors([
                                        'low' => 'gray',
                                        'normal' => 'info',
                                        'urgent' => 'danger',
                                    ])
                                    ->icons([
                                        'low' => 'heroicon-o-check',
                                        'normal' => 'heroicon-o-arrow-right',
                                        'urgent' => 'heroicon-o-exclamation-triangle',
                                    ])
                                    ->default('normal')
                                    ->inline()
                                    ->required(),

                                Select::make('status_produksi')
                                    ->label('Status Saat Ini')
                                    ->options([
                                        'rencana' => '📋 Direncanakan',
                                        'progress' => '⚙️ Sedang Dikerjakan',
                                        'selesai' => '✅ Selesai',
                                        'batal' => '❌ Dibatalkan',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->disabled(fn(?JadwalProduksi $record) => $record?->status_produksi === 'selesai')
                                    ->disableOptionWhen(fn(string $value): bool => $value === 'selesai'),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->with(['produk', 'pengrajin', 'jadwalBahan.bahanBaku']))

            ->columns([
                TextColumn::make('produk.nama_produk')
                    ->label('Item Produksi')
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->description(fn(JadwalProduksi $record): string =>
                    'Target: ' . $record->jumlah_target . ' Pcs | SKU: ' . ($record->produk->sku ?? '-'))
                    ->icon('heroicon-m-cube'),

                TextColumn::make('pengrajin.nama_pengrajin')
                    ->label('Penanggung Jawab')
                    ->icon('heroicon-m-user')
                    ->color('gray')
                    ->searchable(),

                TextColumn::make('tanggal_selesai')
                    ->label('Deadline')
                    ->date('d M Y')
                    ->sortable()
                    ->description(fn(JadwalProduksi $record): string => 'Mulai: ' . $record->tanggal_mulai->format('d/m'))
                    ->icon('heroicon-m-calendar'),

                TextColumn::make('prioritas')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'urgent' => 'danger',
                        'normal' => 'info',
                        'low' => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('status_produksi')
                    ->label('Progress')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'rencana' => 'gray',
                        'progress' => 'warning',
                        'selesai' => 'success',
                        'batal' => 'danger',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'rencana' => 'heroicon-m-clipboard',
                        'progress' => 'heroicon-m-arrow-path',
                        'selesai' => 'heroicon-m-check-badge',
                        'batal' => 'heroicon-m-x-circle',
                    }),
                TextColumn::make('hasil_produksi')
                    ->label('Hasil QC')
                    ->toggleable()
                    ->formatStateUsing(
                        fn(Model $record) =>
                        $record->status_produksi == 'selesai'
                            ? "✅ {$record->hasil_produksi} Total | ⚠️ {$record->jumlah_reject} Rusak"
                            : '-'
                    )
                    ->color(fn(Model $record) => $record->jumlah_reject > 0 ? 'warning' : 'success')
                    ->description(
                        fn(Model $record) =>
                        $record->biaya_tenaga_kerja > 0
                            ? 'Upah: Rp ' . number_format($record->biaya_tenaga_kerja, 0, ',', '.')
                            : null
                    ),
            ])
            ->defaultSort('tanggal_selesai', 'asc')
            ->filters([
                SelectFilter::make('status_produksi')
                    ->options([
                        'rencana' => 'Direncanakan',
                        'progress' => 'Sedang Jalan',
                        'selesai' => 'Selesai',
                    ]),
            ])
            ->actions([
                EditAction::make()->iconButton(),

                Action::make('selesaikan_produksi')
                    ->label('Finalisasi & Upah')
                    ->icon('heroicon-m-banknotes')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Finalisasi Produksi & Gaji')
                    ->modalDescription('Input hasil produksi dan upah yang harus dibayarkan ke pengrajin.')
                    ->modalWidth('2xl')
                    ->form([
                        Section::make('Hasil Produksi')
                            ->schema([
                                TextInput::make('hasil_aktual')
                                    ->label('Total Barang Dibuat')
                                    ->numeric()
                                    ->default(fn(Model $record) => $record->jumlah_target)
                                    ->required()
                                    ->live(),

                                TextInput::make('jumlah_reject')
                                    ->label('Barang Reject (Rusak)')
                                    ->numeric()
                                    ->default(0)
                                    ->live()
                                    ->maxValue(fn(Get $get) => $get('hasil_aktual'))
                                    ->helperText('Barang rusak tidak akan masuk stok jual.'),
                            ])->columns(2),

                        Section::make('Upah Pengrajin (Biaya Tenaga Kerja)')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('tarif_per_pcs')
                                        ->label('Tarif Borongan (Per Pcs)')
                                        ->prefix('Rp')
                                        ->numeric()
                                        ->default(0)
                                        ->live(debounce: 500)
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            $bagus = (int)$get('hasil_aktual') - (int)$get('jumlah_reject');
                                            $tarif = (int)$get('tarif_per_pcs');
                                            $set('biaya_tenaga_kerja', $bagus * $tarif);
                                        })
                                        ->helperText('Isi ini untuk hitung otomatis total upah.'),

                                    TextInput::make('biaya_tenaga_kerja')
                                        ->label('Total Upah (Final)')
                                        ->prefix('Rp')
                                        ->numeric()
                                        ->required()
                                        ->disabled()
                                        ->readOnly()
                                        ->dehydrated()
                                        ->extraInputAttributes(['class' => 'font-bold text-lg text-success-600'])
                                        ->helperText('Nominal ini yang akan masuk ke Laporan Keuangan.'),
                                ]),
                            ]),
                    ])
                    ->action(function (Model $record, array $data) {
                        foreach ($record->jadwalBahan as $item) {
                            if ($item->bahanBaku->stok < $item->jumlah_dipakai) {
                                Notification::make()
                                    ->title('Gagal: Stok ' . $item->bahanBaku->nama_bahan . ' tidak cukup!')
                                    ->danger()
                                    ->send();
                                return;
                            }
                        }

                        foreach ($record->jadwalBahan as $item) {
                            $item->bahanBaku->decrement('stok', $item->jumlah_dipakai);
                        }

                        $barangBagus = $data['hasil_aktual'] - $data['jumlah_reject'];
                        if ($barangBagus > 0) {
                            $record->produk->increment('stok_produk', $barangBagus);
                        }

                        $record->update([
                            'status_produksi'    => 'selesai',
                            'hasil_produksi'     => $data['hasil_aktual'],
                            'jumlah_reject'      => $data['jumlah_reject'],
                            'biaya_tenaga_kerja' => $data['biaya_tenaga_kerja'],
                            'tanggal_selesai'    => now(),
                        ]);

                        Notification::make()
                            ->title('Produksi Selesai')
                            ->body("Stok +$barangBagus. Upah Rp " . number_format($data['biaya_tenaga_kerja']) . " tercatat.")
                            ->success()
                            ->send();
                    })
                    ->visible(function (Model $record) {
                        /** @var \App\Models\User $user */
                        $user = Auth::user();

                        return $record->status_produksi !== 'selesai' &&
                            $user->hasAnyRole(['Administrator', 'Pusat Pengelola']);
                    }),

                Action::make('revisi_qc')
                    ->label('Revisi QC')
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->modalWidth('xl')
                    ->form([
                        Section::make('Hasil Produksi')
                            ->schema([
                                TextInput::make('hasil_aktual')
                                    ->label('Total Barang Dibuat')
                                    ->numeric()
                                    ->default(fn(Model $record) => $record->jumlah_target)
                                    ->required()
                                    ->live(),

                                TextInput::make('jumlah_reject')
                                    ->label('Barang Reject (Rusak)')
                                    ->numeric()
                                    ->default(0)
                                    ->live()
                                    ->maxValue(fn(Get $get) => $get('hasil_aktual'))
                                    ->helperText('Barang rusak tidak akan masuk stok jual.'),
                            ])->columns(2),

                        Section::make('Upah Pengrajin (Biaya Tenaga Kerja)')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('tarif_per_pcs')
                                        ->label('Tarif Borongan (Per Pcs)')
                                        ->prefix('Rp')
                                        ->numeric()
                                        ->default(0)
                                        ->live(debounce: 500)
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            $bagus = (int)$get('hasil_aktual') - (int)$get('jumlah_reject');
                                            $tarif = (int)$get('tarif_per_pcs');
                                            $set('biaya_tenaga_kerja', $bagus * $tarif);
                                        })
                                        ->helperText('Isi ini untuk hitung otomatis total upah.'),

                                    TextInput::make('biaya_tenaga_kerja')
                                        ->label('Total Upah (Final)')
                                        ->prefix('Rp')
                                        ->numeric()
                                        ->required()
                                        ->disabled()
                                        ->extraInputAttributes(['class' => 'font-bold text-lg text-success-600'])
                                        ->helperText('Nominal ini yang akan masuk ke Laporan Keuangan.')
                                        ->readOnly()
                                        ->dehydrated(),
                                ]),
                            ]),
                    ])
                    ->action(function (Model $record, array $data) {
                        $barangBagusLama = (int)$record->hasil_produksi - (int)$record->jumlah_reject;

                        $barangBagusBaru = (int)$data['hasil_aktual'] - (int)$data['jumlah_reject'];

                        $selisih = $barangBagusBaru - $barangBagusLama;

                        if ($selisih != 0) {
                            $record->produk->increment('stok_produk', $selisih);
                        }

                        $record->update([
                            'hasil_produksi'     => $data['hasil_aktual'],
                            'jumlah_reject'      => $data['jumlah_reject'],
                            'biaya_tenaga_kerja' => $data['biaya_tenaga_kerja'],
                        ]);

                        Notification::make()
                            ->title('QC Berhasil Direvisi')
                            ->body("Stok produk telah disesuaikan sebesar $selisih unit.")
                            ->success()
                            ->send();
                    })
                    ->visible(function (Model $record) {
                        /** @var \App\Models\User $user */
                        $user = Auth::user();

                        return $record->status_produksi == 'selesai' &&
                            $user->hasAnyRole(['Administrator', 'Pusat Pengelola']);
                    }),
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
            'index' => Pages\ListJadwalProduksis::route('/'),
            'create' => Pages\CreateJadwalProduksi::route('/create'),
            'edit' => Pages\EditJadwalProduksi::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = parent::getEloquentQuery();

        if ($user->hasRole('Pekerja')) {
            return $query->whereHas('pengrajin', fn($q) => $q->where('email_pengrajin', $user->email));
        }

        return $query;
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        if ($user->role === 'Pekerja') {
            return static::getModel()::where('status_produksi', 'progress')
                ->whereHas('pengrajin', fn($q) => $q->where('email_pengrajin', $user->email))
                ->count() ?: null;
        }
        return null;
    }
}
