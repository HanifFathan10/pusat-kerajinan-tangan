@extends('layouts.app')

@section('content')
    @include('components.navbar')

    <main class="min-h-screen flex items-center justify-center bg-stone-900 text-white px-6 py-32">
        <div class="max-w-3xl w-full text-center">
            <div class="mb-12" data-aos="zoom-in">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full border border-amber-500/30 mb-6">
                    <i class="fa-solid fa-screwdriver-wrench text-3xl text-amber-500"></i>
                </div>
                <h1 class="font-serif text-6xl md:text-8xl italic leading-none text-white">
                    500
                </h1>
            </div>

            <div class="space-y-6" data-aos="fade-up">
                <span class="text-amber-500 text-[10px] font-bold uppercase tracking-[0.4em]">Internal Server Error</span>
                <h2 class="font-serif text-3xl md:text-5xl italic leading-tight">
                    Aplikasi Kami Sedang <br> Dalam Perbaikan.
                </h2>

                <p class="text-stone-400 text-sm md:text-base leading-loose max-w-md mx-auto italic">
                    Mohon maaf atas ketidaknyamanannya. Kami sedang memperbaiki kendala teknis agar Anda dapat
                    kembali menikmati pengalaman berbelanja yang sempurna.
                </p>

                <div class="pt-10">
                    <a href="{{ url('/') }}"
                        class="inline-block bg-amber-500 text-stone-900 px-12 py-4 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-amber-400 transition shadow-xl hover:-translate-y-1">
                        Coba Lagi Nanti
                    </a>
                </div>
            </div>

            <div class="mt-20 pt-12 border-t border-stone-800">
                <p class="text-stone-500 text-[10px] uppercase tracking-[0.2em] mb-4">Butuh bantuan segera?</p>
                <div class="flex justify-center gap-8 text-stone-400">
                    <a href="#" class="hover:text-amber-500 transition-colors text-xs flex items-center gap-2">
                        <i class="fa-brands fa-whatsapp"></i> WhatsApp
                    </a>
                    <a href="#" class="hover:text-amber-500 transition-colors text-xs flex items-center gap-2">
                        <i class="fa-regular fa-envelope"></i> Email Support
                    </a>
                </div>
            </div>
        </div>
    </main>

    @include('components.footer')
@endsection
