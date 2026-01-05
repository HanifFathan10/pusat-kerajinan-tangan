<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kwitansi #{{ $penjualan->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #1c1917;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #f5f5f4;
            padding-bottom: 20px;
        }

        .brand {
            font-size: 24px;
            font-style: italic;
            color: #1c1917;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #78716c;
        }

        .info-section {
            margin-top: 30px;
            width: 100%;
        }

        .info-box {
            width: 50%;
            vertical-align: top;
            font-size: 12px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }

        .table th {
            background: #1c1917;
            color: white;
            text-align: left;
            padding: 10px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f5f5f4;
            font-size: 11px;
        }

        .total-section {
            margin-top: 30px;
            text-align: right;
        }

        .total-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #78716c;
        }

        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #1c1917;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 9px;
            color: #a8a29e;
            font-style: italic;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #dcfce7;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="brand">Pusat Kerajinan Tangan</div>
        <div class="subtitle">Kwitansi Pembayaran Resmi</div>
    </div>

    <table class="info-section">
        <tr>
            <td class="info-box">
                <div style="color: #a8a29e; text-transform: uppercase; font-size: 9px; letter-spacing: 1px;">Diterbitkan
                    Untuk:</div>
                <div style="font-weight: bold; font-size: 14px; margin-top: 5px;">
                    {{ $penjualan->pelanggan->nama_pelanggan }}</div>
                <div style="color: #444;">{{ $penjualan->pelanggan->alamat_pelanggan }}</div>
            </td>
            <td class="info-box" style="text-align: right;">
                <div style="color: #a8a29e; text-transform: uppercase; font-size: 9px; letter-spacing: 1px;">No.
                    Pesanan:</div>
                <div style="font-weight: bold; margin-top: 5px;">#{{ str_pad($penjualan->id, 5, '0', STR_PAD_LEFT) }}
                </div>
                <div style="margin-top: 10px;">
                    <span class="status-badge">{{ $penjualan->status_pembayaran }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Deskripsi Karya</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Harga</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan->detailPenjualan as $item)
                <tr>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td style="text-align: center;">{{ $item->jumlah }}</td>
                    <td style="text-align: right;">Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp{{ number_format($item->sub_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <span class="total-label">Total Pembayaran</span><br>
        <span class="total-amount">Rp{{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
    </div>

    <div class="footer">
        "Terima kasih telah mendukung karya artisan lokal. Setiap detail adalah dedikasi."<br>
        Dicetak otomatis pada {{ date('d F Y H:i') }}
    </div>
</body>

</html>
