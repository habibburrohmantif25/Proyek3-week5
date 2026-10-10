<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja masih kosong.');
        }

        $products = Product::whereIn('id_barang', array_keys($cart))->get()->keyBy('id_barang');
        $items = [];
        $totalHarga = 0;

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

        if (empty($items)) {
            session()->forget('cart');
            return redirect()->route('cart.index')->with('error', 'Barang di keranjang tidak valid.');
        }

        $user = Auth::user();

        return view('checkout.index', compact('items', 'totalHarga', 'user'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'alamat_pengiriman' => ['required', 'string'],
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        try {
            $order = DB::transaction(function () use ($request, $cart) {
                $totalHarga = 0;
                $itemsToSave = [];

                foreach ($cart as $idBarang => $jumlah) {
                    $product = Product::where('id_barang', $idBarang)->lockForUpdate()->first();

                    if (!$product) {
                        throw new Exception("Barang dengan kode {$idBarang} tidak ditemukan.");
                    }

                    if ($product->stok < $jumlah) {
                        throw new Exception(
                            "Stok barang '{$product->nama_barang}' tidak mencukupi (Tersedia: {$product->stok}, diminta: {$jumlah})."
                        );
                    }

                    $subtotal = $product->harga * $jumlah;
                    $totalHarga += $subtotal;

                    $itemsToSave[] = [
                        'product' => $product,
                        'jumlah' => $jumlah,
                        'harga_satuan' => $product->harga,
                    ];
                }

                do {
                    $orderId = 'ORD' . date('ymd') . sprintf('%04d', mt_rand(1, 9999));
                } while (Order::where('id_order', $orderId)->exists());

                $user = Auth::user();

                $newOrder = Order::create([
                    'id_order' => $orderId,
                    'id_user' => $user->id_user,
                    'tanggal_order' => now(),
                    'total_harga' => $totalHarga,
                    'alamat_pengiriman' => $request->alamat_pengiriman,
                ]);

                foreach ($itemsToSave as $item) {
                    OrderDetail::create([
                        'id_order' => $orderId,
                        'id_barang' => $item['product']->id_barang,
                        'harga_satuan' => $item['harga_satuan'],
                        'jumlah_beli' => $item['jumlah'],
                    ]);

                    $item['product']->decrement('stok', $item['jumlah']);
                }

                return $newOrder;
            });

            session()->forget('cart');

            return redirect()->route('orders.show', $order->id_order)
                ->with('success', 'Pembelian berhasil! Pesanan ' . $order->id_order . ' telah dibuat.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal memproses checkout: ' . $e->getMessage());
        }
    }
}
