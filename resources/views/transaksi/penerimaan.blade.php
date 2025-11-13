@extends('layout.app')
@section('title', 'Data Penerimaan')
@section('page_title', 'Data Penerimaan')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('transaksi.penerimaan') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="status" class="form-label">Tampilkan Status Penerimaan</label>
                    <select id="status" name="status" class="form-select" onchange="this.form.submit()">
                        <option value="" {{ $status_terpilih == null ? 'selected' : '' }}>Semua Status</option>
                        <option value="Selesai" {{ $status_terpilih == 'Selesai' ? 'selected' : '' }}>Selesai (Diterima)</option>
                        <option value="Tidak Diterima" {{ $status_terpilih == 'Tidak Diterima' ? 'selected' : '' }}>Tidak Diterima</option>
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
                        <th scope="col">ID Penerimaan</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Referensi PO</th>
                        <th scope="col">Status Penerimaan</th>
                        <th scope="col">Penerima</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Jumlah Terima</th>
                        <th scope="col">Harga Satuan</th>
                        <th scope="col">Sub Total Terima</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data_penerimaan as $penerimaan)
                    <tr>
                        <td>{{ $penerimaan->idpenerimaan }}</td>
                        <td>{{ \Carbon\Carbon::parse($penerimaan->tgl_terima)->format('d M Y H:i') }}</td>
                        <td>{{ $penerimaan->referensi_po_id }}</td>
                        <td>
                            @if($penerimaan->status_penerimaan == 'Diterima')
                                <span class="badge bg-warning text-dark">{{ $penerimaan->status_penerimaan }}</span>
                            @else
                                <span class="badge bg-success">{{ $penerimaan->status_penerimaan }}</span>
                            @endif
                        </td>
                        <td>{{ $penerimaan->penerima }}</td>
                        <td>{{ $penerimaan->nama_barang }}</td>
                        <td>{{ $penerimaan->jumlah_terima }}</td>
                        <td>Rp {{ number_format($penerimaan->harga_satuan_terima, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($penerimaan->sub_total_terima, 0, ',', '.') }}</td>
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