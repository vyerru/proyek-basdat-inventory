<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Admin</title>
    {{-- Tambahkan link CSS jika perlu --}}
</head>
<body>
    <h1>Selamat Datang, {{ $user->username }}! (Role: Super Admin)</h1>
    <p>Ini adalah halaman dashboard khusus Super Admin.</p>

    <h2>Akses Data Aktif:</h2>
    <ul>
        <li><a href="{{ route('view.barang.active') }}">Lihat Barang Aktif</a></li>
        <li><a href="{{ route('view.satuan.active') }}">Lihat Satuan Aktif</a></li>
        <li><a href="{{ route('view.vendor.active') }}">Lihat Vendor Aktif</a></li>
        <li><a href="{{ route('view.margin_penjualan.active') }}">Lihat Margin Penjualan Aktif</a></li>
    </ul>

    <h2>Akses Semua Data (Termasuk Non-Aktif):</h2>
     <ul>
        <li><a href="{{ route('view.barang.all') }}">Lihat Semua Barang</a></li>
        <li><a href="{{ route('view.satuan.all') }}">Lihat Semua Satuan</a></li>
        <li><a href="{{ route('view.vendor.all') }}">Lihat Semua Vendor</a></li>
        <li><a href="{{ route('view.margin_penjualan.all') }}">Lihat Semua Margin Penjualan</a></li>
    </ul>

     <h2>Manajemen User & Role:</h2>
     <ul>
         <li><a href="{{ route('view.user_role') }}">Lihat User dan Role</a></li>
         <li><a href="{{ route('view.role.all') }}">Lihat Semua Role</a></li>
         {{-- Tambahkan link untuk CRUD User/Role di sini nanti --}}
     </ul>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: 20px;">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>