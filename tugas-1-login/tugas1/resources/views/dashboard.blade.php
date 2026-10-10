<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>

<body>
    <h2>Dashboard</h2>

    <form action="{{ url('/logout') }}" method="POST">
        @csrf
        <button type="submit">[ Logout ]</button>
    </form>

    <hr>

    <p>Selamat datang, {{ Auth::user()->nama_lengkap }}!</p>
    <p>Halaman ini hanya bisa dibuka setelah login.</p>
    <p>Username: {{ Auth::user()->username }}</p>
</body>

</html>