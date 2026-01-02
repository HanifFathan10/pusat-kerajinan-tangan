<nav x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50) ? true : false"
    :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-md py-3' : 'bg-transparent py-5 text-white'"
    class="fixed top-0 left-0 w-full z-100 transition-all duration-500 px-6 md:px-12">

    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex gap-3 items-center">
            <img src="{{ asset('image/logo-pkt.png') }}" alt="pusat kerajinan tangan logo"
                class="h-12 w-12 object-contain">
            <a href="/" class="group flex items-center gap-2 relative z-110">
                <span class="font-serif text-2xl italic tracking-tighter transition-colors group-hover:text-amber-700">
                    Nusantara<span class="text-amber-700">PKT.</span>
                </span>
            </a>
        </div>

        <div class="hidden md:flex items-center gap-8">
            <a href="#produk" class="nav-link text-[10px] font-bold uppercase tracking-widest">Koleksi</a>
            <a href="#tracking" class="nav-link text-[10px] font-bold uppercase tracking-widest">Lacak
                Pesanan</a>

            <div class="h-4 w-px bg-stone-200"></div>

            <a href="#form-pesanan"
                :class="scrolled ? 'bg-amber-700 text-white hover:bg-amber-800 shadow-xl' :
                    'bg-stone-50 text-black hover:bg-amber-700 shadow-lg'"
                class="flex items-center gap-3 text-white px-5 py-2.5 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-amber-700 transition-all shadow-lg">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Cart</span>
                <span id="cart-count" class="bg-amber-500 text-stone-900 px-2 py-0.5 rounded-full text-[9px]">
                    {{ count((array) session('cart')) }}
                </span>
            </a>
        </div>

        <div class="flex md:hidden items-center gap-5 relative z-110">
            <a href="#form-pesanan" class="relative text-stone-900">
                <i class="fa-solid fa-bag-shopping text-xl"></i>
                <span
                    class="absolute -top-2 -right-2 bg-amber-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">
                    {{ count((array) session('cart')) }}
                </span>
            </a>

            <button @click="open = !open" class="p-2 outline-none">
                <div class="w-6 h-5 relative flex flex-col justify-between">
                    <span :class="open ? 'rotate-45 translate-y-2' : ''"
                        class="w-full h-0.5 bg-current transition-all duration-300"></span>
                    <span :class="open ? 'opacity-0' : ''"
                        class="w-full h-0.5 bg-current transition-all duration-300"></span>
                    <span :class="open ? '-rotate-45 -translate-y-2' : ''"
                        class="w-full h-0.5 bg-current transition-all duration-300"></span>
                </div>
            </button>
        </div>
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-full"
        class="fixed inset-0 bg-stone-500 z-105 flex flex-col justify-center items-center px-8 md:hidden"
        style="display: none;">

        <div class="flex flex-col gap-10 text-center">
            <a href="#produk" @click="open = false"
                class="text-3xl font-serif italic text-stone-900 hover:text-amber-700 transition-colors">Eksplorasi
                Koleksi</a>
            <a href="#tracking" @click="open = false"
                class="text-3xl font-serif italic text-stone-900 hover:text-amber-700 transition-colors">Lacak
                Pesanan</a>

            <div class="h-px w-20 bg-stone-200 mx-auto"></div>

            <a href="#form-pesanan" @click="open = false"
                class="bg-stone-900 text-white py-4 px-10 rounded-2xl text-xs font-bold uppercase tracking-widest shadow-xl">
                Lanjutkan Checkout
            </a>

            <div class="flex gap-6 text-stone-400 text-xl mt-4">
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
</nav>

@push('styles')
    <style>
        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 1.5px;
            background: #b45309;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }
    </style>
@endpush

@push('scripts')
    <script>
        async function removeCart(productId) {
            const cartCount = document.getElementById('cart-count');

            try {
                const response = await fetch("{{ route('remove.from.cart') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        id: productId
                    })
                })

                const data = await response.json();

                if (data.status == 'success') {
                    cartCount.innerText = data.cart_count;

                    alert(data.message);

                    window.location.reload();
                }
            } catch (error) {
                console.error("Error removing item from cart:", error);

                alert("Terjadi kesalahan saat menghapus item dari keranjang.");
            }
        }
    </script>
@endpush
