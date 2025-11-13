@extends('layout.app')
@section('title', 'Detail PO #' . $po->idpengadaan)
@section('page_title', 'Detail Pengadaan')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h5 class="mb-0">Tambah Barang ke Pengadaan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('transaksi.pengadaan.detail.store', $po->idpengadaan) }}" method="POST">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="idbarang" class="form-label">Barang</label>
                    <select class="form-select @error('idbarang') is-invalid @enderror" id="idbarang" name="idbarang" required>
                        <option value="">Pilih Barang...</option>
                        @foreach($barang_list as $barang)
                            <option value="{{ $barang->idbarang }}">{{ $barang->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="harga_satuan" class="form-label">Harga Beli (Satuan)</label>
                    <input type="number" class="form-control @error('harga_satuan') is-invalid @enderror" 
                           id="harga_satuan" name="harga_satuan" required>
                </div>
                <div class="col-md-2">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                           id="jumlah" name="jumlah" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Tambahkan</button>
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger mt-3 p-2">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li><small>{{ $error }}</small></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Barang di Pengadaan Ini</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID Detail</th>
                        <th>Nama Barang</th>
                        <th>Harga Beli</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($details as $detail)
                    <tr>
                        <td>{{ $detail->iddetail_pengadaan }}</td>
                        <td>{{ $detail->nama_barang }}</td>
                        <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('transaksi.pengadaan.detail.destroy', $detail->iddetail_pengadaan) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus item ini dari PO?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus Item">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada barang yang ditambahkan ke PO ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Pengadaan #{{ $po->idpengadaan }}</h5>
        <form action="{{ route('transaksi.pengadaan.destroy', $po->idpengadaan) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus seluruh PO ini beserta detailnya?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" title="Hapus PO">
                <i class="bi bi-trash"></i> Hapus Pengadaan
            </button>
        </form>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <strong>Vendor:</strong> {{ $po->nama_vendor }}
            </div>
            <div class="col-md-4">
                <strong>Pembuat PO:</strong> {{ $po->username }}
            </div>
            <div class="col-md-4">
                <strong>Tanggal Dibuat:</strong> {{ \Carbon\Carbon::parse($po->timestamp)->format('d M Y H:i') }}
            </div>
        </div>
        <hr>
        <div class="row text-center">
            <div class="col-md-4">
                <h6 class="text-muted">Subtotal</h6>
                <h5>Rp {{ number_format($po->subtotal_nilai, 0, ',', '.') }}</h5>
            </div>
            <div class="col-md-4">
                <h6 class="text-muted">PPN 10%</h6>
                <h5>Rp {{ number_format($po->ppn, 0, ',', '.') }}</h5>
            </div>
            <div class="col-md-4">
                <h6 class="text-muted">Total Nilai</h6>
                <h5>Rp {{ number_format($po->total_nilai, 0, ',', '.') }}</h5>
            </div>
        </div>
    </div>
</div>
@endsection