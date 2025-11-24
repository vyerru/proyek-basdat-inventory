@extends('layout.app')
@section('title', 'Kasir - Transaksi #' . $penjualan->idpenjualan)
@section('page_title', 'Proses Transaksi Penjualan')

@section('content')
    @if(session('success')) <div class="alert alert-success mb-3">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger mb-3">{{ $errors->first() }}</div> @endif

    <div class="card shadow-sm mb-4 border-primary">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Transaksi #{{ $penjualan->idpenjualan }}</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg"></i> Tambah Barang
            </button>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Barang</th>
                        <th class="text-end">Harga Jual (Auto)</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $item)
                    <tr>
                        <td>{{ $item->nama_barang }}</td>
                        <td class="text-end">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->jumlah }}</td>
                        <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('transaksi.penjualan.detail.destroy', $item->iddetail_penjualan) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-end">TOTAL TAGIHAN (Inc. PPN):</td>
                        <td class="text-end fs-5 text-primary">Rp {{ number_format($penjualan->total_nilai, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="d-grid gap-2">
        <a href="{{ route('transaksi.penjualan') }}" class="btn btn-success btn-lg" onclick="return confirm('Selesaikan transaksi?')">
            <i class="bi bi-check-circle"></i> SIMPAN PERMANEN & SELESAI
        </a>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('transaksi.penjualan.detail.store', $penjualan->idpenjualan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Pilih Barang</label>
                        <select name="idbarang" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach($barang_list as $b)
                                <option value="{{ $b->idbarang }}" {{ $b->stok <= 0 ? 'disabled' : '' }}>
                                    {{ $b->nama }} (Stok: {{ $b->stok }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Harga akan dihitung otomatis berdasarkan modal terakhir + margin.</div>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
@endsection