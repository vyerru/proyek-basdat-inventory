<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data ATK</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Data Alat Tulis Kantor (ATK)</h1>
    <p><a href="{{ route('home') }}">Kembali ke Home</a></p>

    @if (count($atkData) > 0)
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
                @foreach ($atkData as $item)
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
        <p>Tidak ada data ATK.</p>
    @endif

</body>
</html>