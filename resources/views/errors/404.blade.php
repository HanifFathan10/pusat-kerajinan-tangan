@extends('layouts.app')

@section('content')
    @include('components.navbar')

    <main class="min-h-screen flex items-center justify-center bg-stone-900 px-6 py-32">
        <div class="max-w-3xl w-full text-center">
            <div class="relative mb-8" data-aos="fade-up">
                <h1 class="font-serif text-[120px] md:text-[200px] leading-none text-stone-200 select-none">
                    404
                </h1>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span
                        class="text-amber-700 text-[10px] md:text-xs font-bold uppercase tracking-[0.6em] bg-stone-50 px-4">
                        Karya Tidak Ditemukan
                    </span>
                </div>
            </div>

            <div class="space-y-6" data-aos="fade-up" data-delay="200">
                <h2 class="font-serif text-3xl md:text-5xl italic text-amber-700 leading-tight">
                    Maaf, Jejak Ini Telah Terhapus.
                </h2>

                <p class="text-stone-500 text-sm md:text-base leading-loose max-w-md mx-auto italic">
                    Halaman yang Anda cari tidak dapat kami temukan di galeri nusantara PKT kami. Mungkin url telah
                    kadaluwarsa
                    atau alamat yang dimasukkan kurang tepat.
                </p>

                <div class="pt-10 flex flex-col md:flex-row gap-4 justify-center">
                    <a href="{{ url('/') }}"
                        class="inline-block bg-amber-700 text-white px-10 py-4 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-amber-900 transition shadow-lg hover:-translate-y-1">
                        Kembali ke Beranda
                    </a>
                    <a href="{{ url('/#tracking') }}"
                        class="inline-block bg-white text-stone-900 border border-stone-200 px-10 py-4 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-stone-50 transition shadow-sm hover:-translate-y-1">
                        Lacak Pesanan
                    </a>
                </div>
            </div>

            <div class="mt-20 opacity-20 flex justify-center gap-8">
                <i class="fa-solid fa-leaf text-stone-400"></i>
                <i class="fa-solid fa-hand-fist text-stone-400"></i>
                <i class="fa-solid fa-gem text-stone-400"></i>
            </div>
        </div>
    </main>

    @include('components.footer')
@endsection
