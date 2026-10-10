@extends('layouts.app')

@section('title', 'Katalog Daftar Barang')

@section('content')
    <section>
        <h2>Katalog Daftar Barang</h2>
        <p>
            Selamat datang di toko online kami. Pengunjung dapat melihat daftar produk secara bebas.
            Untuk memasukkan barang ke keranjang dan melakukan pembelian, Anda wajib masuk terlebih dahulu.
        </p>

        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Deskripsi</th>
                    <th>Harga Satuan</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $index => $product)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td align="center">
                            @if ($product->gambar)
                                <img src="{{ asset('images/products/' . $product->gambar) }}" alt="{{ $product->nama_barang }}" width="120" height="90">
                            @else
                                <em>Tidak ada gambar</em>
                            @endif
                        </td>
                        <td align="center"><strong>{{ $product->id_barang }}</strong></td>
                        <td><strong>{{ $product->nama_barang }}</strong></td>
                        <td>{{ $product->deskripsi }}</td>
                        <td align="right">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                        <td align="center">
                            @if ($product->stok > 0)
                                {{ $product->stok }}
                            @else
                                <strong>Stok Habis (0)</strong>
                            @endif
                        </td>
                        <td align="center">
                            @if ($product->stok <= 0)
                                <button type="button" disabled>Stok Habis</button>
                            @else
                                @auth
                                    <form action="{{ route('cart.add', $product->id_barang) }}" method="POST">
                                        @csrf
                                        <label for="jumlah-{{ $product->id_barang }}">Jumlah:</label>
                                        <input type="number" id="jumlah-{{ $product->id_barang }}" name="jumlah" value="1" min="1" max="{{ $product->stok }}" size="3">
                                        <br><br>
                                        <button type="submit">Tambah ke Keranjang</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}">Login untuk Membeli</a>
                                @endauth
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" align="center">Belum ada data barang yang tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
