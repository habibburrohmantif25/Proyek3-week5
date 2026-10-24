<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Toko Online</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            margin: 0;
            padding: 2rem;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        .user-badge {
            background-color: #e2e8f0;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .user-badge.admin { background-color: #fef08a; color: #854d0e; }
        .user-badge.staff { background-color: #fed7aa; color: #9a3412; }
        .user-badge.customer { background-color: #bbf7d0; color: #166534; }
        .alert-success {
            background-color: #dcfce7;
            border: 1px solid #22c55e;
            color: #15803d;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
        .alert-error {
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            color: #b91c1c;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
        .card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            text-align: left;
            padding: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background-color: #f1f5f9;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .action-form {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        select {
            padding: 0.35rem 0.5rem;
            border: 1px solid #94a3b8;
            border-radius: 4px;
        }
        button.btn-primary {
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
        }
        button.btn-primary:hover {
            background-color: #1d4ed8;
        }
        button.btn-secondary {
            background-color: #64748b;
            color: #ffffff;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            cursor: pointer;
        }
        button.btn-secondary:hover {
            background-color: #475569;
        }
        .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .nav-links a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <div>
                <h1 style="margin: 0; font-size: 1.5rem;">Dashboard Aplikasi</h1>
                <p style="margin: 0.25rem 0 0; color: #64748b;">
                    Pengguna: <strong>{{ $user->nama_lengkap ?? $user->username }}</strong> 
                    ({{ $user->username }})
                    <span class="user-badge {{ $user->role }}">{{ $user->role }}</span>
                </p>
            </div>
            <div class="nav-links">
                <a href="{{ route('shop.index') }}">Ke Toko</a>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-secondary">Keluar</button>
                </form>
            </div>
        </header>

        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="card">
            <h2 style="margin-top: 0; font-size: 1.25rem;">Informasi Akun</h2>
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Nama Lengkap:</strong> {{ $user->nama_lengkap ?? '-' }}</p>
            <p><strong>Username:</strong> {{ $user->username }}</p>
            <p><strong>Role Saat Ini:</strong> {{ $user->role }}</p>
        </div>

        @can('manage-roles')
            <div class="card">
                <h2 style="margin-top: 0; font-size: 1.25rem;">Manajemen Role Pengguna (Admin Only)</h2>
                <p style="color: #64748b; font-size: 0.9rem;">
                    Berdasarkan BR-03 dan BR-04, admin hanya dapat menukar role antara <code>customer</code> dan <code>staff</code>.
                    Akun admin serta akun diri sendiri dilindungi dan tidak dapat diubah di antarmuka ini.
                </p>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Role Saat Ini</th>
                            <th>Aksi Ubah Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $u)
                            <tr>
                                <td>{{ $u->id }}</td>
                                <td>{{ $u->username }}</td>
                                <td>{{ $u->nama_lengkap ?? '-' }}</td>
                                <td>
                                    <span class="user-badge {{ $u->role }}">{{ $u->role }}</span>
                                </td>
                                <td>
                                    @if ($u->role === 'admin' || $user->is($u))
                                        <span style="color: #94a3b8; font-size: 0.85rem;">
                                            {{ $user->is($u) ? '(Akun Anda Sendiri)' : '(Akun Admin Tetap)' }}
                                        </span>
                                    @else
                                        <form action="{{ route('admin.users.role.update', $u) }}" method="POST" class="action-form">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" aria-label="Pilih role untuk {{ $u->username }}">
                                                <option value="customer" {{ $u->role === 'customer' ? 'selected' : '' }}>customer</option>
                                                <option value="staff" {{ $u->role === 'staff' ? 'selected' : '' }}>staff</option>
                                            </select>
                                            <button type="submit" class="btn-primary">Simpan</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card">
                <h2 style="margin-top: 0; font-size: 1.25rem;">Akses Role</h2>
                <p>Anda login sebagai <strong>{{ $user->role }}</strong>. Menu administrasi role hanya dapat diakses oleh akun dengan role <code>admin</code>.</p>
                @if ($user->role === 'customer')
                    <p style="color: #166534;">Anda memiliki hak akses untuk menggunakan keranjang belanja stateless di toko online.</p>
                @elseif ($user->role === 'staff')
                    <p style="color: #9a3412;">Sebagai staff, hak akses keranjang dibatasi. Anda memiliki izin melihat dan mengelola stok.</p>
                @endif
            </div>
        @endcan
    </div>
</body>
</html>
