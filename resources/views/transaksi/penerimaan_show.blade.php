@extends('layout.app')
@section('title', 'Detail Penerimaan #' . $penerimaan->idpenerimaan)
@section('page_title', 'Detail Penerimaan Barang')

@section('content')

@if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger mb-3">{!! $errors->first() !!}</div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            Penerimaan #{{ $penerimaan->idpenerimaan }}
            @if($penerimaan->status == 'P')
                <span class="badge bg-warning text-dark ms-2">Proses Input</span>
            @else
                <span class="badge bg-success ms-2">Diterima (Final)</span>
            @endif
        </h5>
        
        @if($penerimaan->status == 'P')
        <form action="{{ route('transaksi.penerimaan.destroy', $penerimaan->idpenerimaan) }}" method="POST" onsubmit="return confirm('Batalkan dan hapus penerimaan ini?');">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus Draft</button>
        </form>
        @endif
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4"><strong>Ref PO:</strong> PO #{{ $penerimaan->idpengadaan }}</div>
            <div class="col-md-4"><strong>Vendor:</strong> {{ $penerimaan->nama_vendor }}</div>
            <div class="col-md-4"><strong>Penerima:</strong> {{ $penerimaan->username }}</div>
            <div class="col-md-4 mt-2"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penerimaan->created_at)->format('d M Y H:i') }}</div>
        </div>
    </div>
</div>

@if($penerimaan->status == 'P')
    <div class="alert alert-info mb-3">
        <i class="bi bi-info-circle"></i> Silakan tambahkan barang yang diterima. Jika sudah semua, klik tombol <b>"Selesai & Kunci"</b>.
    </div>

    <div class="d-flex justify-content-between mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInputBarang">
            <i class="bi bi-box-seam"></i> Input Barang Diterima
        </button>

        <form action="{{ route('transaksi.penerimaan.lock', $penerimaan->idpenerimaan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin semua barang sudah diinput? Data tidak bisa diubah lagi setelah ini.');">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Selesai & Kunci Penerimaan
            </button>
        </form>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Daftar Barang Diterima</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Barang</th>
                        <th>Jml Terima</th>
                        <th>Harga Terima (Aktual)</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($details as $item)
                    <tr>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->jumlah_terima }}</td>
                        <td>Rp {{ number_format($item->harga_satuan_terima, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->sub_total_terima, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">Belum ada barang yang diterima.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($penerimaan->status == 'P')
<div class="modal fade" id="modalInputBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Barang Diterima</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('transaksi.penerimaan.detail.store', $penerimaan->idpenerimaan) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Barang</label>
                        <select name="idbarang" class="form-select" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barang_list as $b)
                                <option value="{{ $b->idbarang }}">{{ $b->nama }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Hanya menampilkan barang dari PO #{{ $penerimaan->idpengadaan }}.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Diterima</label>
                        <input type="number" name="jumlah_terima" class="form-control" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Terima (Aktual)</label>
                        <input type="number" name="harga_terima" class="form-control" required min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection