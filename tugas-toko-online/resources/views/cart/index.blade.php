@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    <section>
        <h2>Keranjang Belanja</h2>

        @if (empty($items))
            <p>Keranjang belanja Anda saat ini masih kosong.</p>
            <p><a href="{{ route('products.index') }}">&laquo; Kembali ke Daftar Barang</a></p>
        @else
            <p>Berikut adalah barang-barang yang ada di dalam keranjang belanja Anda:</p>

            <table border="1" cellpadding="8" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah Beli</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $index => $item)
                        <tr>
                            <td align="center">{{ $index + 1 }}</td>
                            <td align="center">
                                @if ($item['product']->gambar)
                                    <img src="{{ asset('images/products/' . $item['product']->gambar) }}" alt="{{ $item['product']->nama_barang }}" width="80" height="60">
                                @else
                                    <em>-</em>
                                @endif
                            </td>
                            <td align="center">{{ $item['product']->id_barang }}</td>
                            <td><strong>{{ $item['product']->nama_barang }}</strong></td>
                            <td align="right">Rp {{ number_format($item['product']->harga, 0, ',', '.') }}</td>
                            <td align="center">
                                <form action="{{ route('cart.update', $item['product']->id_barang) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="jumlah" value="{{ $item['jumlah'] }}" min="1" max="{{ $item['product']->stok }}" size="3" required>
                                    <button type="submit">Ubah</button>
                                    <br>
                                    <small>(Maksimal stok: {{ $item['product']->stok }})</small>
                                </form>
                            </td>
                            <td align="right"><strong>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</strong></td>
                            <td align="center">
                                <form action="{{ route('cart.remove', $item['product']->id_barang) }}" method="POST" onsubmit="return confirm('Hapus barang ini dari keranjang?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" align="right">Total Harga:</th>
                        <th align="right">Rp {{ number_format($totalHarga, 0, ',', '.') }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

            <br>

            <div>
                <form action="{{ route('cart.clear') }}" method="POST" style="display: inline;" onsubmit="return confirm('Kosongkan seluruh isi keranjang?');">
                    @csrf
                    <button type="submit">Kosongkan Keranjang</button>
                </form>
                |
                <a href="{{ route('products.index') }}">Lanjut Pilih Barang</a>
                |
                <a href="{{ route('checkout.index') }}"><strong>Lanjut ke Checkout &raquo;</strong></a>
            </div>
        @endif
    </section>
@endsection
