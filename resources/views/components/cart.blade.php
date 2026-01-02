<div class="lg:sticky lg:top-32 space-y-8">
    <div class="bg-stone-900 rounded-[2.5rem] p-8 md:p-10 text-white shadow-2xl">
        <h3 class="font-serif text-2xl italic mb-8 flex items-center gap-3">
            <i class="fa-solid fa-bag-shopping text-sm text-amber-500"></i> Keranjang Anda
        </h3>

        <div id="cart-items-container" class="space-y-6 mb-8 max-h-100 overflow-y-auto pr-2 custom-scrollbar">
            @if (session('cart') && count(session('cart')) > 0)
                @foreach (session('cart') as $id => $details)
                    <div class="flex gap-4 items-center animate-fade-in">
                        <div class="w-16 h-16 bg-white/10 rounded-2xl overflow-hidden shrink-0">
                            <img src="{{ asset('storage/' . $details['image']) }}" class="object-cover w-full h-full">
                        </div>
                        <div class="grow">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-stone-200">
                                {{ $details['name'] }}
                            </h4>
                            <p class="text-[10px] text-stone-500 italic">{{ $details['quantity'] }} unit</p>
                            <p class="text-xs font-bold text-amber-500">
                                Rp{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <button onclick="removeFromCart({{ $id }})"
                                class="text-stone-400 hover:text-white transition">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-12 opacity-50">
                    <p class="text-xs italic">Keranjang kosong.</p>
                </div>
            @endif
        </div>

        <div class="mt-8 pt-6 border-t border-stone-800">
            <div class="flex justify-between items-center">
                <span class="text-[10px] uppercase tracking-[0.2em] text-stone-400">Total</span>
                <span id="cart-total-display" class="text-xl font-serif text-white">
                    Rp{{ number_format($total ?? 0, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>
</div>
