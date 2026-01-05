<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\LandingPageController;

Route::get('/', [LandingPageController::class, 'index'])->name('landing.page');
Route::post('/pesan', [LandingPageController::class, 'pesan'])->name('pesan.store');
Route::post('/add-to-cart', [LandingPageController::class, 'addToCart'])->name('add.to.cart');
Route::post('/remove-from-cart', [LandingPageController::class, 'removeFromCart'])->name('remove.from.cart');
Route::get('/success/{id}', [LandingPageController::class, 'success'])->name('landing.success');
Route::get('/track-order', [LandingPageController::class, 'trackOrder'])->name('order.track');
Route::get('/invoice/{penjualan}', [PdfController::class, 'cetakInvoice'])->name('cetak.invoice');
Route::get('/download-kwitansi/{id}', [LandingPageController::class, 'downloadKwitansi'])->name('kwitansi.download');
