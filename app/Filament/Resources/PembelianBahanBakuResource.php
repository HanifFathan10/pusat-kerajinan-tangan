<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianBahanBakuResource\Pages;
use App\Models\PembelianBahanBaku;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\DB;

class PembelianBahanBakuResource extends Resource
{
    protected static ?string $model = PembelianBahanBaku::class;
    protected static ?string $navigationLabel = 'Belanja Bahan';
    protected static ?string $modelLabel = 'Pembelian Material';
    protected static ?string $navigationGroup = 'Inventaris Gudang';
    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Info Supplier')
                    ->schema([
                        TextInput::make('supplier')
                            ->label('Nama Toko / Supplier')
                            ->placeholder('Contoh: TB. Maju Jaya')
                            ->required(),
                        DatePicker::make('tanggal_beli')
                            ->default(now())
                            ->required(),
                    ])->columns(2),

                Section::make('Item Belanja')
                    ->description('Daftar material yang dibeli dari supplier.')
                    ->disabled(fn(?PembelianBahanBaku $record) => $record?->status === 'diterima')
                    ->schema([
                        Repeater::make('detailPembelian')
                            ->relationship()
                            ->live()
                            ->columns(5)
                            ->afterStateUpdated(fn(Get $get, Set $set) => self::updateGrandTotal($get, $set))
                            ->schema([
                                Select::make('bahan_baku_id')
                                    ->label('Bahan Baku')
                                    ->relationship('bahanBaku', 'nama_bahan')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(2)

                                    ->createOptionForm([
                                        TextInput::make('nama_bahan')
                                            ->label('Nama Material / Bahan Baru')
                                            ->placeholder('Misal: Benang Wol Merah, Kayu Jati Grade A')
                                            ->required()
                                            ->maxLength(255)
                                            ->prefixIcon('heroicon-m-tag')
                                            ->columnSpanFull(),

                                        TextInput::make('satuan')
                                            ->label('Satuan Ukur')
                                            ->placeholder('Pcs / Kg / Meter')
                                            ->required()
                                            ->datalist([
                                                'Pcs',
                                                'Lembar',
                                                'Meter',
                                                'Kg',
                                                'Liter',
                                                'Roll'
                                            ])
                                            ->prefixIcon('heroicon-m-scale'),

                                        Hidden::make('stok')
                                            ->default(0),
                                    ])
                                    ->createOptionUsing(function (array $data) {
                                        return \App\Models\BahanBaku::create($data)->id;
                                    }),

                                TextInput::make('jumlah_beli')
                                    ->label('Qty Masuk')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        self::updateItemSubtotal($get, $set);
                                        self::updateGrandTotal($get, $set);
                                    }),

                                TextInput::make('harga_satuan')
                                    ->label('Harga Beli (@)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->reactive()
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        self::updateItemSubtotal($get, $set);
                                        self::updateGrandTotal($get, $set);
                                    }),

                                TextInput::make('sub_total')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->dehydrated()
                            ]),
                    ]),

                TextInput::make('total_biaya')
                    ->label('Total Pengeluaran')
                    ->prefix('Rp')
                    ->readOnly()
                    ->numeric()
                    ->extraInputAttributes(['class' => 'font-bold text-lg text-primary-600']),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_beli')->date('d M Y')->sortable(),
                TextColumn::make('supplier')->searchable(),
                TextColumn::make('total_biaya')->money('IDR')->weight('bold'),
                TextColumn::make('created_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tanggal_beli', 'desc')
            ->actions([
                EditAction::make(),
                Action::make('terima_barang')
                    ->label('Barang Masuk Gudang')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('success')
                    ->requiresConfirmation()
                    ->hidden(fn(PembelianBahanBaku $record) => str_contains($record->supplier, '(DITERIMA)'))
                    ->action(function (PembelianBahanBaku $record) {
                        DB::transaction(function () use ($record) {
                            foreach ($record->detailPembelian as $detail) {
                                $detail->bahanBaku->increment('stok', $detail->jumlah_beli);
                            }

                            $record->update([
                                'supplier' => $record->supplier . ' (DITERIMA)'
                            ]);
                        });

                        Notification::make()
                            ->title('Stok Berhasil Masuk')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function updateItemSubtotal(Get $get, Set $set)
    {
        $qty = (float) ($get('jumlah_beli') ?? 0);
        $harga = (float) ($get('harga_satuan') ?? 0);

        $set('sub_total', $qty * $harga);
    }

    public static function updateGrandTotal(Get $get, Set $set)
    {
        $items = $get('detailPembelian') ?? [];
        $total = 0;

        foreach ($items as $item) {
            $qty = (float) ($item['jumlah_beli'] ?? 0);
            $harga = (float) ($item['harga_satuan'] ?? 0);
            $total += ($qty * $harga);
        }

        $set('total_biaya', $total);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelianBahanBakus::route('/'),
            'create' => Pages\CreatePembelianBahanBaku::route('/create'),
            'edit' => Pages\EditPembelianBahanBaku::route('/{record}/edit'),
        ];
    }
}
