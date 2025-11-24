@extends('layout.app')
@section('title', 'Penerimaan Barang')
@section('page_title', 'Penerimaan Barang')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between">
            <h5 class="mb-0">Riwayat Penerimaan</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBuatPenerimaan">
                <i class="bi bi-plus-circle"></i> Tambah Penerimaan Baru
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Terima</th>
                            <th>Tanggal</th>
                            <th>Ref. PO</th>
                            <th>Penerima</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data_penerimaan as $row)
                            <tr>
                                <td>{{ $row->idpenerimaan }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->tgl_terima)->format('d M Y H:i') }}</td>
                                <td>PO #{{ $row->referensi_po_id }}</td>
                                <td>{{ $row->penerima }}</td>
                                <td>
                                    @if($row->status_penerimaan == 'P') <span class="badge bg-warning text-dark">Proses
                                        Input</span>
                                    @elseif($row->status_penerimaan == 'D' || $row->status_penerimaan == 'Diterima')
                                        <span class="badge bg-success">Diterima</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $row->status_penerimaan }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('transaksi.penerimaan.show', $row->idpenerimaan) }}"
                                        class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data penerimaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBuatPenerimaan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Penerimaan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('transaksi.penerimaan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Nomor PO (Purchase Order)</label>
                            <select name="idpengadaan" class="form-select" required>
                                <option value="">-- Pilih PO --</option>
                                @foreach($po_list as $po)
                                    <option value="{{ $po->idpengadaan }}">
                                        PO #{{ $po->idpengadaan }} - {{ $po->nama_vendor }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Pilih PO yang barangnya akan diterima.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Buat Penerimaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection