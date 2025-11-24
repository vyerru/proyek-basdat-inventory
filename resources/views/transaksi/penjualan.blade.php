@extends('layout.app')
@section('title', 'Transaksi Penjualan')
@section('page_title', 'Daftar Transaksi Penjualan')

@section('content')

<div class="card shadow-sm mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Riwayat Penjualan</h5>
        <form action="{{ route('transaksi.penjualan.store') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-cart-plus"></i> Transaksi Baru
            </button>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Total Transaksi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data_penjualan as $row)
                    <tr>
                        <td>{{ $row->idpenjualan }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->tgl_penjualan)->format('d M Y H:i') }}</td>
                        <td>{{ $row->kasir }}</td>
                        <td>Rp {{ number_format($row->total_transaksi, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('transaksi.penjualan.show', $row->idpenjualan) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection