<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanKeuanganResource\Pages;
use App\Filament\Resources\LaporanKeuanganResource\Widgets\LaporanKeuanganChart;
use App\Models\LaporanKeuangan;
use App\Models\Penjualan;
use App\Models\PembelianBahanBaku;
use App\Models\JadwalProduksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Facades\Auth;

class LaporanKeuanganResource extends Resource
{
    protected static ?string $model = LaporanKeuangan::class;
    protected static ?string $modelLabel = 'Financial Report';
    protected static ?string $pluralModelLabel = 'Laporan Keuangan';
    protected static ?string $navigationLabel = 'Laporan Keuangan';
    protected static ?string $navigationGroup = 'Evaluasi & Keuangan';
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Periode Laporan')
                    ->description('Pilih bulan untuk menarik seluruh data transaksi secara otomatis.')
                    ->schema([
                        DatePicker::make('periode_laporan')
                            ->label('Bulan & Tahun')
                            ->format('Y-m-d')
                            ->displayFormat('F Y')
                            ->native(false)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::hitungOtomatis($get, $set);
                            })
                            ->afterStateHydrated(function (Get $get, Set $set) {
                                self::hitungOtomatis($get, $set);
                            }),
                    ]),

                Group::make()
                    ->schema([
                        Hidden::make('id_tim_keuangan')
                            ->default(function () {
                                /** @var \App\Models\User $user */
                                $user = Auth::user();

                                if (!$user) return null;

                                return \App\Models\TimKeuangan::where('email_keuangan', $user->email)->first()?->id;
                            }),
                        Hidden::make('detail_penjualan_count'),
                        Section::make('Arus Kas Masuk (Revenue)')
                            ->icon('heroicon-m-arrow-trending-up')
                            ->schema([
                                Placeholder::make('info_penjualan')
                                    ->label('Sumber: Transaksi Penjualan (Lunas)')
                                    ->content(fn(Get $get) => $get('detail_penjualan_count') . ' Transaksi berhasil'),

                                TextInput::make('total_pendapatan')
                                    ->label('Total Omset')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->readOnly()
                                    ->extraInputAttributes(['class' => 'text-success-600 font-bold text-xl']),
                            ]),

                        Section::make('Arus Kas Keluar (Cost)')
                            ->icon('heroicon-m-arrow-trending-down')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('biaya_material')
                                        ->label('Belanja Bahan')
                                        ->prefix('Rp')
                                        ->numeric()
                                        ->readOnly()
                                        ->live()
                                        ->dehydrated(true),

                                    TextInput::make('biaya_sdm')
                                        ->label('Upah Pengrajin')
                                        ->prefix('Rp')
                                        ->numeric()
                                        ->readOnly()
                                        ->live()
                                        ->dehydrated(true)
                                ]),

                                TextInput::make('total_pengeluaran')
                                    ->label('Total Cost')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->readOnly()
                                    ->dehydrated()
                                    ->extraInputAttributes(['class' => 'text-danger-600 font-bold text-xl']),
                            ]),
                    ])->columns(2),

                Section::make()
                    ->schema([
                        TextInput::make('laba_rugi')
                            ->label('NET PROFIT / LOSS (Laba Bersih)')
                            ->prefix('Rp')
                            ->numeric()
                            ->readOnly()
                            ->dehydrated(true)
                            ->extraInputAttributes([
                                'class' => 'text-3xl font-black text-center',
                                'style' => 'text-align: center',
                            ])
                            ->helperText('Rumus: Total Omset - (Belanja Bahan + Upah Pengrajin)'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('periode_laporan')
                    ->label('Periode')
                    ->date('F Y')
                    ->sortable()
                    ->icon('heroicon-m-calendar'),

                TextColumn::make('total_pendapatan')
                    ->label('Omset')
                    ->money('IDR')
                    ->color('success')
                    ->weight(FontWeight::Bold)
                    ->summarize(Sum::make()->money('IDR')->label('Total Omset')),

                TextColumn::make('total_pengeluaran')
                    ->label('Cost')
                    ->money('IDR')
                    ->color('danger')
                    ->summarize(Sum::make()->money('IDR')->label('Total Cost')),

                TextColumn::make('laba_rugi')
                    ->label('Laba Bersih')
                    ->money('IDR')
                    ->weight(FontWeight::Black)
                    ->size(TextColumn\TextColumnSize::Large)
                    ->color(fn(string $state): string => $state < 0 ? 'danger' : 'success')
                    ->icon(fn(string $state): string => $state < 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up')
                    ->summarize(Sum::make()->money('IDR')->label('Total Laba')),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('periode_laporan', 'desc')
            ->actions([
                EditAction::make()->label('Detail'),
                DeleteAction::make(),
                Action::make('cetak_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->action(function (LaporanKeuangan $record) {
                        $pdf = Pdf::loadView('pdf.laporan-keuangan', ['data' => $record]);
                        return response()->streamDownload(fn() => print($pdf->output()), "Laporan-{$record->periode_laporan->format('M-Y')}.pdf");
                    }),
            ]);
    }

    public static function hitungOtomatis(Get $get, Set $set)
    {
        $dateInput = $get('periode_laporan');

        if (! $dateInput) return;

        $start = Carbon::parse($dateInput)->startOfMonth();
        $end   = Carbon::parse($dateInput)->endOfMonth();

        $pendapatan = Penjualan::whereBetween('tanggal', [$start, $end])
            ->where('status_pembayaran', 'lunas')
            ->sum('total_harga') ?? 0;

        $countTransaksi = Penjualan::whereBetween('tanggal', [$start, $end])
            ->where('status_pembayaran', 'lunas')
            ->count() ?? 0;

        $costBahan = PembelianBahanBaku::whereBetween('tanggal_beli', [$start, $end])
            ->sum('total_biaya') ?? 0;

        $costUpah = JadwalProduksi::whereBetween('tanggal_selesai', [$start, $end])
            ->where('status_produksi', 'selesai')
            ->sum('biaya_tenaga_kerja') ?? 0;

        $totalPengeluaran = $costBahan + $costUpah;
        $labaBersih = $pendapatan - $totalPengeluaran;

        $set('total_pendapatan', $pendapatan);
        $set('detail_penjualan_count', $countTransaksi);
        $set('biaya_material', $costBahan);
        $set('biaya_sdm', $costUpah);
        $set('total_pengeluaran', $totalPengeluaran);
        $set('laba_rugi', $labaBersih);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanKeuangans::route('/'),
            'create' => Pages\CreateLaporanKeuangan::route('/create'),
            'edit' => Pages\EditLaporanKeuangan::route('/{record}/edit'),
        ];
    }
}
