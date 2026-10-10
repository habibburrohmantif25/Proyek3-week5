<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Toko Alat Tulis</title>
</head>
<body>
    <table border="1" cellpadding="8" cellspacing="0" width="500">
        <tr>
            <td>
                <strong>Toko Alat Tulis</strong>
            </td>
            <td align="right">
                <a href="{{ route('keranjang') }}">[ Keranjang ({{ $totalJumlah }}) ]</a>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p><strong>Daftar barang</strong></p>
                <table cellpadding="6" cellspacing="0" width="100%">
                    @foreach ($daftarBarang as $barang)
                    <tr>
                        <td width="50">[img]</td>
                        <td>{{ $barang->nama }}</td>
                        <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                        <td align="right">
                            <form action="{{ route('keranjang.tambah', $barang->id) }}" method="POST">
                                @csrf
                                <button type="submit">[Masukkan ke krj]</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
