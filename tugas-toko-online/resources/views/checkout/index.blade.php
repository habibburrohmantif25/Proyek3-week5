@extends('layouts.app')

@section('title', 'Checkout Pesanan')

@section('content')
    <section>
        <h2>Checkout Pesanan</h2>
        <p>Silakan periksa kembali daftar barang pesanan Anda dan konfirmasikan alamat tujuan pengiriman sebelum menyelesaikan transaksi.</p>

        <h3>Rincian Pembelian</h3>
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah Beli</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $item)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td align="center">{{ $item['product']->id_barang }}</td>
                        <td>{{ $item['product']->nama_barang }}</td>
                        <td align="right">Rp {{ number_format($item['product']->harga, 0, ',', '.') }}</td>
                        <td align="center">{{ $item['jumlah'] }}</td>
                        <td align="right">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" align="right">Total Harga Barang:</th>
                    <th align="right">Rp {{ number_format($totalHarga, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="5" align="right">Ongkos Kirim (Diabaikan):</th>
                    <th align="right">Rp 0</th>
                </tr>
                <tr>
                    <th colspan="5" align="right">Total Bayar:</th>
                    <th align="right">Rp {{ number_format($totalHarga, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <br>

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <fieldset>
                <legend>Informasi Pengiriman</legend>
                <p>
                    <strong>Nama Pembeli:</strong> {{ $user->nama_lengkap }} ({{ $user->username }})
                </p>
                <p>
                    <strong>Nomor Telepon:</strong> {{ $user->no_hp }}
                </p>
                <p>
                    <label for="alamat_pengiriman"><strong>Alamat Pengiriman:</strong></label><br>
                    <small>Alamat di bawah terisi otomatis dari profil Anda dan dapat disesuaikan jika diperlukan.</small><br>
                    <textarea id="alamat_pengiriman" name="alamat_pengiriman" rows="4" cols="60" required>{{ old('alamat_pengiriman', $user->alamat) }}</textarea>
                </p>
            </fieldset>

            <br>

            <div>
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan pesanan ini?');">
                    <strong>Konfirmasi dan Selesaikan Pesanan</strong>
                </button>
                |
                <a href="{{ route('cart.index') }}">Kembali ke Keranjang</a>
            </div>
        </form>
    </section>
@endsection
