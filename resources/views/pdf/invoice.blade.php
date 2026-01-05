<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }} - PKT Artisan</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,600;1,700&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --wood-dark: #3D2B1F;
            --leather-tan: #A67B5B;
            --clay-light: #F2E8DF;
            --copper-accent: #B87333;
            --gold-premium: #D4AF37;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FDFBFA;
            color: var(--wood-dark);
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        .bg-clay {
            background-color: var(--clay-light);
        }

        .text-copper {
            color: var(--copper-accent);
        }

        .btn-premium {
            background: linear-gradient(135deg, var(--wood-dark) 0%, #1a1a1a 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            transition: transform 0.7s scale;
        }

        .product-card:hover img {
            transform: scale(1.08);
        }

        .font-serif-italic {
            font-family: 'Playfair Display', serif;
            font-style: italic;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                background: white;
            }
        }
    </style>
</head>

<body class="bg-stone-50 antialiased text-stone-900 p-0 md:p-8">
    <div class="max-w-4xl mx-auto bg-white shadow-2xl overflow-hidden md:rounded-4xl border border-stone-100">
        <div class="h-2 w-full bg-linear-to-r from-[#3D2B1F] to-[#B87333]"></div>

        <div class="p-8 md:p-16">
            <div class="flex flex-col md:flex-row justify-between items-start mb-16 gap-8">
                <div>
                    <h1 class="text-3xl font-black tracking-tighter text-stone-900 uppercase">PKT Artisan</h1>
                    <p class="text-[10px] font-bold uppercase tracking-[0.4em] text-amber-600 mt-1">Premium Handcrafted
                        Goods</p>
                </div>
                <div class="text-right">
                    <h2 class="text-6xl font-black text-stone-100 uppercase leading-none">Invoice</h2>
                    <p class="text-sm font-mono text-stone-400 mt-2 italic">
                        #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-12 mb-16">
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-4 italic">Pelanggan
                    </h4>
                    <p class="font-serif-italic text-xl text-stone-900">{{ $data->pelanggan->nama_pelanggan }}</p>
                    <p class="text-xs text-stone-500 leading-relaxed mt-2">{{ $data->pelanggan->alamat_pelanggan }}</p>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-4 italic">Rincian Waktu
                    </h4>
                    <p class="text-sm font-bold text-stone-900">{{ date('d F Y', strtotime($data->tanggal)) }}</p>
                    <p class="text-[10px] text-stone-400 uppercase tracking-tighter">Batas Bayar: +3 Hari</p>
                </div>
                <div class="text-right">
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-4 italic">Metode</h4>
                    <span
                        class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-stone-100 text-stone-700">Transfer
                        Bank</span>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-stone-100 mb-12">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-stone-50 border-b border-stone-100 text-[10px] uppercase tracking-widest text-stone-400">
                            <th class="px-8 py-5">Item Produk</th>
                            <th class="px-8 py-5 text-center">Qty</th>
                            <th class="px-8 py-5 text-right">Harga</th>
                            <th class="px-8 py-5 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @foreach ($data->detailPenjualan as $item)
                            <tr class="text-sm">
                                <td class="px-8 py-6 font-serif-italic text-stone-900 text-lg">
                                    {{ $item->produk->nama_produk }}</td>
                                <td class="px-8 py-6 text-center text-stone-600">{{ $item->jumlah }}</td>
                                <td class="px-8 py-6 text-right text-stone-400 italic">
                                    Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td class="px-8 py-6 text-right font-bold text-stone-900">
                                    Rp{{ number_format($item->sub_total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-end gap-8">
                <div class="text-xs text-stone-400 italic max-w-xs">
                    *Harap sertakan nomor invoice pada berita transfer untuk proses verifikasi yang lebih cepat.
                </div>
                <div class="w-full md:w-80 space-y-3">
                    <div class="flex justify-between text-sm px-4">
                        <span class="text-stone-400">Subtotal</span>
                        <span class="font-bold">Rp{{ number_format($data->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm px-4 border-b border-stone-100 pb-3">
                        <span class="text-stone-400">Pajak (0%)</span>
                        <span class="font-bold">Rp 0</span>
                    </div>
                    <div class="bg-stone-900 rounded-2xl p-6 text-white shadow-xl shadow-stone-900/20">
                        <p class="text-[10px] uppercase tracking-[0.3em] opacity-50 mb-1">Total Akhir</p>
                        <p class="text-2xl font-bold tracking-tighter">
                            Rp{{ number_format($data->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-stone-50 p-8 text-center border-t border-stone-100">
            <p class="text-[10px] text-stone-400 italic">Terima kasih telah mendukung komunitas pengrajin lokal melalui
                PKT Artisan.</p>
        </div>
    </div>
</body>

</html>
