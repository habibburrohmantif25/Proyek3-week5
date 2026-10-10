<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Online') - Program Toko Online</title>
</head>
<body>
    <header>
        <h1>Program Toko Online</h1>
        <nav>
            <p>
                <a href="{{ route('products.index') }}">Daftar Barang</a>
                @auth
                    |
                    <a href="{{ route('cart.index') }}">
                        Keranjang Belanja ({{ array_sum(session('cart', [])) }})
                    </a>
                    |
                    <a href="{{ route('orders.index') }}">Riwayat Pesanan</a>
                    |
                    <span>Masuk sebagai: <strong>{{ Auth::user()->nama_lengkap }}</strong> ({{ Auth::user()->username }})</span>
                    |
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    |
                    <a href="{{ route('login') }}">Login</a>
                @endauth
            </p>
        </nav>
    </header>

    <hr>

    @if (session('success'))
        <div>
            <p><strong>[SUKSES]</strong> {{ session('success') }}</p>
        </div>
        <hr>
    @endif

    @if (session('error'))
        <div>
            <p><strong>[PERINGATAN]</strong> {{ session('error') }}</p>
        </div>
        <hr>
    @endif

    @if ($errors->any())
        <div>
            <p><strong>[TERJADI KESALAHAN]</strong></p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <hr>
    @endif

    <main>
        @yield('content')
    </main>

    <hr>
    <footer>
        <p><small>Tugas Pemrograman Web: Program Toko Online berbasis Laravel dan MySQL</small></p>
    </footer>
</body>
</html>
