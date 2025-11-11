@extends('layout.app')
@section('title', 'Laporan Penjualan')
@section('page_title', 'Laporan Penjualan')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID Jual</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Kasir</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Harga Jual</th>
                        <th scope="col">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data_penjualan as $jual)
                    <tr>
                        <td>{{ $jual->idpenjualan }}</td>
                        <td>{{ \Carbon\Carbon::parse($jual->tgl_penjualan)->format('d M Y H:i') }}</td>
                        <td>{{ $jual->kasir }}</td>
                        <td>{{ $jual->nama_barang }}</td>
                        <td>{{ $jual->jumlah }}</td>
                        <td>Rp {{ number_format($jual->harga_jual, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($jual->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection