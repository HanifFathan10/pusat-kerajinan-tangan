<x-filament-panels::page>
    <div class="max-w-5xl mx-auto space-y-8 py-4 text-stone-900 dark:text-stone-200">

        <header class="border-b border-stone-200 dark:border-stone-700 pb-8">
            <h1 class="text-3xl font-light tracking-tight uppercase dark:text-amber-500">
                Prosedur Operasi Standar
            </h1>
            <p class="mt-3 text-stone-500 dark:text-stone-400 max-w-3xl text-sm leading-relaxed">
                Panduan resmi sistem manajemen produksi. Setiap pengrajin wajib mengikuti alur ini untuk menjamin
                akurasi stok dan transparansi upah.
            </p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <div class="md:col-span-4 space-y-8">
                <section>
                    <h2 class="text-[10px] font-bold tracking-[0.2em] text-stone-400 dark:text-stone-500 uppercase mb-4">
                        01 / Persiapan
                    </h2>
                    <div class="space-y-2">
                        <p class="text-sm font-bold italic underline decoration-amber-500">Manajemen Material</p>
                        <p class="text-xs text-stone-600 dark:text-stone-400 leading-loose">
                            Bahan baku wajib diambil sesuai rincian <span class="font-bold">Jadwal Produksi</span>.
                            Segala selisih fisik harus dilaporkan sebelum mulai.
                        </p>
                    </div>
                </section>

                <section class="pt-8 border-t border-stone-100 dark:border-stone-800">
                    <h2
                        class="text-[10px] font-bold tracking-[0.2em] text-stone-400 dark:text-stone-500 uppercase mb-4">
                        02 / Pelaporan
                    </h2>
                    <div class="space-y-2">
                        <p class="text-sm font-bold italic underline decoration-amber-500">Finalisasi Tugas</p>
                        <p class="text-xs text-stone-600 dark:text-stone-400 leading-loose">
                            Input <span class="font-medium italic">Hasil Aktual</span> segera setelah fisik selesai
                            menggunakan tombol di dashboard.
                        </p>
                    </div>
                </section>
            </div>

            <div
                class="md:col-span-8 bg-stone-50 dark:bg-stone-900/40 p-6 rounded-xl border border-stone-100 dark:border-stone-800">
                <h2 class="text-[10px] font-bold tracking-[0.2em] text-stone-400 dark:text-stone-500 uppercase mb-6">
                    Standar Kualitas Produk (QC)
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-xs leading-relaxed">
                    <div class="space-y-2">
                        <h3 class="font-bold text-stone-900 dark:text-stone-100">Konstruksi Fisik</h3>
                        <p class="text-stone-600 dark:text-stone-400">Sambungan harus presisi dan simetris. Kekuatan
                            struktur adalah prioritas utama.</p>
                    </div>
                    <div class="space-y-2">
                        <h3 class="font-bold text-stone-900 dark:text-stone-100">Penyelesaian Akhir</h3>
                        <p class="text-stone-600 dark:text-stone-400">Permukaan halus, finishing rata tanpa tetesan cat
                            atau plitur yang menggumpal.</p>
                    </div>
                    <div class="space-y-2">
                        <h3 class="font-bold text-stone-900 dark:text-stone-100">Konsistensi Warna</h3>
                        <p class="text-stone-600 dark:text-stone-400">Warna identik dengan sampel referensi tanpa
                            variasi mencolok antar bagian.</p>
                    </div>
                    <div class="space-y-2">
                        <h3 class="font-bold text-stone-900 dark:text-stone-100">Kondisi Bersih</h3>
                        <p class="text-stone-600 dark:text-stone-400">Produk bebas sisa lem, debu, atau noda sidik jari
                            sebelum diserahkan.</p>
                    </div>
                </div>
            </div>
        </div>

        <section class="pt-8 border-t border-stone-200 dark:border-stone-700">
            <h2 class="text-[10px] font-bold tracking-[0.2em] text-stone-400 dark:text-stone-500 uppercase mb-6">
                Langkah Digital: Penyelesaian & Upah
            </h2>

            <div class="overflow-hidden border border-stone-200 dark:border-stone-700 rounded-lg shadow-sm">
                <table class="w-full text-left text-xs border-collapse bg-stone-900/20">
                    <thead class="bg-stone-100 dark:bg-stone-800/50 border-b border-stone-200 dark:border-stone-700">
                        <tr>
                            <th class="px-6 py-3 font-bold text-stone-900 dark:text-stone-100 w-16 text-center">Step
                            </th>
                            <th class="px-6 py-3 font-bold text-stone-900 dark:text-stone-100">Aksi di Aplikasi</th>
                            <th class="px-6 py-3 font-bold text-stone-900 dark:text-stone-100">Dampak Sistem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800 text-stone-600 dark:text-stone-400">
                        <tr>
                            <td class="px-6 py-4 text-center font-mono">01</td>
                            <td class="px-6 py-4 font-medium text-stone-800 dark:text-stone-200 text-sm">Input Total
                                Barang Selesai</td>
                            <td class="px-6 py-4 italic">Mencatat volume produksi harian.</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-center font-mono">02</td>
                            <td class="px-6 py-4 font-medium text-amber-600 dark:text-amber-500 text-sm italic">
                                Identifikasi Barang Reject</td>
                            <td class="px-6 py-4 italic">Mengurangi kalkulasi upah bersih Anda.</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-center font-mono">03</td>
                            <td class="px-6 py-4 font-medium text-stone-800 dark:text-stone-200 text-sm">Klik
                                "Selesaikan Produksi"</td>
                            <td class="px-6 py-4 italic">Stok katalog bertambah secara otomatis.</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-center font-mono">04</td>
                            <td class="px-6 py-4 font-bold text-stone-900 dark:text-stone-100 text-sm uppercase">
                                Verifikasi Gaji</td>
                            <td class="px-6 py-4 italic">Upah masuk ke daftar pengeluaran perusahaan.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <footer
            class="bg-stone-900 dark:bg-stone-800 p-6 rounded-xl flex flex-col md:flex-row items-center justify-between text-white shadow-xl">
            <div class="text-center md:text-left mb-4 md:mb-0">
                <p class="text-[10px] tracking-widest uppercase opacity-50 mb-1">Butuh Bantuan Teknis?</p>
                <p class="text-xs font-medium italic text-amber-500 underline">Hubungi Admin Produksi: +62 812 3456 789
                </p>
            </div>
            <div class="text-[10px] font-mono opacity-30 hidden md:block uppercase">
                Pusat Kerajinan Tangan — V.2025
            </div>
        </footer>
    </div>
</x-filament-panels::page>
