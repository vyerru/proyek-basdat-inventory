@extends('layout.app')
@section('title', 'Detail Penerimaan #' . $penerimaan->idpenerimaan)
@section('page_title', 'Detail Penerimaan Barang')

@section('content')

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h6 class="text-muted">ID Penerimaan</h6>
                    <h4 class="fw-bold text-primary">#{{ $penerimaan->idpenerimaan }}</h4>
                    
                    @if($penerimaan->status == 'P')
                        <span class="badge bg-warning text-dark">Proses Input</span>
                    @elseif($penerimaan->status == 'S' || $penerimaan->status == 'D')
                        <span class="badge bg-success">Selesai / Diterima</span>
                    @else
                        <span class="badge bg-secondary">{{ $penerimaan->status }}</span>
                    @endif
                </div>
                
                <div class="col-md-4">
                    <h6 class="text-muted">Referensi PO</h6>
                    <h5>
                        <a href="{{ route('transaksi.pengadaan.show', $penerimaan->idpengadaan) }}" class="text-decoration-none">
                            PO #{{ $penerimaan->idpengadaan }}
                        </a>
                    </h5>
                    <small class="text-muted">Vendor: <strong>{{ $penerimaan->nama_vendor }}</strong></small>
                </div>
                
                <div class="col-md-4">
                    <h6 class="text-muted">Info Tambahan</h6>
                    <p class="mb-0">Penerima: <strong>{{ $penerimaan->penerima }}</strong></p>
                    <small>Tanggal: {{ \Carbon\Carbon::parse($penerimaan->created_at)->format('d M Y H:i') }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-box-seam"></i> Daftar Barang Diterima</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th class="ps-4">Nama Barang</th>
                            <th class="text-center">Jumlah Diterima</th>
                            <th class="text-end">Harga Terima</th>
                            <th class="text-end pe-4">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $rawItem)
                            @php $item = (object) $rawItem; @endphp
                            <tr>
                                <td class="ps-4 fw-medium">{{ $item->nama_barang }}</td>
                                
                                <td class="text-center">
                                    <span class="badge bg-info text-dark rounded-pill px-3">
                                        {{ $item->jumlah_terima }}
                                    </span>
                                </td>
                                
                                <td class="text-end">
                                    Rp {{ number_format($item->harga_satuan_terima, 0, ',', '.') }}
                                </td>
                                
                                <td class="text-end pe-4">
                                    Rp {{ number_format($item->sub_total_terima, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    Belum ada barang yang diterima pada sesi ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                    @if(count($details) > 0)
                    <tfoot class="table-light border-top">
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end py-3">Total Nilai Penerimaan:</td>
                            <td class="text-end pe-4 py-3 text-primary">
                                @php
                                    $total = 0;
                                    foreach($details as $d) $total += $d->sub_total_terima;
                                @endphp
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('transaksi.penerimaan') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Penerimaan
        </a>

        @if($penerimaan->status == 'P')
            <a href="{{ route('transaksi.penerimaan.proses', $penerimaan->idpenerimaan) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Lanjutkan Input Barang
            </a>
        @endif
    </div>

@endsection