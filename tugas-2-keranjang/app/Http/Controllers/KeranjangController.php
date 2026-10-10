<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    private function getCart(Request $request): array
    {
        $raw = $request->cookie('keranjang');
        if (!$raw) {
            return [];
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return [];
        }

        return $decoded;
    }

    public function index(Request $request)
    {
        $daftarBarang = Barang::all();
        $cart = $this->getCart($request);
        $totalJumlah = array_sum($cart);

        return view('index', compact('daftarBarang', 'totalJumlah'));
    }

    public function keranjang(Request $request)
    {
        $cart = $this->getCart($request);
        $items = [];
        $total = 0;

        if (!empty($cart)) {
            $ids = array_keys($cart);
            $daftarBarang = Barang::whereIn('id', $ids)->get()->keyBy('id');

            foreach ($cart as $id => $jumlah) {
                if (isset($daftarBarang[$id])) {
                    $barang = $daftarBarang[$id];
                    $qty = (int) $jumlah;
                    $subtotal = $barang->harga * $qty;
                    $total += $subtotal;

                    $items[] = [
                        'barang' => $barang,
                        'jumlah' => $qty,
                        'subtotal' => $subtotal,
                    ];
                }
            }
        }

        return view('keranjang', compact('items', 'total'));
    }

    public function tambah($id, Request $request)
    {
        $cart = $this->getCart($request);
        $barang = Barang::find($id);

        if ($barang) {
            $idStr = (string) $id;
            if (isset($cart[$idStr])) {
                $cart[$idStr] += 1;
            } else {
                $cart[$idStr] = 1;
            }
        }

        return redirect()->route('daftar_barang')->withCookie(cookie('keranjang', json_encode($cart), 60 * 24 * 7));
    }

    public function tambahQty($id, Request $request)
    {
        $cart = $this->getCart($request);
        $idStr = (string) $id;

        if (isset($cart[$idStr])) {
            $cart[$idStr] += 1;
        }

        return redirect()->route('keranjang')->withCookie(cookie('keranjang', json_encode($cart), 60 * 24 * 7));
    }

    public function kurangQty($id, Request $request)
    {
        $cart = $this->getCart($request);
        $idStr = (string) $id;

        if (isset($cart[$idStr])) {
            $cart[$idStr] -= 1;
            if ($cart[$idStr] <= 0) {
                unset($cart[$idStr]);
            }
        }

        return redirect()->route('keranjang')->withCookie(cookie('keranjang', json_encode($cart), 60 * 24 * 7));
    }

    public function hapusItem($id, Request $request)
    {
        $cart = $this->getCart($request);
        $idStr = (string) $id;

        if (isset($cart[$idStr])) {
            unset($cart[$idStr]);
        }

        return redirect()->route('keranjang')->withCookie(cookie('keranjang', json_encode($cart), 60 * 24 * 7));
    }

    public function kosongkan()
    {
        return redirect()->route('keranjang')->withCookie(cookie()->forget('keranjang'));
    }
}
