<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BahanBakuResource\Pages;
use App\Models\BahanBaku;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
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

class BahanBakuResource extends Resource
{
    protected static ?string $model = BahanBaku::class;
    protected static ?string $modelLabel = 'Material Kerajinan';
    protected static ?string $pluralModelLabel = 'Inventaris Material';
    protected static ?string $navigationLabel = 'Gudang Material';
    protected static ?string $navigationGroup = 'Manajemen Produksi';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detail Material')
                    ->description('Masukan data bahan mentah yang akan digunakan untuk produksi kerajinan.')
                    ->icon('heroicon-m-clipboard-document-list')
                    ->schema([

                        TextInput::make('nama_bahan')
                            ->label('Nama Material / Bahan')
                            ->placeholder('Misal: Benang Wol Merah, Kayu Jati Grade A')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-m-tag')
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('stok')
                                    ->label('Jumlah Stok Fisik')
                                    ->helperText('Pastikan sesuai dengan hitungan di gudang.')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->prefixIcon('heroicon-m-calculator'),

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
                            ]),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_bahan')
                    ->label('Material')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('primary')->description(
                        fn(BahanBaku $record): string =>
                        $record->updated_at
                            ? 'Update: ' . $record->updated_at->diffForHumans()
                            : '-'
                    ),

                TextColumn::make('stok')
                    ->label('Ketersediaan')
                    ->formatStateUsing(fn($state, BahanBaku $record) => $state . ' ' . $record->satuan)
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state < 10 => 'warning',
                        default => 'success',
                    })
                    ->icon(fn(string $state): string => match (true) {
                        $state <= 0 => 'heroicon-m-x-circle',
                        $state < 10 => 'heroicon-m-exclamation-triangle',
                        default => 'heroicon-m-check-circle',
                    })
                    ->sortable(),

                TextColumn::make('satuan')
                    ->hidden()
                    ->searchable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->emptyStateHeading('Gudang Kosong')
            ->emptyStateDescription('Belum ada material yang terdaftar. Silakan tambah material baru.')
            ->emptyStateIcon('heroicon-o-archive-box-x-mark')
            ->filters([
                Filter::make('stok_menipis')
                    ->label('Hanya Stok Menipis (< 10)')
                    ->query(fn($query) => $query->where('stok', '<', 10))
                    ->toggle(),
            ])
            ->actions([
                EditAction::make()
                    ->tooltip('Ubah Data'),
                DeleteAction::make()
                    ->tooltip('Hapus dari Gudang'),
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
            'index' => Pages\ListBahanBakus::route('/'),
            'create' => Pages\CreateBahanBaku::route('/create'),
            'edit' => Pages\EditBahanBaku::route('/{record}/edit'),
        ];
    }
}
