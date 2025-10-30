<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>View Semua Role</title>
     <style> /* Styling sederhana */
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Data Semua Role</h1>
     <p><a href="{{ route('home') }}">Kembali ke Home</a></p>

    @if (count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th>ID Role</th>
                    <th>Nama Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->idrole }}</td>
                        <td>{{ $item->nama_role }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada data role.</p>
    @endif
</body>
</html>