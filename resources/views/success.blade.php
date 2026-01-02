@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-stone-50 flex items-center justify-center p-6">
        <div class="max-w-2xl w-full bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-stone-100">
            <div class="p-8 md:p-12 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-50 text-green-600 rounded-full mb-6">
                    <i class="fa-solid fa-check text-3xl"></i>
                </div>

                <h1 class="font-serif text-3xl italic mb-2">Pesanan Diterima!</h1>
                <p class="text-stone-500 text-sm mb-8">Simpan ID Pesanan Anda untuk melacak status pengiriman.</p>

                <div class="bg-clay/20 rounded-2xl p-6 mb-8 border border-stone-100">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">ID Pesanan Anda</p>
                    <h2 class="text-3xl font-mono font-bold text-stone-900">
                        #{{ str_pad($penjualan->id, 5, '0', STR_PAD_LEFT) }}</h2>
                </div>

                <div class="text-left space-y-4 mb-8">
                    <div class="flex justify-between text-sm">
                        <span class="text-stone-500">Total Pembayaran:</span>
                        <span
                            class="font-bold text-copper">Rp{{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-[11px] text-stone-600 italic leading-relaxed">
                        *Silakan lakukan transfer ke Rekening BCA 12345678 a/n Nusantara PKT dan kirim bukti transfer
                        melalui
                        tombol di bawah.
                    </p>
                </div>

                <div class="flex flex-col gap-4">
                    <a href="https://wa.me/6285724367468?text=Halo%20Admin,%20saya%20ingin%20konfirmasi%20pembayaran%20ID%20Pesanan%20%23{{ str_pad($penjualan->id, 5, '0', STR_PAD_LEFT) }}"
                        target="_blank"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-xl font-bold text-sm transition flex items-center justify-center gap-2">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        Konfirmasi via WhatsApp
                    </a>

                    <a href="{{ route('landing.page') }}"
                        class="text-xs text-stone-400 hover:text-stone-900 transition font-bold uppercase tracking-widest">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
