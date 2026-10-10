<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
</head>
<body>
    <table border="1" cellpadding="8" cellspacing="0" width="500">
        <tr>
            <td>
                <strong>Keranjang belanja</strong>
            </td>
            <td align="right">
                Tanpa login
            </td>
        </tr>
        <tr>
            <td colspan="2">
                @if (empty($items))
                    <p>Keranjang belanja kosong.</p>
                @else
                    <table cellpadding="4" cellspacing="0" width="100%">
                        @foreach ($items as $item)
                        <tr>
                            <td colspan="5">
                                <strong>{{ $item['barang']->nama }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Rp {{ number_format($item['barang']->harga, 0, ',', '.') }} x {{ $item['jumlah'] }} = Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </td>
                            <td width="30" align="center">
                                <form action="{{ route('keranjang.kurang', $item['barang']->id) }}" method="POST">
                                    @csrf
                                    <button type="submit">[-]</button>
                                </form>
                            </td>
                            <td width="30" align="center">
                                {{ $item['jumlah'] }}
                            </td>
                            <td width="30" align="center">
                                <form action="{{ route('keranjang.tambah_qty', $item['barang']->id) }}" method="POST">
                                    @csrf
                                    <button type="submit">[+]</button>
                                </form>
                            </td>
                            <td width="40" align="center">
                                <form action="{{ route('keranjang.hapus', $item['barang']->id) }}" method="POST">
                                    @csrf
                                    <button type="submit">[hps]</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    <hr>
                    <table cellpadding="4" cellspacing="0" width="100%">
                        <tr>
                            <td>
                                <strong>Total</strong>
                            </td>
                            <td align="right">
                                <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <form action="{{ route('keranjang.kosongkan') }}" method="POST">
                        @csrf
                        <button type="submit">[ Kosongkan keranjang ]</button>
                    </form>
                @endif
                <br>
                <a href="{{ route('daftar_barang') }}">[ Kembali ke daftar barang ]</a>
            </td>
        </tr>
    </table>
</body>
</html>
