<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>View Margin Penjualan Aktif</title>
    <style> /* Styling sederhana */
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Data Margin Penjualan Aktif</h1>
     <p><a href="{{ route('home') }}">Kembali ke Home</a></p>

    @if (count($data) > 0)
        <table>
            <thead>
                 <tr>
                    <th>ID Margin</th>
                    <th>Persen (%)</th>
                    <th>Tanggal Dibuat</th>
                    <th>Tanggal Update</th>
                    <th>Dibuat Oleh</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->idmargin_penjualan }}</td>
                        <td>{{ $item->persen }}</td>
                        <td>{{ $item->tanggal_dibuat }}</td>
                        <td>{{ $item->tanggal_diupdate }}</td>
                        <td>{{ $item->dibuat_oleh }}</td>
                        <td>{{ $item->status_margin }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada data margin penjualan aktif.</p>
    @endif
</body>
</html>