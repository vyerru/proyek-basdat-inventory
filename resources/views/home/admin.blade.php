<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
</head>
<body>
    <h1>Selamat Datang, {{ $user->username }}! (Admin)</h1>
    <p>Ini adalah halaman dashboard Admin.</p>

     <p><a href="{{ route('atk.index') }}">Lihat Data ATK</a></p>
     <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>