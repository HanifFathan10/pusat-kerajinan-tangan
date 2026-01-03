<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query()->where('is_active', 1);

        if ($request->has('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->has('ready_stock')) {
            $query->where('stok_produk', '>', 0);
        }

        if ($request->has('min_price')) {
            $query->where('harga', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('harga', '<=', $request->max_price);
        }

        match ($request->sort) {
            'harga_asc' => $query->orderBy('harga', 'asc'),
            'harga_desc' => $query->orderBy('harga', 'desc'),
            default => $query->latest(),
        };

        $produks = $query->paginate(9)->withQueryString();

        $cart = session()->get('cart', []);
        $total = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        return view('landing', compact('produks', 'cart', 'total'));
    }

    public function getCartContent()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }


        return response()->json([
            'cart' => $cart,
            'cart_count' => count($cart),
            'total_formatted' => number_format($total, 0, ',', '.'),
            'total_raw' => $total
        ]);
    }

    public function trackOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required'
        ]);

        $cleanId = str_replace('#', '', $request->order_id);

        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.produk'])
            ->where('id', $cleanId)
            ->firstOrFail();

        if (!$penjualan) {
            return back()->with('error_tracking', 'ID Pesanan tidak ditemukan. Mohon periksa kembali.');
        }

        return view('tracking-result', compact('penjualan'));
    }

    public function addToCart(Request $request)
    {
        $produk = Produk::findOrFail($request->id);
        $cart = session()->get('cart', []);

        if (isset($cart[$produk->id])) {
            $cart[$produk->id]['quantity']++;
        } else {
            $cart[$produk->id] = [
                "id" => $produk->id,
                "name" => $produk->nama_produk,
                "quantity" => 1,
                "price" => $produk->harga,
                "image" => $produk->gambar_produk[0] ?? null
            ];
        }

        session()->put('cart', $cart);
        return response()->json(['status' => 'success', 'message' => 'Produk berhasil ditambahkan!', 'cart_count' => count($cart)]);
    }

    public function removeFromCart(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer|exists:produk,id'
            ]);

            $cart = session()->get('cart', []);

            if (isset($cart[$validated['id']])) {
                unset($cart[$validated['id']]);
                session()->put('cart', $cart);
                return response()->json(['status' => 'success', 'message' => 'Produk berhasil dihapus', 'cart_count' => count($cart)]);
            }

            return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan di keranjang'], 404);
        } catch (\Exception $e) {
            Log::error('Error removing from cart: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan saat menghapus produk'], 500);
        }
    }

    public function pesan(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja Anda kosong.');
        }

        $validated = $request->validate([
            'nama_pelanggan'    => 'required|string|max:255',
            'email_pelanggan'   => 'required|email|max:255',
            'telepon_pelanggan' => 'required|string|max:20',
            'alamat_pelanggan'  => 'required|string',
        ]);

        try {
            return DB::transaction(function () use ($validated, $cart) {
                $pelanggan = Pelanggan::updateOrCreate(
                    ['email_pelanggan' => $validated['email_pelanggan']],
                    $validated
                );

                $grandTotal = 0;
                foreach ($cart as $item) {
                    $grandTotal += $item['price'] * $item['quantity'];
                }

                $penjualan = Penjualan::create([
                    'tanggal'           => now(),
                    'total_harga'       => $grandTotal,
                    'status_pembayaran' => 'pending',
                    'status_verifikasi' => 'belum_terverifikasi',
                    'id_pelanggan'      => $pelanggan->id,
                    'catatan'           => $cart['catatan'] ?? null,
                ]);

                foreach ($cart as $id => $details) {
                    $produk = Produk::lockForUpdate()->find($id);

                    if ($produk && $produk->stok_produk >= $details['quantity']) {
                        DetailPenjualan::create([
                            'id_penjualan' => $penjualan->id,
                            'id_produk'    => $id,
                            'jumlah'       => $details['quantity'],
                            'harga_satuan' => $details['price'],
                            'sub_total'    => $details['price'] * $details['quantity']
                        ]);

                        $produk->decrement('stok_produk', $details['quantity']);
                    } else {
                        throw new \Exception("Stok produk {$details['name']} tidak mencukupi.");
                    }
                }

                session()->forget('cart');

                return redirect()->route('landing.success', $penjualan->id)
                    ->with('success', 'Pesanan berhasil dibuat!');
            });
        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    // LandingPageController.php
    public function success($id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.produk'])->findOrFail($id);
        return view('success', compact('penjualan'));
    }
}
