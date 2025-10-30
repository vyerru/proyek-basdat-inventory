<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>View Semua Vendor</title>
     <style> /* Styling sederhana */
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Data Semua Vendor (Aktif & Tidak Aktif)</h1>
     <p><a href="{{ route('home') }}">Kembali ke Home</a></p>

    @if (count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th>ID Vendor</th>
                    <th>Nama Vendor</th>
                    <th>Badan Hukum</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->idvendor }}</td>
                        <td>{{ $item->nama_vendor }}</td>
                        <td>{{ $item->status_badan_hukum }}</td>
                        <td>{{ $item->status_vendor }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada data vendor.</p>
    @endif
</body>
</html>