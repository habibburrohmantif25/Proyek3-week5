<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $items = [];
        $totalHarga = 0;

        if (!empty($cart)) {
            $products = Product::whereIn('id_barang', array_keys($cart))->get()->keyBy('id_barang');

            foreach ($cart as $idBarang => $jumlah) {
                if (isset($products[$idBarang])) {
                    $product = $products[$idBarang];
                    $subtotal = $product->harga * $jumlah;
                    $totalHarga += $subtotal;

                    $items[] = [
                        'product' => $product,
                        'jumlah' => $jumlah,
                        'subtotal' => $subtotal,
                    ];
                }
            }
        }

        return view('cart.index', compact('items', 'totalHarga'));
    }

    public function add(Request $request, string $id_barang)
    {
        $product = Product::findOrFail($id_barang);

        if ($product->stok <= 0) {
            return back()->with('error', 'Barang dengan stok 0 tidak dapat dibeli.');
        }

        $addQty = (int) $request->input('jumlah', 1);
        if ($addQty < 1) {
            $addQty = 1;
        }

        $cart = session()->get('cart', []);
        $currentQty = $cart[$id_barang] ?? 0;
        $targetQty = $currentQty + $addQty;

        if ($targetQty > $product->stok) {
            return back()->with(
                'error',
                'Jumlah yang dibeli tidak boleh melebihi stok yang tersedia (Sisa stok: ' . $product->stok . ').'
            );
        }

        $cart[$id_barang] = $targetQty;
        session()->put('cart', $cart);

        return back()->with('success', $product->nama_barang . ' berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, string $id_barang)
    {
        $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($id_barang);
        $newQty = (int) $request->input('jumlah');

        if ($newQty > $product->stok) {
            return back()->with(
                'error',
                'Jumlah untuk ' . $product->nama_barang . ' melebihi stok yang tersedia (Sisa stok: ' . $product->stok . ').'
            );
        }

        $cart = session()->get('cart', []);
        $cart[$id_barang] = $newQty;
        session()->put('cart', $cart);

        return back()->with('success', 'Jumlah barang berhasil diperbarui.');
    }

    public function remove(string $id_barang)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id_barang])) {
            unset($cart[$id_barang]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Barang berhasil dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Keranjang belanja telah dikosongkan.');
    }
}
