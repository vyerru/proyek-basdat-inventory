@extends('layout.app')
@section('title', 'Data Pengadaan (PO)')
@section('page_title', 'Data Pengadaan (PO)')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('transaksi.pengadaan') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="status" class="form-label">Tampilkan Status PO</label>
                    <select id="status" name="status" class="form-select" onchange="this.form.submit()">
                        <option value="" {{ $status_terpilih == null ? 'selected' : '' }}>Semua Status</option>
                        <option value="Aktif" {{ $status_terpilih == 'Aktif' ? 'selected' : '' }}>Aktif (Dipesan)</option>
                        <option value="Selesai" {{ $status_terpilih == 'Selesai' ? 'selected' : '' }}>Selesai (Diterima)</option>
                    </select>
                </div>
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
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Harga Beli</th>
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
                        <td>{{ $po->nama_barang }}</td>
                        <td>{{ $po->jumlah }}</td>
                        <td>Rp {{ number_format($po->harga_beli, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection