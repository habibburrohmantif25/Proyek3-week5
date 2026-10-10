@extends('layouts.app')

@section('title', 'Login Pengguna')

@section('content')
    <section>
        <h2>Login Pengguna</h2>
        <p>Silakan masuk menggunakan username dan password Anda untuk dapat berbelanja dan melakukan checkout.</p>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <fieldset>
                <legend>Formulir Login</legend>
                <p>
                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus>
                </p>
                <p>
                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password" required>
                </p>
                <p>
                    <button type="submit">Masuk</button>
                </p>
            </fieldset>
        </form>

        <aside>
            <h3>Akun Pengujian (Demo):</h3>
            <ul>
                <li>Username: <strong>budi</strong> | Password: <strong>password123</strong></li>
                <li>Username: <strong>siti</strong> | Password: <strong>password123</strong></li>
            </ul>
        </aside>
    </section>
@endsection
