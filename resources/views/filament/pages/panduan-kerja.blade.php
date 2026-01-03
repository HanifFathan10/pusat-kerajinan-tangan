<x-filament-panels::page>
    <div class="space-y-10">
        {{-- 1. Header Hero Section --}}
        <x-filament::section>
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="space-y-2 text-center md:text-left">
                    <h2 class="text-3xl font-serif italic text-amber-600 dark:text-amber-500">Seni Ketelitian & Prosedur
                        Operasi</h2>
                    <p class="text-sm font-medium tracking-widest text-stone-500 uppercase">Pusat Kerajinan Tangan —
                        Edisi V.2025</p>
                </div>
                <x-filament::button color="warning" icon="heroicon-m-chat-bubble-left-right" tag="a"
                    href="https://wa.me/6285724367468" size="lg">
                    Hubungi Admin Produksi
                </x-filament::button>
            </div>
        </x-filament::section>

        {{-- 2. Alur Kerja Utama (Workflow) --}}
        <div class="space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-stone-400 px-2">Empat Pilar Produksi</h3>
            <x-filament::grid columns="1" md="2" lg="4" gap="6">
                @php
                    $steps = [
                        [
                            'id' => '01',
                            't' => 'Persiapan Material',
                            'd' => 'Ambil bahan sesuai jadwal. Laporkan selisih fisik sebelum mulai.',
                            'i' => 'heroicon-o-cube-transparent',
                        ],
                        [
                            'id' => '02',
                            't' => 'Proses Kreasi',
                            'd' => 'Fokus pada presisi sambungan dan kekuatan struktur fisik produk.',
                            'i' => 'heroicon-o-beaker',
                        ],
                        [
                            'id' => '03',
                            't' => 'Quality Control',
                            'd' => 'Permukaan halus, warna konsisten, dan bebas noda sidik jari.',
                            'i' => 'heroicon-o-check-badge',
                        ],
                        [
                            'id' => '04',
                            't' => 'Laporan Digital',
                            'd' => 'Input hasil aktual untuk finalisasi upah dan stok katalog.',
                            'i' => 'heroicon-o-cloud-arrow-up',
                        ],
                    ];
                @endphp

                @foreach ($steps as $s)
                    <x-filament::section class="h-full">
                        <div class="flex flex-col items-center text-center space-y-4">
                            <div class="p-3 rounded-xl bg-amber-500/10 text-amber-600">
                                <x-filament::icon :icon="$s['i']" class="w-8 h-8" />
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-black text-stone-300 uppercase">Tahap
                                    {{ $s['id'] }}</span>
                                <h4 class="text-base font-bold text-stone-800 dark:text-stone-100">{{ $s['t'] }}
                                </h4>
                            </div>
                            <p class="text-xs leading-relaxed text-stone-500 dark:text-stone-400">
                                {{ $s['d'] }}
                            </p>
                        </div>
                    </x-filament::section>
                @endforeach
            </x-filament::grid>
        </div>

        {{-- 3. Detail Standar & Langkah Digital --}}
        <x-filament::grid columns="1" lg="2" gap="8">
            {{-- Standar Kualitas (QC) --}}
            <x-filament::section icon="heroicon-m-shield-check" icon-color="warning">
                <x-slot name="heading">Standar Kualitas (QC)</x-slot>

                <div class="grid grid-cols-1 gap-6 pt-4">
                    @foreach ([
        'Konstruksi Fisik' => 'Sambungan presisi, simetris, dan struktur harus kokoh.',
        'Penyelesaian Akhir' => 'Permukaan halus tanpa gumpalan cat atau plitur yang kasar.',
        'Konsistensi Warna' => 'Warna identik dengan sampel referensi tanpa variasi mencolok.',
        'Kebersihan Produk' => 'Bebas sisa lem, debu, atau noda sidik jari sebelum diserahkan.',
    ] as $title => $desc)
                        <div class="flex items-start gap-4">
                            <div class="mt-1 shrink-0">
                                <x-heroicon-m-check-circle class="w-5 h-5 text-success-500" />
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-stone-800 dark:text-stone-200">{{ $title }}
                                </h5>
                                <p class="text-xs text-stone-500">{{ $desc }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>

            {{-- Langkah Digital --}}
            <x-filament::section icon="heroicon-m-cursor-arrow-rays" icon-color="primary">
                <x-slot name="heading">Langkah Pelaporan Digital</x-slot>

                <div class="relative space-y-8 pt-6 pl-4 border-l-2 border-stone-100 dark:border-stone-800 ml-2">
                    <div class="relative">
                        <div class="absolute -left-6.25 top-0 w-4 h-4 rounded-full bg-stone-200 dark:bg-stone-700">
                        </div>
                        <h5 class="text-sm font-bold">1. Input Volume Produksi</h5>
                        <p class="text-xs text-stone-500">Mencatat jumlah barang fisik yang berhasil diselesaikan hari
                            ini.</p>
                    </div>
                    <div class="relative">
                        <div class="absolute -left-6.25 top-0 w-4 h-4 rounded-full bg-stone-200 dark:bg-stone-700">
                        </div>
                        <h5 class="text-sm font-bold">2. Identifikasi Produk Reject</h5>
                        <p class="text-xs text-stone-500">Pemisahan barang cacat untuk akurasi perhitungan upah bersih.
                        </p>
                    </div>
                    <div class="relative">
                        <div class="absolute -left-6.25 top-0 w-4 h-4 rounded-full bg-amber-500 shadow-sm"></div>
                        <h5 class="text-sm font-bold text-amber-600">3. Finalisasi Data</h5>
                        <p class="text-xs text-stone-500">Klik selesai agar stok katalog otomatis bertambah di website.
                        </p>
                    </div>
                </div>
            </x-filament::section>
        </x-filament::grid>
    </div>
</x-filament-panels::page>
