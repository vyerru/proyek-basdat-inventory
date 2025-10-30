<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    {{-- Tambahkan link CSS jika perlu --}}
</head>
<body>
    <h1>Selamat Datang, {{ $user->username }}! (Role: Admin)</h1>
    <p>Ini adalah halaman dashboard khusus Admin.</p>

    <h2>Akses Data Aktif:</h2>
    <ul>
        <li><a href="{{ route('view.barang.active') }}">Lihat Barang Aktif</a></li>
        <li><a href="{{ route('view.satuan.active') }}">Lihat Satuan Aktif</a></li>
        <li><a href="{{ route('view.vendor.active') }}">Lihat Vendor Aktif</a></li>
        <li><a href="{{ route('view.margin_penjualan.active') }}">Lihat Margin Penjualan Aktif</a></li>
    </ul>

     <form method="POST" action="{{ route('logout') }}" style="margin-top: 20px;">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>