<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Online</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
        }
        .login-card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 2rem;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        h1 {
            font-size: 1.5rem;
            margin-top: 0;
            margin-bottom: 0.5rem;
        }
        p.subtitle {
            color: #475569;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.35rem;
            font-weight: 600;
            font-size: 0.9rem;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #94a3b8;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
        }
        input:focus {
            outline: 2px solid #2563eb;
            outline-offset: 1px;
        }
        button[type="submit"] {
            width: 100%;
            padding: 0.65rem;
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
        }
        button[type="submit"]:hover {
            background-color: #1d4ed8;
        }
        .error-box {
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .demo-accounts {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
            font-size: 0.85rem;
            color: #64748b;
        }
        .demo-accounts table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.5rem;
        }
        .demo-accounts td {
            padding: 0.2rem 0;
        }
        .back-link {
            display: inline-block;
            margin-top: 1rem;
            color: #2563eb;
            text-decoration: none;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>Login Toko</h1>
        <p class="subtitle">Masuk dengan kredensial pengguna Anda.</p>

        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Masuk</button>
        </form>

        <div class="demo-accounts">
            <strong>Daftar Akun Praktikum (Password: password123):</strong>
            <table>
                <tr><td>admin</td><td>Role: admin</td></tr>
                <tr><td>staff</td><td>Role: staff</td></tr>
                <tr><td>budi</td><td>Role: customer</td></tr>
                <tr><td>siti</td><td>Role: customer</td></tr>
            </table>
        </div>

        <a href="{{ route('shop.index') }}" class="back-link">Kembali ke Halaman Toko</a>
    </div>
</body>
</html>
