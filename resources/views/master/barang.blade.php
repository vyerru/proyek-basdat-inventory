@extends('layout.app')
@section('title', 'Data Barang')
@section('page_title', 'Master Data Barang')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('master.barang') }}" method="GET" class="d-flex justify-content-between align-items-center">
            <div class="col-md-4">
                <label for="status" class="form-label">Tampilkan Status</label>
                <select id="status" name="status" class="form-select" onchange="this.form.submit()">
                    <option value="Aktif" {{ $status_terpilih == 'Aktif' ? 'selected' : '' }}>Barang Aktif</option>
                    <option value="Semua Barang" {{ $status_terpilih == 'Semua Barang' ? 'selected' : '' }}>Semua Barang</option>
                </select>
            </div>
            <div>
                <a href="{{ route('master.barang.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Barang
                </a>
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
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Harga Jual</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th style="width: 10%;">Aksi</th>
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
                        <td>
                            @if($barang->status_barang == 'Aktif')
                            <form action="{{ route('master.barang.destroy', $barang->idbarang) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menonaktifkan barang ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Nonaktifkan">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection