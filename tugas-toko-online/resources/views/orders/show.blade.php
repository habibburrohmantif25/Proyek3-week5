@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->id_order)

@section('content')
    <section>
        <h2>Detail Pesanan: {{ $order->id_order }}</h2>
        <p><a href="{{ route('orders.index') }}">&laquo; Kembali ke Riwayat Pesanan</a></p>

        <fieldset>
            <legend>Informasi Pesanan</legend>
            <table cellpadding="4" cellspacing="0">
                <tr>
                    <td><strong>ID Pesanan</strong></td>
                    <td>: {{ $order->id_order }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal &amp; Waktu</strong></td>
                    <td>: {{ $order->tanggal_order->format('d F Y, H:i:s') }}</td>
                </tr>
                <tr>
                    <td><strong>Nama Pembeli</strong></td>
                    <td>: {{ $order->user->nama_lengkap }} ({{ $order->user->username }})</td>
                </tr>
                <tr>
                    <td><strong>Nomor Telepon</strong></td>
                    <td>: {{ $order->user->no_hp }}</td>
                </tr>
                <tr>
                    <td><strong>Alamat Pengiriman</strong></td>
                    <td>: {{ $order->alamat_pengiriman }}</td>
                </tr>
            </table>
        </fieldset>

        <br>

        <h3>Rincian Barang yang Dibeli</h3>
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga Saat Beli (Arsip)</th>
                    <th>Jumlah Dibeli</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderDetails as $index => $detail)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td align="center">
                            @if ($detail->product && $detail->product->gambar)
                                <img src="{{ asset('images/products/' . $detail->product->gambar) }}" alt="{{ $detail->product->nama_barang }}" width="80" height="60">
                            @else
                                <em>-</em>
                            @endif
                        </td>
                        <td align="center">{{ $detail->id_barang }}</td>
                        <td>{{ $detail->product ? $detail->product->nama_barang : 'Barang tidak ditemukan' }}</td>
                        <td align="right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td align="center">{{ $detail->jumlah_beli }}</td>
                        <td align="right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6" align="right">Total Bayar:</th>
                    <th align="right">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <br>
        <p>
            <a href="{{ route('orders.index') }}">&laquo; Kembali ke Riwayat Pesanan</a> |
            <a href="{{ route('products.index') }}">Kembali ke Daftar Barang</a>
        </p>
    </section>
@endsection
