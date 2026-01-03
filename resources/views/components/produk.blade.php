<section id="produk" class="py-24 px-6 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row gap-12">
            <aside class="w-full md:w-64 shrink-0">
                <div class="sticky top-32">
                    <h3 class="font-serif text-3xl italic mb-8 border-b border-stone-100 pb-4 text-stone-900">Koleksi.
                    </h3>

                    <form action="{{ route('landing.page') }}#produk" method="GET">
                        <div class="mb-10">
                            <h4 class="text-[11px] font-bold uppercase tracking-[0.2em] text-stone-400 mb-4">Cari Produk
                            </h4>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Nama karya..."
                                    class="w-full bg-stone-50 border border-stone-100 rounded-xl px-4 py-3 text-xs outline-none focus:ring-1 focus:ring-stone-900 transition-all">
                            </div>
                        </div>

                        <div class="mb-10">
                            <h4 class="text-[11px] font-bold uppercase tracking-[0.2em] text-stone-400 mb-5">
                                Ketersediaan</h4>
                            <div class="space-y-4">
                                <label class="flex items-center group cursor-pointer">
                                    <input type="checkbox" name="ready_stock" value="1"
                                        {{ request('ready_stock') ? 'checked' : '' }} onchange="this.form.submit()"
                                        class="w-4 h-4 border-stone-300 rounded accent-stone-900 focus:ring-0">
                                    <span
                                        class="ml-3 text-[11px] text-stone-500 group-hover:text-stone-900 transition-colors uppercase tracking-widest">
                                        Ready Stock Only
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-10">
                            <h4 class="text-[11px] font-bold uppercase tracking-[0.2em] text-stone-400 mb-5">Urutkan
                                Harga</h4>
                            <select name="sort" onchange="this.form.submit()"
                                class="w-full bg-stone-50 border border-stone-100 rounded-xl px-4 py-3 text-[10px] font-bold uppercase tracking-widest outline-none">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru
                                </option>
                                <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>
                                    Terendah - Tertinggi</option>
                                <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>
                                    Tertinggi - Terendah</option>
                            </select>
                        </div>
                    </form>
                </div>
            </aside>

            <div class="grow">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-16 gap-4">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-stone-400">
                        Menampilkan <span
                            class="text-stone-900 italic font-serif text-sm px-1">{{ $produks->total() }}</span> Koleksi
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-20">
                    @forelse ($produks as $p)
                        <div class="group relative" data-aos="fade-up">
                            <div
                                class="relative aspect-4/5 overflow-hidden rounded-4xl bg-stone-100 mb-8 shadow-sm group-hover:shadow-2xl transition-all duration-700">
                                @if ($p->gambar_produk && count($p->gambar_produk) > 0)
                                    <img src="{{ asset('storage/' . $p->gambar_produk[0]) }}"
                                        alt="{{ $p->nama_produk }}"
                                        class="h-full w-full object-cover grayscale-[0.2] group-hover:grayscale-0 transition-all duration-1000 scale-100 group-hover:scale-110">
                                @endif

                                @if ($p->stok_produk <= 5)
                                    <div
                                        class="absolute top-6 right-6 bg-red-600 text-white text-[8px] font-bold uppercase tracking-widest px-3 py-1 rounded-full animate-pulse shadow-lg z-20">
                                        Hanya Tersisa {{ $p->stok_produk }}
                                    </div>
                                @endif

                                <div
                                    class="absolute inset-0 bg-stone-900/10 opacity-0 group-hover:opacity-100 transition-all duration-500 backdrop-blur-[2px] flex items-center justify-center gap-3">
                                    <button onclick="addToCart({{ $p->id }})"
                                        class="bg-white text-stone-900 w-12 h-12 rounded-full flex items-center justify-center hover:bg-amber-700 hover:text-white transition-all shadow-xl hover:scale-110">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                    <button onclick="quickBuy({{ $p->id }})"
                                        class="bg-stone-900 text-white px-6 py-3 rounded-full text-[9px] font-bold uppercase tracking-widest hover:bg-amber-700 transition-all shadow-xl hover:scale-105">
                                        Beli Langsung
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-serif text-2xl italic text-stone-900 tracking-tight">
                                        {{ $p->nama_produk }}</h3>
                                    <span class="font-mono text-[9px] text-stone-300">#ART-{{ $p->id }}</span>
                                </div>
                                <div class="flex justify-between items-center border-t border-stone-50 pt-3">
                                    <p class="text-[9px] text-stone-400 uppercase tracking-widest">
                                        {{ $p->kategori ?? 'Lokal Artisan' }}</p>
                                    <p class="text-sm font-bold text-stone-900 tracking-tighter">Rp
                                        {{ number_format($p->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center">
                            <p class="font-serif italic text-stone-400 text-xl">"Maaf, tidak ada karya yang sesuai
                                dengan kriteria Anda."</p>
                            <a href="#tracking"
                                class="mt-4 block text-center text-[9px] uppercase tracking-[0.2em] text-stone-400 hover:text-stone-900 underline transition-all">
                                Lihat Jejak Produksi
                            </a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-24 pt-12 border-t border-stone-50 flex flex-col items-center gap-6">
                    <p class="text-[9px] uppercase tracking-widest text-stone-400">
                        Menampilkan {{ $produks->firstItem() }} - {{ $produks->lastItem() }} dari
                        {{ $produks->total() }} karya
                    </p>

                    <div class="flex justify-center items-center gap-4">
                        @if ($produks->onFirstPage())
                            <span
                                class="opacity-30 flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-stone-300">
                                <i class="fa-solid fa-chevron-left"></i> Prev
                            </span>
                        @else
                            <a href="{{ $produks->previousPageUrl() }}#produk"
                                class="group flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-stone-400 hover:text-stone-900 transition-all">
                                <i class="fa-solid fa-chevron-left transition-transform group-hover:-translate-x-1"></i>
                                Prev
                            </a>
                        @endif

                        <div class="flex gap-2">
                            @foreach ($produks->getUrlRange(1, $produks->lastPage()) as $page => $url)
                                @if ($page == $produks->currentPage())
                                    <span
                                        class="w-8 h-8 rounded-full bg-stone-900 text-white flex items-center justify-center text-[10px] font-bold shadow-lg">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}#produk"
                                        class="w-8 h-8 rounded-full text-stone-400 flex items-center justify-center text-[10px] font-bold hover:bg-stone-50 transition-colors">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        </div>

                        @if ($produks->hasMorePages())
                            <a href="{{ $produks->nextPageUrl() }}#produk"
                                class="group flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-stone-400 hover:text-stone-900 transition-all">
                                Next <i
                                    class="fa-solid fa-chevron-right transition-transform group-hover:translate-x-1"></i>
                            </a>
                        @else
                            <span
                                class="opacity-30 flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-stone-300">
                                Next <i class="fa-solid fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
