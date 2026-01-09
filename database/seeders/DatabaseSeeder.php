<?php

namespace Database\Seeders;

use App\Models\{Produk, Pelanggan, Pengrajin, TimKeuangan, BahanBaku, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Hash, Storage, Log};
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $this->call(RoleAndUserSeeder::class);

            $subFolder = 'produk-images';

            if (Storage::disk('public')->exists($subFolder)) {
                Storage::disk('public')->deleteDirectory($subFolder);
            }

            Storage::disk('public')->makeDirectory($subFolder);

            $timKeuangan = [
                [
                    'nik_karyawan' => 'FIN-001',
                    'nama_pegawai' => 'Adinda Kasir',
                    'email_keuangan' => 'adinda@pkt.com',
                    'jabatan' => 'Kasir',
                    'telepon' => '081299990001'
                ],
                [
                    'nik_karyawan' => 'FIN-002',
                    'nama_pegawai' => 'Budi Manager',
                    'email_keuangan' => 'budi@pkt.com',
                    'jabatan' => 'Manager Keuangan',
                    'telepon' => '081299990002'
                ],
            ];
            foreach ($timKeuangan as $tk) {
                DB::table('tim_keuangan')->updateOrInsert(
                    ['nik_karyawan' => $tk['nik_karyawan']],
                    array_merge($tk, ['is_active' => true, 'created_at' => now(), 'updated_at' => now()])
                );
            }

            $pelanggan = [
                ['nama_pelanggan' => 'Pelanggan Umum (Guest)', 'email_pelanggan' => 'pelanggan@pkt.com', 'telepon_pelanggan' => '0000000000', 'alamat_pelanggan' => 'Jl. Merdeka No. 1, Jakarta'],
                ['nama_pelanggan' => 'Rina Shopaholic', 'email_pelanggan' => 'rina@gmail.com', 'telepon_pelanggan' => '085712345678', 'alamat_pelanggan' => 'Jl. Dago Atas No. 12, Bandung'],
                ['nama_pelanggan' => 'PT. Suka Makmur (Corporate)', 'email_pelanggan' => 'procurement@sukamakmur.com', 'telepon_pelanggan' => '0229876543', 'alamat_pelanggan' => 'Kawasan Industri Cimahi'],
            ];
            foreach ($pelanggan as $p) {
                DB::table('pelanggan')->updateOrInsert(['email_pelanggan' => $p['email_pelanggan']], array_merge($p, ['created_at' => now(), 'updated_at' => now()]));
            }

            $pengrajin = [
                ['nama_pengrajin' => 'Mang Ujang (Ahli Kayu)', 'email_pengrajin' => 'ujang@pkt.com', 'telepon_pengrajin' => '081311112222', 'alamat_pengrajin' => 'Desa Jelekong, Kab. Bandung'],
                ['nama_pengrajin' => 'Teh Lilis (Ahli Jahit)', 'email_pengrajin' => 'lilis@pkt.com', 'telepon_pengrajin' => '081333334444', 'alamat_pengrajin' => 'Cibaduyut, Kota Bandung'],
                ['nama_pengrajin' => 'Pak Lukman (Anyaman)', 'email_pengrajin' => 'lukman@pkt.com', 'telepon_pengrajin' => '081355556666', 'alamat_pengrajin' => 'Rajapolah, Tasikmalaya'],
            ];
            foreach ($pengrajin as $pj) {
                DB::table('pengrajin')->updateOrInsert(['telepon_pengrajin' => $pj['telepon_pengrajin']], array_merge($pj, ['created_at' => now(), 'updated_at' => now()]));
            }

            $rolePekerja = Role::findByName('Pekerja', 'web');

            foreach ($pengrajin as $pj) {
                $user = User::create(['name' => $pj['nama_pengrajin'], 'email' => $pj['email_pengrajin'], 'password' => Hash::make('password123')]);
                $user->assignRole($rolePekerja);
            }

            $bahanBaku = [
                ['nama_bahan' => 'Kayu Jati Grade A', 'stok' => 50, 'satuan' => 'Batang'],
                ['nama_bahan' => 'Kulit Sapi Asli (Coklat)', 'stok' => 100, 'satuan' => 'Meter'],
                ['nama_bahan' => 'Rotan Sintetis', 'stok' => 500, 'satuan' => 'Kg'],
                ['nama_bahan' => 'Benang Nilon Hitam', 'stok' => 200, 'satuan' => 'Roll'],
                ['nama_bahan' => 'Resleting YKK 20cm', 'stok' => 1000, 'satuan' => 'Pcs'],
                ['nama_bahan' => 'Pernis Kayu Clear Gloss', 'stok' => 20, 'satuan' => 'Kaleng'],
                ['nama_bahan' => 'Kain Batik Perca', 'stok' => 150, 'satuan' => 'Lembar'],
            ];
            foreach ($bahanBaku as $bb) {
                DB::table('bahan_baku')->updateOrInsert(['nama_bahan' => $bb['nama_bahan']], array_merge($bb, ['created_at' => now(), 'updated_at' => now()]));
            }

            $produkData = [
                ['nama' => 'Tas Selempang Kulit Etnik', 'sku' => 'TAS-KLT-001', 'berat' => 500, 'harga' => 350000, 'stok' => 15],
                ['nama' => 'Vas Bunga Ukir Kayu Jati', 'sku' => 'VAS-KYU-002', 'berat' => 800, 'harga' => 125000, 'stok' => 8],
                ['nama' => 'Kursi Teras Rotan', 'sku' => 'KUR-RTN-003', 'berat' => 3000, 'harga' => 450000, 'stok' => 5],
                ['nama' => 'Dompet Kartu Batik', 'sku' => 'DOM-BTK-004', 'berat' => 500, 'harga' => 35000, 'stok' => 100],
                ['nama' => 'Produk Sample Kosong', 'sku' => 'SMP-000', 'berat' => 100, 'harga' => 10000, 'stok' => 0],
            ];

            foreach ($produkData as $data) {
                $fileName = 'produk-' . Str::slug($data['nama']) . '.png';
                $savePath = $subFolder . '/' . $fileName;

                try {
                    $ctx = stream_context_create(["http" => ["header" => "User-Agent: Mozilla/5.0\r\n"]]);
                    $imageUrl = "https://placehold.jp/40/c5a358/ffffff/600x800.png?text=" . urlencode($data['nama']);
                    $imageContent = file_get_contents($imageUrl, false, $ctx);

                    if ($imageContent) {
                        Storage::disk('public')->put($savePath, $imageContent);
                    }
                } catch (\Exception $e) {
                    Log::warning("Gagal download gambar untuk {$data['sku']}: " . $e->getMessage());
                }

                Produk::updateOrCreate(
                    ['sku' => $data['sku']],
                    [
                        'nama_produk' => $data['nama'],
                        'deskripsi'   => "Kerajinan tangan eksklusif {$data['nama']} buatan artisan lokal dengan material premium.",
                        'harga'       => $data['harga'],
                        'stok_produk' => $data['stok'],
                        'berat_gram'  => $data['berat'],
                        'gambar_produk' => [$savePath],
                        'is_active'   => true,
                        'created_at'  => now(),
                    ]
                );
            }

            $firstPengrajin = DB::table('pengrajin')->first();
            $firstProduk = DB::table('produk')->first();

            if ($firstPengrajin && $firstProduk) {
                DB::table('jadwal_produksi')->updateOrInsert(
                    ['id' => 1],
                    [
                        'tanggal_mulai' => '2025-12-15',
                        'tanggal_selesai' => '2025-12-19',
                        'status_produksi' => 'selesai',
                        'biaya_tenaga_kerja' => 50000.00,
                        'hasil_produksi' => 10,
                        'jumlah_reject' => 0,
                        'jumlah_target' => 10,
                        'prioritas' => 'normal',
                        'catatan' => 'Produksi berjalan lancar tanpa kendala.',
                        'id_pengrajin' => $firstPengrajin->id,
                        'id_produk' => $firstProduk->id,
                        'created_at' => now(),
                    ]
                );
            }
        });

        $this->command->info('🎉 SELESAI! Data dummy dari SQL dan Seeder berhasil disinkronisasi tanpa data NULL.');
    }
}
