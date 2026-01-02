<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - {{ $data->periode_laporan->format('F Y') }}</title>
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #2d3436;
            line-height: 1.5;
            background-color: #ffffff;
        }

        /* Header / Kop Surat Professional */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .brand-section {
            width: 60%;
        }

        .company-name {
            font-size: 24px;
            font-weight: 800;
            color: #2c3e50;
            letter-spacing: -1px;
            margin: 0;
        }

        .company-tagline {
            font-size: 10px;
            color: #b87333;
            /* Warna Copper/Tembaga sesuai tema artisan */
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .report-info {
            width: 40%;
            text-align: right;
            vertical-align: bottom;
        }

        .report-title {
            font-size: 18px;
            font-weight: bold;
            color: #636e72;
            margin: 0;
        }

        .report-meta {
            color: #2c3e50;
            font-weight: bold;
            font-size: 12px;
        }

        /* Summary Cards */
        .summary-container {
            width: 100%;
            margin-bottom: 30px;
        }

        .summary-card {
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .bg-revenue {
            background-color: #f1fcf4;
            border: 1px solid #d4edda;
        }

        .bg-expense {
            background-color: #fff5f5;
            border: 1px solid #feb2b2;
        }

        .bg-profit {
            background-color: #2c3e50;
            color: white;
        }

        /* Financial Table */
        .financial-table {
            width: 100%;
            border-collapse: collapse;
        }

        .financial-table th {
            text-align: left;
            padding: 12px;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            text-transform: uppercase;
            font-size: 10px;
            color: #636e72;
        }

        .financial-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #f1f1f1;
        }

        .group-header {
            background-color: #fdfdfd;
            font-weight: bold;
            color: #2c3e50;
        }

        .indent {
            padding-left: 30px !important;
            color: #636e72;
        }

        .row-total {
            font-weight: bold;
            background-color: #fafafa;
            border-top: 1px solid #2c3e50;
        }

        .grand-total {
            background-color: #2c3e50;
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
        }

        /* Typography & Colors */
        .text-right {
            text-align: right;
        }

        .text-success {
            color: #27ae60;
            font-weight: bold;
        }

        .text-danger {
            color: #e74c3c;
            font-weight: bold;
        }

        .currency {
            font-family: 'Courier', monospace;
            font-weight: bold;
        }

        /* Signatures */
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }

        .sig-box {
            text-align: center;
            width: 33.3%;
        }

        .sig-line {
            margin-top: 60px;
            border-top: 1px solid #2d3436;
            width: 150px;
            margin-left: auto;
            margin-right: auto;
        }

        .sig-name {
            font-weight: bold;
            margin-top: 5px;
        }

        .sig-role {
            font-size: 9px;
            color: #636e72;
        }
    </style>
</head>

<body>

    @php
        $start = $data->periode_laporan->copy()->startOfMonth();
        $end = $data->periode_laporan->copy()->endOfMonth();

        // Data Fetching
        $biayaBahan = \App\Models\PembelianBahanBaku::query()
            ->whereBetween('tanggal_beli', [$start, $end])
            ->sum('total_biaya');

        $biayaUpah = \App\Models\JadwalProduksi::query()
            ->whereBetween('tanggal_selesai', [$start, $end])
            ->where('status_produksi', 'selesai')
            ->sum('biaya_tenaga_kerja');

        $jmlTransaksi = \App\Models\Penjualan::query()
            ->whereBetween('tanggal', [$start, $end])
            ->where('status_pembayaran', 'lunas')
            ->count();
    @endphp

    <table class="header-table">
        <tr>
            <td class="brand-section">
                <p class="company-name">PUSAT KERAJINAN TANGAN</p>
                <p class="company-tagline">Heritage Craftsmanship Nusantara</p>
                <p style="margin-top: 10px;">
                    Jl. PT Inti No. 123, Bandung | (022) 1234-5678<br>
                    finance@pkt.com | www.pusat-kerajinan-tangan.com
                </p>
            </td>
            <td class="report-info">
                <p class="report-title">LAPORAN LABA RUGI</p>
                <p class="report-meta italic">Periode Efektif: {{ $data->periode_laporan->format('F Y') }}</p>
                <p style="font-size: 9px; color: #b2bec3;">Dokumen ID: #FIN-{{ $data->id }}-{{ date('Ymd') }}</p>
            </td>
        </tr>
    </table>

    <table class="summary-container">
        <tr>
            <td width="32%">
                <div class="summary-card bg-revenue">
                    <span style="font-size: 9px; text-transform: uppercase;">Total Omset</span><br>
                    <span class="text-success" style="font-size: 14px;">Rp
                        {{ number_format($data->total_pendapatan, 0, ',', '.') }}</span>
                </div>
            </td>
            <td width="2%"></td>
            <td width="32%">
                <div class="summary-card bg-expense">
                    <span style="font-size: 9px; text-transform: uppercase;">Total Pengeluaran</span><br>
                    <span class="text-danger" style="font-size: 14px;">Rp
                        {{ number_format($data->total_pengeluaran, 0, ',', '.') }}</span>
                </div>
            </td>
            <td width="2%"></td>
            <td width="32%">
                <div class="summary-card bg-profit">
                    <span style="font-size: 9px; text-transform: uppercase;">Laba Bersih</span><br>
                    <span style="font-size: 14px;">Rp {{ number_format($data->laba_rugi, 0, ',', '.') }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="financial-table">
        <thead>
            <tr>
                <th>Deskripsi Transaksi</th>
                <th class="text-right">Nilai (IDR)</th>
            </tr>
        </thead>
        <tbody>
            <tr class="group-header">
                <td>PENDAPATAN OPERASIONAL</td>
                <td></td>
            </tr>
            <tr>
                <td class="indent">Penjualan Produk Kerajinan ({{ $jmlTransaksi }} Invoice)</td>
                <td class="text-right currency">Rp {{ number_format($data->total_pendapatan, 0, ',', '.') }}</td>
            </tr>
            <tr class="row-total">
                <td>TOTAL PENDAPATAN</td>
                <td class="text-right currency text-success">Rp
                    {{ number_format($data->total_pendapatan, 0, ',', '.') }}</td>
            </tr>

            <tr>
                <td colspan="2" style="border:none; height: 15px;"></td>
            </tr>

            <tr class="group-header">
                <td>BEBAN POKOK PRODUKSI (COGS)</td>
                <td></td>
            </tr>
            <tr>
                <td class="indent">Pembelian Bahan Baku & Material</td>
                <td class="text-right currency">Rp {{ number_format($biayaBahan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="indent">Beban Upah Pengrajin & Tenaga Kerja</td>
                <td class="text-right currency">Rp {{ number_format($biayaUpah, 0, ',', '.') }}</td>
            </tr>
            @php $lainnya = $data->total_pengeluaran - ($biayaBahan + $biayaUpah); @endphp
            @if ($lainnya > 0)
                <tr>
                    <td class="indent">Biaya Operasional Lainnya</td>
                    <td class="text-right currency">Rp {{ number_format($lainnya, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="row-total">
                <td>TOTAL PENGELUARAN</td>
                <td class="text-right currency text-danger">(Rp
                    {{ number_format($data->total_pengeluaran, 0, ',', '.') }})</td>
            </tr>

            <tr>
                <td colspan="2" style="border:none; height: 25px;"></td>
            </tr>

            <tr class="grand-total">
                <td style="padding: 15px;">LABA (RUGI) BERSIH PERIODE INI</td>
                <td class="text-right" style="padding: 15px;">
                    Rp {{ number_format($data->laba_rugi, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <table class="signature-section">
        <tr>
            <td class="sig-box">
                <p>Dibuat Oleh,</p>
                <div class="sig-line"></div>
                <p class="sig-name">{{ $data->timKeuangan->nama_pegawai ?? 'Staff Keuangan' }}</p>
                <p class="sig-role">Finance Department</p>
            </td>
            <td class="sig-box">
                <p>Diperiksa Oleh,</p>
                <div class="sig-line"></div>
                <p class="sig-name">Haniep Fathan Riziq</p>
                <p class="sig-role">Finance Manager</p>
            </td>
            <td class="sig-box">
                <p>Disetujui Oleh,</p>
                <div class="sig-line"></div>
                <p class="sig-name">Admin PKT</p>
                <p class="sig-role">Executive Director</p>
            </td>
        </tr>
    </table>

    <div style="margin-top: 50px; text-align: center; border-top: 1px solid #eee; padding-top: 10px;">
        <p style="font-size: 8px; color: #95a5a6; font-style: italic;">
            Laporan ini dihasilkan secara otomatis oleh Sistem Informasi Akuntansi PKT pada
            {{ now()->format('d/m/Y H:i:s') }}.<br>
            Segala bentuk manipulasi data dalam laporan ini merupakan pelanggaran hukum.
        </p>
    </div>

</body>

</html>
