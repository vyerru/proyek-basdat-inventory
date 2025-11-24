@extends('layout.app')
@section('title', 'Data Pengadaan (PO)')
@section('page_title', 'Data Pengadaan')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('transaksi.pengadaan') }}" method="GET" class="d-flex justify-content-between align-items-center">
            <div class="col-md-4">
                <label for="status" class="form-label">Tampilkan Status Pengadaan</label>
                <select id="status" name="status" class="form-select" onchange="this.form.submit()">
                    <option value="" {{ $status_terpilih == null ? 'selected' : '' }}>Semua Status</option>
                    <option value="Aktif" {{ $status_terpilih == 'Aktif' ? 'selected' : '' }}>Proses (Dipesan)</option>
                    <option value="Selesai" {{ $status_terpilih == 'Selesai' ? 'selected' : '' }}>Selesai (Diterima)</option>
                </select>
            </div>
            
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#buatPOModal">
                    <i class="bi bi-plus-circle"></i> Buat Pengadaan Baru
                </button>
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
                        <th scope="col">ID PO</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Status</th>
                        <th scope="col">Vendor</th>
                        <th scope="col">Pembuat PO</th>
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
                        <td>Rp {{ number_format($po->total_nilai, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('transaksi.pengadaan.show', $po->idpengadaan) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="buatPOModal" tabindex="-1" aria-labelledby="buatPOModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buatPOModalLabel">Buat Purchase Order Baru (Header)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('transaksi.pengadaan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Langkah 1: Tentukan vendor. Anda akan diarahkan ke halaman detail untuk menambah barang.</p>
                    
                    <div class="mb-3">
                        <label for="vendor_idvendor" class="form-label">Vendor</label>
                        <select class="form-select @error('vendor_idvendor') is-invalid @enderror" 
                                id="vendor_idvendor" name="vendor_idvendor" required>
                            <option value="">Pilih Vendor...</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->idvendor }}">
                                    {{ $vendor->nama_vendor }}
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_idvendor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-text mb-3">
                        <ul>
                            <li><b>ID User</b> ({{ Auth::user()->username }}).</li>
                            <li><b>Status</b>.</li>
                            <li><b>Subtotal</b></li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Lanjutkan & Tambah Detail</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection