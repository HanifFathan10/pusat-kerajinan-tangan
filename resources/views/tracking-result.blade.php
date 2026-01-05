@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-stone-50 py-16 px-4" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 200)">
        <div class="max-w-4xl mx-auto">

            <div class="text-center mb-12" x-show="loaded" x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 transform -translate-y-4">
                <span class="text-[10px] font-bold uppercase tracking-[0.4em] text-stone-400">Arsip Kerajinan</span>
                <h1 class="font-serif text-5xl italic mt-2 mb-4 text-stone-900">Tracking Order</h1>
                <div class="inline-block px-4 py-1 bg-white border border-stone-200 rounded-full">
                    <p class="text-stone-500 text-[10px] font-mono uppercase tracking-tighter">
                        ID PESANAN: <span
                            class="text-stone-900 font-bold">#{{ str_pad($penjualan->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-[3rem] shadow-2xl shadow-stone-200/50 overflow-hidden border border-white"
                x-show="loaded" x-transition:enter="transition ease-out duration-1000 delay-300">

                <div class="bg-stone-900 p-10 md:p-14 text-white">
                    <div class="relative flex justify-between items-center">
                        <div class="absolute top-1/2 left-0 w-full h-px bg-stone-700 -translate-y-1/2"></div>

                        @php
                            $steps = [
                                ['icon' => 'fa-file-invoice', 'label' => 'Diterima', 'status' => true],
                                [
                                    'icon' => 'fa-money-bill-wave',
                                    'label' => 'Pembayaran',
                                    'status' => $penjualan->status_pembayaran == 'lunas',
                                ],
                                [
                                    'icon' => 'fa-hammer',
                                    'label' => 'Produksi',
                                    'status' => $penjualan->status_verifikasi == 'terverifikasi',
                                ],
                                [
                                    'icon' => 'fa-truck-fast',
                                    'label' => 'Selesai',
                                    'status' =>
                                        $penjualan->status_verifikasi == 'terverifikasi' &&
                                        $penjualan->status_pembayaran == 'lunas',
                                ],
                            ];
                        @endphp

                        @foreach ($steps as $index => $step)
                            <div class="relative z-10 group">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-500 {{ $step['status'] ? 'bg-white text-stone-900 scale-110 shadow-[0_0_20px_rgba(255,255,255,0.3)]' : 'bg-stone-800 text-stone-500 border border-stone-700' }}">
                                        <i class="fa-solid {{ $step['icon'] }} text-sm"></i>
                                    </div>
                                    <span
                                        class="absolute -bottom-8 whitespace-nowrap text-[9px] font-bold uppercase tracking-widest {{ $step['status'] ? 'text-white' : 'text-stone-500' }}">
                                        {{ $step['label'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="p-8 md:p-12">
                    <div class="grid md:grid-cols-2 gap-12">
                        <div class="space-y-6">
                            <section>
                                <h4 class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-3 italic">
                                    Informasi Pemesan</h4>
                                <div
                                    class="bg-stone-50 rounded-2xl p-5 border border-stone-100 transition-hover hover:bg-stone-100/50">
                                    <p class="text-lg font-serif italic text-stone-900">
                                        {{ $penjualan->pelanggan->nama_pelanggan }}</p>
                                    <p class="text-xs text-stone-500 leading-relaxed mt-1">
                                        {{ $penjualan->pelanggan->alamat_pelanggan }}</p>
                                </div>
                            </section>

                            <section>
                                <h4 class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-3 italic">
                                    Status Tagihan</h4>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter {{ $penjualan->status_pembayaran == 'lunas' ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-orange-50 text-orange-700 border border-orange-100' }}">
                                        {{ $penjualan->status_pembayaran }}
                                    </span>
                                    <span class="text-stone-300">/</span>
                                    <p class="text-sm font-bold text-stone-900">Rp
                                        {{ number_format($penjualan->total_harga, 0, ',', '.') }}</p>
                                </div>
                            </section>
                        </div>

                        <div class="bg-stone-50 rounded-4xl p-8 border border-stone-100 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4 opacity-5">
                                <i class="fa-solid fa-box-archive text-6xl"></i>
                            </div>

                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-6 italic">Rincian
                                Karya</h4>
                            <ul class="space-y-4">
                                @foreach ($penjualan->detailPenjualan as $item)
                                    <li class="flex justify-between items-center group">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="w-6 h-6 rounded-lg bg-stone-200 flex items-center justify-center text-[10px] font-bold group-hover:bg-stone-900 group-hover:text-white transition-colors">
                                                {{ $item->jumlah }}
                                            </span>
                                            <p class="text-sm italic text-stone-700">{{ $item->produk->nama_produk }}</p>
                                        </div>
                                        <p class="text-xs font-mono text-stone-400">@
                                            Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-8 pt-6 border-t border-stone-200">
                                <p class="text-[10px] text-stone-400 uppercase tracking-widest italic mb-1">Catatan Pesanan:
                                </p>
                                <p class="text-xs italic text-stone-600">
                                    "{{ $penjualan->catatan ?? 'Tidak ada catatan khusus.' }}"</p>
                            </div>

                            @if ($penjualan->status_pembayaran == 'lunas')
                                <div class="mt-8 pt-6 border-t border-stone-200">
                                    <a href="{{ route('kwitansi.download', $penjualan->id) }}"
                                        class="bg-stone-900 px-5 py-3 text-white rounded-full hover:bg-amber-800 transition-all shadow-xl active:scale-95 cursor-pointer flex gap-3 justify-center items-center text-lg decoration-none">
                                        <i class="fa-solid fa-receipt text-sm"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-[0.2em]">
                                            Download Kwitansi
                                        </span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-16 pt-8 border-t border-stone-100 flex flex-col items-center gap-6">
                        <a href="{{ route('landing.page') }}"
                            class="group flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.3em] text-stone-400 hover:text-stone-900 transition-all">
                            <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-2"></i>
                            Kembali Menjelajah
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
