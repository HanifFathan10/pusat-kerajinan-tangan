<section id="produk" class="py-24 px-6 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row gap-12">

            <aside class="w-full md:w-64 shrink-0">
                <div class="sticky top-32">
                    <h3 class="font-serif text-3xl italic mb-8 border-b border-stone-100 pb-4">Katalog.</h3>

                    <div class="mb-10">
                        <h4 class="text-[11px] font-bold uppercase tracking-[0.2em] text-stone-400 mb-5">Kategori</h4>
                        <div class="space-y-4">
                            @foreach (['Dekorasi Rumah', 'Aksesoris', 'Furniture'] as $cat)
                                <label class="flex items-center group cursor-pointer">
                                    <input type="checkbox"
                                        class="w-4 h-4 border-stone-300 rounded accent-stone-900 focus:ring-0">
                                    <span
                                        class="ml-3 text-sm text-stone-500 group-hover:text-stone-900 transition-colors uppercase tracking-wider">{{ $cat }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-10">
                        <h4 class="text-[11px] font-bold uppercase tracking-[0.2em] text-stone-400 mb-5">Rentang Harga
                        </h4>
                        <input type="range" min="0" max="5000000" step="100000"
                            class="w-full h-1 bg-stone-100 rounded-lg appearance-none cursor-pointer accent-amber-700">
                        <div class="flex justify-between text-[10px] mt-3 font-mono text-stone-400">
                            <span>IDR 0</span>
                            <span>IDR 5M</span>
                        </div>
                    </div>

                    <div class="p-6 rounded-4xl bg-stone-50 border border-stone-100 relative overflow-hidden">
                        <i class="fa-solid fa-quote-left absolute top-4 left-4 opacity-5 text-4xl"></i>
                        <p class="italic text-[11px] leading-relaxed text-stone-500 relative z-10">
                            "Setiap karya melewati proses kurasi material & teknik kriya Nusantara yang ketat."
                        </p>
                    </div>
                </div>
            </aside>

            <div class="grow">
                <div
                    class="flex flex-col sm:flex-row justify-between items-center mb-12 gap-4 border-b border-stone-50 pb-8">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400">
                        Arsip Karya: <span class="text-stone-900">{{ count($produks) }} Items</span>
                    </p>
                    <div class="flex items-center gap-4 bg-stone-50 px-4 py-2 rounded-full border border-stone-100">
                        <span class="text-[9px] font-bold uppercase tracking-widest text-stone-400">Urutkan:</span>
                        <select
                            class="bg-transparent border-none text-[10px] font-bold uppercase tracking-widest outline-none cursor-pointer text-stone-900">
                            <option>Terbaru</option>
                            <option>Harga: Rendah - Tinggi</option>
                            <option>Harga: Tinggi - Rendah</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16">
                    @foreach ($produks as $p)
                        <div class="group" data-aos="fade-up">
                            <div
                                class="relative aspect-4/5 overflow-hidden rounded-[2.5rem] bg-stone-100 mb-6 transition-all duration-700 group-hover:shadow-2xl group-hover:shadow-stone-200">
                                @if ($p->gambar_produk && count($p->gambar_produk) > 0)
                                    <img src="{{ asset('storage/' . $p->gambar_produk[0]) }}"
                                        alt="{{ $p->nama_produk }}"
                                        class="h-full w-full object-cover grayscale-[0.3] group-hover:grayscale-0 transition-all duration-1000 group-hover:scale-110">
                                @endif

                                <div
                                    class="absolute inset-0 flex items-end justify-center p-6 opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-4 group-hover:translate-y-0 bg-linear-to-t from-stone-900/40 via-transparent">
                                    <div class="flex gap-2 w-full">
                                        <button onclick="addToCart({{ $p->id }})"
                                            class="grow bg-white text-stone-900 py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-amber-700 hover:text-white transition-all shadow-xl">
                                            Tambah Keranjang
                                        </button>
                                        <button onclick="quickBuy({{ $p->id }})"
                                            class="w-12 h-12 bg-white text-stone-900 rounded-2xl flex items-center justify-center hover:bg-stone-900 hover:text-white transition-all shadow-xl">
                                            <i class="fa-solid fa-bolt-lightning text-xs"></i>
                                        </button>
                                    </div>
                                </div>

                                <span
                                    class="absolute top-6 left-6 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[8px] font-bold uppercase tracking-widest text-stone-900">
                                    Handcrafted
                                </span>
                            </div>

                            <div class="px-2">
                                <div class="flex justify-between items-start mb-2">
                                    <h3
                                        class="font-serif text-xl italic text-stone-900 group-hover:text-amber-800 transition-colors">
                                        {{ $p->nama_produk }}
                                    </h3>
                                    <p class="font-mono text-xs text-stone-400">
                                        #{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</p>
                                </div>
                                <p class="text-[10px] text-stone-400 uppercase tracking-[0.2em] mb-3">
                                    {{ $p->kategori ?? 'Artisan Work' }}</p>
                                <p class="text-sm font-bold text-stone-900 tracking-tighter">
                                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-24 pt-12 border-t border-stone-50 flex justify-center items-center gap-8">
                    <button
                        class="group flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-stone-300 hover:text-stone-900 transition-all">
                        <i class="fa-solid fa-chevron-left transition-transform group-hover:-translate-x-1"></i> Prev
                    </button>
                    <div class="flex gap-4">
                        <span
                            class="w-8 h-8 rounded-full bg-stone-900 text-white flex items-center justify-center text-[10px] font-bold shadow-lg">1</span>
                        <span
                            class="w-8 h-8 rounded-full text-stone-400 flex items-center justify-center text-[10px] font-bold hover:bg-stone-50 transition-colors cursor-pointer">2</span>
                    </div>
                    <button
                        class="group flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-stone-300 hover:text-stone-900 transition-all">
                        Next <i class="fa-solid fa-chevron-right transition-transform group-hover:translate-x-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
