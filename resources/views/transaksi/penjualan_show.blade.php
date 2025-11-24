@extends('layout.app')
@section('title', 'Detail Transaksi #' . $penjualan->idpenjualan)
@section('page_title', 'Detail Penjualan')

@section('content')

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <h6 class="text-muted">ID Transaksi</h6>
                <h4 class="fw-bold text-primary">#{{ $penjualan->idpenjualan }}</h4>
            </div>
            <div class="col-md-4">
                <h6 class="text-muted">Kasir</h6>
                <h5><i class="bi bi-person-circle"></i> {{ $penjualan->kasir }}</h5>
            </div>
            <div class="col-md-4">
                <h6 class="text-muted">Waktu Transaksi</h6>
                <h5><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($penjualan->tgl_penjualan)->format('d M Y H:i') }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0 text-dark"><i class="bi bi-basket"></i> Rincian Barang</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Nama Barang</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end pe-4">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($details as $rawItem)
                        @php $item = (object) $rawItem; @endphp
                        <tr>
                            <td class="ps-4 fw-medium">{{ $item->nama_barang }}</td>
                            <td class="text-end">
                                Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary rounded-pill px-3">
                                    {{ $item->jumlah }}
                                </span>
                            </td>
                            <td class="text-end pe-4 fw-bold text-dark">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada barang di transaksi ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                
                <tfoot class="table-light border-top">
                    <tr>
                        <td colspan="3" class="text-end pt-3">Subtotal :</td>
                        <td class="text-end pe-4 pt-3">
                            Rp {{ number_format($penjualan->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end border-0">PPN (11%) :</td>
                        <td class="text-end pe-4 border-0">
                            Rp {{ number_format($penjualan->ppn, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="bg-primary bg-opacity-10">
                        <td colspan="3" class="text-end fw-bold fs-5 py-3 text-primary">TOTAL BAYAR :</td>
                        <td class="text-end pe-4 fw-bold fs-5 py-3 text-primary">
                            Rp {{ number_format($penjualan->total_transaksi, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-start">
    <a href="{{ route('transaksi.penjualan') }}" class="btn btn-secondary px-4">
        <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Penjualan
    </a>
</div>

@endsection