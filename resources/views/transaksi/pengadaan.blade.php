@extends('layout.app')
@section('title', 'Data Pengadaan')
@section('page_title', 'Data Pengadaan (PO)')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <form action="{{ route('transaksi.pengadaan') }}" method="GET">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="" {{ $status_terpilih == '' ? 'selected' : '' }}>Semua Status</option>
                <option value="Aktif" {{ $status_terpilih == 'Aktif' ? 'selected' : '' }}>Proses (A)</option>
                <option value="Selesai" {{ $status_terpilih == 'Selesai' ? 'selected' : '' }}>Selesai (S)</option>
            </select>
        </form>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreatePO">
            <i class="bi bi-plus-circle"></i> Buat PO Baru
        </button>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Vendor</th>
                    <th>Status</th>
                    <th>Total Nilai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data_pengadaan as $po)
                <tr>
                    <td>#{{ $po->idpengadaan }}</td>
                    <td>{{ \Carbon\Carbon::parse($po->tgl_pengadaan)->format('d M Y') }}</td>
                    <td>{{ $po->nama_vendor }}</td>
                    <td>
                        <span class="badge {{ $po->status_po == 'Aktif' ? 'bg-warning text-dark' : 'bg-success' }}">
                            {{ $po->status_po }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($po->total_nilai, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('transaksi.pengadaan.show', $po->idpengadaan) }}" class="btn btn-info btn-sm">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">Data tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalCreatePO" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('transaksi.pengadaan.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Buat PO Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Vendor</label>
                    <select name="vendor_idvendor" class="form-select" required>
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendors as $v)
                            <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Lanjut</button>
            </div>
        </form>
    </div>
</div>
@endsection