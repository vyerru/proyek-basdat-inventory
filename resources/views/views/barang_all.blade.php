<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>View Semua Barang</title>
    <style> /* Styling sederhana */
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Data Semua Barang (Aktif & Tidak Aktif)</h1>
    <p><a href="{{ route('home') }}">Kembali ke Home</a></p>

    @if (count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Jenis</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->idbarang }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->nama_satuan }}</td>
                        <td>{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>{{ $item->jenis_barang }}</td>
                        <td>{{ $item->status_barang }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada data.</p>
    @endif
</body>
</html>