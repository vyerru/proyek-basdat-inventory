@extends('layout.app')
@section('title', 'Detail PO #' . $po->idpengadaan)
@section('page_title', 'Detail Purchase Order')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h6 class="text-muted">Nomor PO</h6>
                    <h4 class="text-primary fw-bold">#{{ $po->idpengadaan }}</h4>
                    <span class="badge {{ $po->status_po == 'Aktif' ? 'bg-warning text-dark' : 'bg-success' }}">{{ $po->status_po }}</span>
                </div>
                <div class="col-md-4">
                    <h6 class="text-muted">Vendor</h6>
                    <h5>{{ $po->nama_vendor }}</h5>
                </div>
                <div class="col-md-4">
                    <h6 class="text-muted">Total Nilai</h6>
                    <h5 class="fw-bold">Rp {{ number_format($po->total_nilai, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th class="ps-4">Barang</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end pe-4">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $item)
                    <tr>
                        <td class="ps-4">{{ $item->nama_barang }}</td>
                        <td class="text-end">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->jumlah }}</td>
                        <td class="text-end pe-4">Rp {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light border-top">
                    <tr class="bg-primary bg-opacity-10">
                        <td colspan="3" class="text-end fw-bold py-3">TOTAL (Inc. PPN):</td>
                        <td class="text-end pe-4 fw-bold py-3">Rp {{ number_format($po->total_nilai, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('transaksi.pengadaan') }}" class="btn btn-secondary">Kembali</a>
    </div>
@endsection