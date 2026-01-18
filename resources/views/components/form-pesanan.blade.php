<form action="{{ route('pesan.store') }}" method="POST" class="space-y-8">
    @csrf
    <div class="grid md:grid-cols-2 gap-8">
        <div>
            <label class="block text-[10px] font-bold uppercase tracking-widest mb-3 text-stone-400">Nama
                Lengkap</label>
            <input type="text" name="nama_pelanggan" required
                class="w-full bg-clay/10 border-b-2 border-stone-200 py-3 focus:border-copper-accent outline-none transition text-sm font-medium"
                placeholder="Masukkan nama anda...">
        </div>
        <div>
            <label class="block text-[10px] font-bold uppercase tracking-widest mb-3 text-stone-400">Nomor
                WhatsApp</label>
            <input type="text" name="telepon_pelanggan" required
                class="w-full bg-clay/10 border-b-2 border-stone-200 py-3 focus:border-copper-accent outline-none transition text-sm font-medium"
                placeholder="0812...">
        </div>
    </div>

    <div>
        <label class="block text-[10px] font-bold uppercase tracking-widest mb-3 text-stone-400">Alamat
            Email Aktif</label>
        <input type="email" name="email_pelanggan" required
            class="w-full bg-clay/10 border-b-2 border-stone-200 py-3 focus:border-copper-accent outline-none transition text-sm font-medium"
            placeholder="email@contoh.com">
    </div>

    <div>
        <label class="block text-[10px] font-bold uppercase tracking-widest mb-3 text-stone-400">Alamat
            Pengiriman Lengkap</label>
        <textarea name="alamat_pelanggan" required rows="3"
            class="w-full bg-clay/10 border-b-2 border-stone-200 py-3 focus:border-copper-accent outline-none transition text-sm font-medium resize-none"
            placeholder="Nama Jalan, No. Rumah, Kecamatan, Kota..."></textarea>
    </div>

    <div>
        <label class="block text-[10px] font-bold uppercase tracking-widest mb-3 text-stone-400">Catatan
            Tambahan</label>
        <textarea name="catatan" rows="3"
            class="w-full bg-clay/10 border-b-2 border-stone-200 py-3 focus:border-copper-accent outline-none transition text-sm font-medium resize-none"
            placeholder="Catatan tambahan untuk pesanan Anda..."></textarea>
    </div>

    <div class="p-6 bg-stone-50 rounded-2xl border border-stone-100 flex items-start gap-4 mb-8">
        <i class="fa-solid fa-circle-info text-copper mt-1"></i>
        <p class="text-base text-stone-500 leading-relaxed italic">
            Pesanan Anda akan diverifikasi oleh tim keuangan kami. Anda akan menerima notifikasi
            pembayaran melalui Email dan WhatsApp dalam waktu 1x24 jam.
        </p>
    </div>

    <button type="submit" @if (count((array) session('cart')) == 0) disabled @endif
        class="w-full btn-premium py-5 text-white text-[11px] font-bold uppercase tracking-[0.3em] rounded-2xl disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer">
        Konfirmasi & Pesan Sekarang
    </button>
</form>
