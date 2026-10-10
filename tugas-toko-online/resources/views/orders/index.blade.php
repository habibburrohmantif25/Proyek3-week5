@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
    <section>
        <h2>Riwayat Pesanan Anda</h2>

        @if ($orders->isEmpty())
            <p>Anda belum memiliki riwayat pesanan.</p>
            <p><a href="{{ route('products.index') }}">Mulai belanja sekarang &raquo;</a></p>
        @else
            <table border="1" cellpadding="8" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pesanan</th>
                        <th>Waktu Pesanan</th>
                        <th>Total Bayar</th>
                        <th>Alamat Pengiriman</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $index => $order)
                        <tr>
                            <td align="center">{{ $index + 1 }}</td>
                            <td align="center"><strong>{{ $order->id_order }}</strong></td>
                            <td align="center">{{ $order->tanggal_order->format('d/m/Y H:i') }}</td>
                            <td align="right">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td>{{ $order->alamat_pengiriman }}</td>
                            <td align="center">
                                <a href="{{ route('orders.show', $order->id_order) }}">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>
@endsection
