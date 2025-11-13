@extends('layout.app')
@section('title', 'Data Pengadaan (PO)')
@section('page_title', 'Data Pengadaan (PO)')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('transaksi.pengadaan') }}" method="GET" class="d-flex justify-content-between align-items-center">
            <div class="col-md-4">
                <label for="status" class="form-label">Tampilkan Status PO</label>
                <select id="status" name="status" class="form-select" onchange="this.form.submit()">
                    <option value="" {{ $status_terpilih == null ? 'selected' : '' }}>Semua Status</option>
                    <option value="Aktif" {{ $status_terpilih == 'Aktif' ? 'selected' : '' }}>Aktif (Dipesan)</option>
                    <option value="Selesai" {{ $status_terpilih == 'Selesai' ? 'selected' : '' }}>Selesai (Diterima)</option>
                </select>
            </div>
            <div>
                <a href="{{ route('transaksi.pengadaan.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Buat PO Baru
                </a>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errors->first('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID PO</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Status</th>
                        <th scope="col">Vendor</th>
                        <th scope="col">Pembuat PO</th>
                        <th scope="col">Subtotal</th>
                        <th scope="col">PPN (10%)</th>
                        <th scope="col">Total Nilai</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data_pengadaan as $po)
                    <tr>
                        <td>{{ $po->idpengadaan }}</td>
                        <td>{{ \Carbon\Carbon::parse($po->tgl_pengadaan)->format('d M Y H:i') }}</td>
                        <td>
                            @if($po->status_po == 'Aktif')
                                <span class="badge bg-warning text-dark">{{ $po->status_po }}</span>
                            @else
                                <span class="badge bg-success">{{ $po->status_po }}</span>
                            @endif
                        </td>
                        <td>{{ $po->nama_vendor }}</td>
                        <td>{{ $po->pembuat_po }}</td>
                        <td>Rp {{ number_format($po->subtotal_nilai, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($po->ppn, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($po->total_nilai, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('transaksi.pengadaan.show', $po->idpengadaan) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection