@extends('layout.app')
@section('title', 'Data Barang')
@section('page_title', 'Master Data Barang')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('master.barang') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="status" class="form-label">Tampilkan Status</label>
                    <select id="status" name="status" class="form-select" onchange="this.form.submit()">
                        <option value="Aktif" {{ $status_terpilih == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ $status_terpilih == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
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
                        <th scope="col">ID</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Satuan</th>
                        <th scope="col">Harga Jual</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data_barang as $barang)
                    <tr>
                        <td>{{ $barang->idbarang }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->nama_satuan }}</td>
                        <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                        <td>{{ $barang->jenis_barang }}</td>
                        <td>
                            @if($barang->status_barang == 'Aktif')
                                <span class="badge bg-success">{{ $barang->status_barang }}</span>
                            @else
                                <span class="badge bg-danger">{{ $barang->status_barang }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection