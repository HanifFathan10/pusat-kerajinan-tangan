<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }} - Nusantara PKT</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #1f2937;
            background-color: #f3f4f6;
            line-height: 1.6;
            padding: 40px 20px;
        }

        .invoice-card {
            max-width: 850px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .accent-bar {
            height: 6px;
            background: #111827;
        }

        .header {
            padding: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid #f3f4f6;
        }

        .brand h1 {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #111827;
        }

        .brand p {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            background: #fef3c7;
            color: #92400e;
            margin-bottom: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            padding: 40px;
            gap: 20px;
        }

        .info-label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            color: #9ca3af;
            margin-bottom: 8px;
            display: block;
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
        }

        .customer-name {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .table-area {
            padding: 0 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            color: #6b7280;
            padding: 15px 0;
            border-bottom: 2px solid #111827;
        }

        td {
            padding: 20px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            padding: 40px;
            gap: 50px;
        }

        .payment-info {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
        }

        .payment-info p {
            font-size: 13px;
            margin-bottom: 5px;
        }

        .total-box {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }

        .grand-total {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #111827;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .grand-total span {
            font-size: 18px;
            font-weight: 800;
            color: #111827;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: 0.2s;
        }

        .btn-dark {
            background: #111827;
            color: white;
        }

        .btn-light {
            color: #6b7280;
        }

        .btn:hover {
            opacity: 0.8;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .invoice-card {
                box-shadow: none;
                border: 1px solid #eee;
                width: 100%;
                max-width: none;
            }

            .controls {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-card">
        <div class="accent-bar"></div>

        <div class="header">
            <div class="brand">
                <h1>Nusantara PKT</h1>
                <p>Studio Produksi Kerajinan</p>
            </div>
            <div style="text-align: right;">
                <div class="status-badge">{{ $data->status_pembayaran }}</div>
                <p style="font-size: 14px; font-weight: 700;">
                    INV/{{ date('Ymd', strtotime($data->tanggal)) }}/{{ $data->id }}</p>
                <p style="font-size: 12px; color: #6b7280;">Tanggal: {{ date('d F Y', strtotime($data->tanggal)) }}</p>
            </div>
        </div>

        <div class="info-grid">
            <div>
                <span class="info-label">Pelanggan</span>
                <p class="customer-name">{{ $data->pelanggan->nama_pelanggan }}</p>
                <p class="info-value" style="color: #6b7280; margin-top: 5px;">{{ $data->pelanggan->alamat_pelanggan }}
                </p>
            </div>
            <div style="text-align: right;">
                <span class="info-label">Metode Pembayaran</span>
                <p class="info-value">Transfer Bank (BCA)</p>
            </div>
        </div>

        <div class="table-area">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50%;">Item</th>
                        <th class="text-center">Kuantitas</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->detailPenjualan as $item)
                        <tr>
                            <td>
                                <div style="font-weight: 700;">{{ $item->produk->nama_produk }}</div>
                                <div style="font-size: 11px; color: #9ca3af;">Produk Handmade Premium</div>
                            </td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-right" style="font-weight: 700;">Rp
                                {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer-grid">
            <div class="payment-info">
                <span class="info-label">Instruksi Pembayaran</span>
                <p><strong>Bank BCA</strong></p>
                <p style="font-family: monospace; font-size: 16px; font-weight: 700;">8829 1002 991</p>
                <p style="font-size: 12px; color: #6b7280;">a.n Nusantara PKT</p>
            </div>
            <div class="total-box">
                <div class="total-row">
                    <span style="color: #6b7280;">Subtotal</span>
                    <span>Rp {{ number_format($data->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="total-row">
                    <span style="color: #6b7280;">Pajak (0%)</span>
                    <span>Rp 0</span>
                </div>
                <div class="grand-total">
                    <span style="font-size: 11px; text-transform: uppercase;">Total Bayar</span>
                    <span>Rp {{ number_format($data->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div
            style="padding: 20px 40px; background: #111827; color: #9ca3af; font-size: 10px; text-align: center; text-transform: uppercase; letter-spacing: 1px;">
            Terima kasih telah berbelanja di Nusantara PKT &bull; Dokumen ini Sah & Digital
        </div>
    </div>

</body>

</html>
