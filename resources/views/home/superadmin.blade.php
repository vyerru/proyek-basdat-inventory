<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Admin</title>
</head>
<body>
    <h1>Selamat Datang, {{ $user->username }}! (Super Admin)</h1>
    <p>Ini adalah halaman dashboard khusus Super Admin.</p>

    <p><a href="{{ route('atk.index') }}">Lihat Data ATK</a></p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>