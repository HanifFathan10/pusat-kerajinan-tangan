@extends('layouts.app')

@section('content')
    @include('components.navbar')

    <main class="relative">
        <div
            class="bg-stone-900 text-white text-[9px] md:text-[10px] uppercase tracking-[0.3em] py-3 text-center font-semibold px-4 pt-20 md:pt-24">
            Gratis Pengiriman Seluruh Indonesia Untuk Pembelian Diatas <span class="text-amber-400">Rp 1.000.000</span>
        </div>

        @include('components.jumbotron')

        @include('components.produk')

        <section class="py-20 md:py-32 bg-stone-50 border-y border-stone-100 px-6 overflow-hidden" id="tracking">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-12 gap-12 lg:gap-24 items-center">
                    <div class="lg:col-span-5" data-aos="fade-right"">
                        <span
                            class="text-amber-700 text-[10px] font-bold uppercase tracking-[0.4em] mb-4 block">Transparency</span>
                        <h2 class="font-serif text-4xl md:text-5xl italic mb-8 text-stone-900 leading-[1.1]">Jejak Pembuatan
                            <br class="hidden md:block"> Karya Anda.
                        </h2>
                        <p class="text-stone-500 text-sm leading-loose mb-10 italic max-w-md">
                            Setiap goresan dan bentukan diabadikan. Masukkan kode pesanan Anda untuk memantau tahap produksi
                            oleh artisan kami hingga sampai ke depan pintu Anda.
                        </p>

                        <form action="{{ route('order.track') }}" method="GET" class="relative group">
                            <input type="text" name="order_id" required placeholder="ID Pesanan (e.g. #00001)"
                                class="w-full bg-white px-8 py-5 rounded-2xl border border-stone-200 text-sm focus:ring-2 focus:ring-stone-900 focus:border-transparent shadow-sm transition-all outline-none">
                            <button type="submit"
                                class="absolute right-2 top-2 bottom-2 bg-stone-900 text-white px-6 md:px-10 rounded-xl font-bold text-[9px] uppercase tracking-widest hover:bg-stone-800 transition shadow-md">
                                Lacak
                            </button>
                        </form>

                        @if (session('error_tracking'))
                            <p class="text-red-500 text-[10px] mt-4 flex items-center gap-2 italic">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error_tracking') }}
                            </p>
                        @endif
                    </div>

                    <div class="lg:col-span-7 grid grid-cols-2 gap-4 md:gap-6" data-aos="fade-left">
                        <div
                            class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-stone-100 space-y-4 hover:shadow-xl hover:-translate-y-2 transition-all duration-500">
                            <div
                                class="w-12 h-12 bg-stone-900 text-white rounded-full flex items-center justify-center font-serif italic text-xl">
                                1</div>
                            <h4 class="text-[11px] font-bold uppercase tracking-widest">Kurasi Bahan</h4>
                            <p class="text-[10px] text-stone-400 italic leading-relaxed">Pemilihan material alam terbaik
                                secara
                                berkelanjutan.</p>
                        </div>
                        <div
                            class="bg-stone-900 p-8 rounded-[2.5rem] shadow-xl text-white space-y-4 md:translate-y-12 transition-all duration-500">
                            <div
                                class="w-12 h-12 bg-amber-500 text-stone-900 rounded-full flex items-center justify-center font-serif italic text-xl">
                                2</div>
                            <h4 class="text-[11px] font-bold uppercase tracking-widest">Tangan Artisan</h4>
                            <p class="text-[10px] text-stone-400 italic leading-relaxed">Proses pengerjaan manual dengan
                                ketelitian tinggi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="flex gap-8 py-24 px-6 bg-white">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-8">
                    <div class="flex flex-col items-center text-center p-8 rounded-3xl hover:bg-stone-50 transition-colors">
                        <div class="w-16 h-16 bg-stone-50 rounded-full flex items-center justify-center mb-6">
                            <i class="fa-solid fa-leaf text-2xl text-stone-400"></i>
                        </div>
                        <h4 class="text-[11px] font-bold uppercase tracking-widest mb-3">Sustainable Material</h4>
                        <p class="text-xs text-stone-500 italic leading-relaxed">Bahan alam tersertifikasi & ramah
                            lingkungan.
                        </p>
                    </div>
                    <div class="flex flex-col items-center text-center p-8 rounded-3xl hover:bg-stone-50 transition-colors">
                        <div class="w-16 h-16 bg-stone-50 rounded-full flex items-center justify-center mb-6">
                            <i class="fa-solid fa-handshake-angle text-2xl text-stone-400"></i>
                        </div>
                        <h4 class="text-[11px] font-bold uppercase tracking-widest mb-3">Empowering Artisans</h4>
                        <p class="text-xs text-stone-500 italic leading-relaxed">Mendukung perekonomian komunitas lokal
                            Indonesia.</p>
                    </div>
                    <div class="flex flex-col items-center text-center p-8 rounded-3xl hover:bg-stone-50 transition-colors">
                        <div class="w-16 h-16 bg-stone-50 rounded-full flex items-center justify-center mb-6">
                            <i class="fa-solid fa-shield-halved text-2xl text-stone-400"></i>
                        </div>
                        <h4 class="text-[11px] font-bold uppercase tracking-widest mb-3">Quality Assurance</h4>
                        <p class="text-xs text-stone-500 italic leading-relaxed">Setiap produk melewati inspeksi kualitas
                            ketat.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="form-pesanan" class="py-24 bg-white px-6 border-t border-stone-100">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col lg:flex-row gap-16">
                    <div class="w-full lg:w-5/12 lg:order-2">
                        @include('components.cart')
                    </div>

                    <div class="w-full lg:w-7/12 lg:order-1">
                        <div class="mb-12">
                            <span class="text-copper text-[10px] font-bold uppercase tracking-[0.3em] mb-4 block">Langkah
                                Terakhir</span>
                            <h2 class="font-serif text-5xl italic leading-tight mb-4 text-stone-800">Detail Pengiriman</h2>
                            <p class="text-stone-500 text-sm italic">Mohon lengkapi data diri Anda untuk proses pengiriman
                                karya
                                artisan kami.</p>
                        </div>

                        @include('components.form-pesanan')
                    </div>
                </div>
            </div>
        </section>

    </main>



    @include('components.footer')
@endsection

@push('scripts')
    <script>
        const getCartCountElement = () => document.getElementById('cart-count');

        async function addToCart(productId) {
            try {
                const response = await fetch("{{ route('add.to.cart') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        id: productId
                    })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    if (getCartCountElement()) getCartCountElement().innerText = data.cart_count;

                    Swal.fire({
                        title: 'Karya Ditambahkan',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#1c1917',
                        iconColor: '#f59e0b',
                    }).then(() => {
                        window.location.reload();
                    });
                }
            } catch (error) {
                Swal.fire({
                    title: 'Gagal',
                    text: 'Terjadi kesalahan sistem.',
                    icon: 'error',
                    confirmButtonColor: '#7f1d1d'
                });
            }
        }

        async function removeFromCart(productId) {
            const result = await Swal.fire({
                title: 'Hapus Pesanan?',
                text: "Karya ini akan dikeluarkan dari keranjang belanja Anda.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1c1917',
                cancelButtonColor: '#78716c',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch("{{ route('remove.from.cart') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: productId
                        })
                    });

                    const data = await response.json();
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Terupdate',
                            text: 'Keranjang berhasil diperbarui.',
                            icon: 'success',
                            confirmButtonColor: '#1c1917'
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                } catch (error) {
                    Swal.fire('Gagal', 'Gagal memperbarui keranjang.', 'error');
                }
            }
        }
    </script>
@endpush
