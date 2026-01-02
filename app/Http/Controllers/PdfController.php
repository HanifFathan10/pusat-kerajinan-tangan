<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class PdfController extends Controller
{
    public function cetakInvoice(Penjualan $penjualan)
    {
        $pdf = FacadePdf::loadView('pdf.invoice', ['data' => $penjualan]);
        return $pdf->stream();
    }
}
