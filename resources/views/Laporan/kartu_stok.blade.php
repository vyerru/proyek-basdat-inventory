@extends('layout.app')
@section('title', 'Kartu Stok')
@section('page_title', 'Laporan Kartu Stok')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('laporan.kartu.stok') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="idbarang" class="form-label">Tampilkan Kartu Stok Barang</label>
                    <select id="idbarang" name="idbarang" class="form-select" onchange="this.form.submit()">
                        @foreach($daftar_barang as $barang)
                        <option value="{{ $barang->idbarang }}" 
                                {{ $idbarang_terpilih == $barang->idbarang ? 'selected' : '' }}>
                            {{ $barang->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID Transaksi</th>
                        <th scope="col">Waktu</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Masuk</th>
                        <th scope="col">Keluar</th>
                        <th scope="col">Sisa Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data_kartu_stok as $stok)
                    <tr>
                        <td>{{ $stok->idtransaksi }}</td>
                        <td>{{ \Carbon\Carbon::parse($stok->created_at)->format('d M Y H:i:s') }}</td>
                        <td>
                            @if($stok->jenis_transaksi == 'M')
                                <span class="badge bg-primary">MASUK</span>
                            @else
                                <span class="badge bg-danger">KELUAR</span>
                            @endif
                        </td>
                        <td class="text-success fw-bold">{{ $stok->masuk > 0 ? $stok->masuk : '' }}</td>
                        <td class="text-danger fw-bold">{{ $stok->keluar > 0 ? $stok->keluar : '' }}</td>
                        <td class="fw-bold">{{ $stok->stock }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">Belum ada riwayat stok untuk barang ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection