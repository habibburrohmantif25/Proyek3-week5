<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('id_user', Auth::user()->id_user)
            ->orderBy('tanggal_order', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(string $id_order)
    {
        $order = Order::with(['orderDetails.product', 'user'])
            ->where('id_order', $id_order)
            ->where('id_user', Auth::user()->id_user)
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}
