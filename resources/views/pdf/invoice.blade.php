<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }} - PKT Artisan</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #2d3436;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        /* Accent Bar */
        .top-bar {
            height: 8px;
            background: linear-gradient(to right, #3D2B1F, #B87333);
        }

        .container {
            padding: 50px;
        }

        /* Header Section */
        .header {
            width: 100%;
            margin-bottom: 50px;
        }

        .brand-name {
            font-size: 28px;
            font-weight: 800;
            color: #3D2B1F;
            letter-spacing: 1px;
            margin: 0;
        }

        .brand-tagline {
            font-size: 10px;
            color: #B87333;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: bold;
        }

        .invoice-label {
            font-size: 35px;
            font-weight: 300;
            color: #b2bec3;
            text-transform: uppercase;
            text-align: right;
            margin: 0;
        }

        /* Info Grid */
        .info-table {
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 1px solid #f1f1f1;
            padding-bottom: 30px;
        }

        .info-table td {
            vertical-align: top;
        }

        .label {
            font-size: 9px;
            text-transform: uppercase;
            color: #b2bec3;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .value {
            font-size: 12px;
            font-weight: bold;
            color: #2d3436;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background-color: #f9f9f9;
            color: #636e72;
            text-align: left;
            padding: 15px;
            font-size: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #3D2B1F;
        }

        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #f1f1f1;
            font-size: 12px;
        }

        .product-name {
            font-weight: bold;
            color: #3D2B1F;
            display: block;
        }

        .product-desc {
            font-size: 10px;
            color: #b2bec3;
            font-style: italic;
        }

        /* Total Section */
        .totals-container {
            width: 100%;
            margin-top: 20px;
        }

        .total-row td {
            padding: 8px 15px;
        }

        .grand-total-box {
            background-color: #3D2B1F;
            color: white;
            padding: 12px 16px !important;
        }

        /* Footer */
        .footer {
            margin-top: 60px;
            text-align: center;
            border-top: 1px solid #f1f1f1;
            padding-top: 20px;
        }

        .footer p {
            font-size: 11px;
            color: #b2bec3;
            font-style: italic;
        }

        .watermark {
            position: absolute;
            bottom: 50px;
            right: 50px;
            opacity: 0.05;
            font-size: 80px;
            font-weight: bold;
            transform: rotate(-15deg);
            z-index: -1;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="top-bar"></div>
    <div class="watermark">PAID</div>

    <div class="container">
        <table class="header">
            <tr>
                <td>
                    <h1 class="brand-name">PKT ARTISAN</h1>
                    <span class="brand-tagline">Heritage Craftsmanship</span>
                </td>
                <td class="text-right">
                    <h2 class="invoice-label">INVOICE</h2>
                    <p class="value">#{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}</p>
                </td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td width="33%">
                    <span class="label">Diterbitkan Untuk:</span>
                    <span class="value">{{ $data->pelanggan->nama_pelanggan }}</span><br>
                    <span style="font-size: 11px; color: #636e72;">
                        {{ $data->pelanggan->telepon_pelanggan }}<br>
                        {{ $data->pelanggan->alamat_pelanggan }}
                    </span>
                </td>
                <td width="33%">
                    <span class="label">Tanggal Transaksi:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($data->tanggal)->format('d F Y') }}</span><br>
                    <span class="label" style="margin-top: 15px;">Metode Pembayaran:</span>
                    <span class="value">{{ strtoupper($data->status_pembayaran) }}</span>
                </td>
                <td width="33%" class="text-right">
                    <span class="label">Pengirim:</span>
                    <span class="value">Gudang Utama PKT</span><br>
                    <span style="font-size: 11px; color: #636e72;">
                        Jl. Industri Kreatif No. 123<br>
                        Bandung, Jawa Barat
                    </span>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Deskripsi Produk</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data->detailPenjualan as $item)
                    <tr>
                        <td>
                            <span class="product-name">{{ $item->produk->nama_produk }}</span>
                            <span class="product-desc">{{ Str::limit($item->produk->deskripsi, 60) }}</span>
                        </td>
                        <td class="text-right">{{ $item->jumlah }}</td>
                        <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right" style="font-weight: bold;">Rp
                            {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table style="width: 100%;">
            <tr>
                <td width="60%">
                    <div style="border-left: 3px solid #B87333; padding-left: 15px; margin-top: 20px;">
                        <span class="label">Catatan:</span>
                        <p style="font-size: 10px; color: #636e72; margin: 0;">
                            Barang yang sudah dibeli tidak dapat ditukar kecuali terdapat cacat produksi.
                            Simpan invoice ini sebagai bukti garansi karya artisan kami.
                        </p>
                    </div>
                </td>
                <td width="40%">
                    <table class="totals-container">
                        <tr class="total-row">
                            <td class="text-right" style="color: #b2bec3;">Subtotal</td>
                            <td class="text-right" style="font-weight: bold;">Rp
                                {{ number_format($data->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="total-row">
                            <td class="text-right" style="color: #b2bec3;">Pajak (0%)</td>
                            <td class="text-right" style="font-weight: bold;">Rp 0</td>
                        </tr>
                        <tr class="total-row">
                            <td class="text-right grand-total-box" style="border-radius: 8px 0 0 8px;">TOTAL AKHIR</td>
                            <td class="text-right grand-total-box" style="font-size: 18px; border-radius: 0 8px 8px 0;">
                                Rp {{ number_format($data->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="footer">
            <p>Terima kasih telah mendukung komunitas pengrajin lokal melalui PKT Artisan.</p>
            <div style="margin-top: 10px;">
                <span style="font-size: 9px; color: #dfe6e9;">www.pkt-artisan.com | @pkt.artisan</span>
            </div>
        </div>
    </div>
</body>

</html>
