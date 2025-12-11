@extends('layout.app')
@section('title', 'Data Vendor')
@section('page_title', 'Master Data Vendor')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('master.vendor') }}" method="GET"
                class="d-flex justify-content-between align-items-center">
                <div class="col-md-4">
                    <label for="status" class="form-label">Tampilkan Status</label>
                    <select id="status" name="status" class="form-select" onchange="this.form.submit()">
                        <option value="Aktif" {{ $status_terpilih == 'Aktif' ? 'selected' : '' }}>Vendor Aktif</option>
                        <option value="Semua Vendor" {{ $status_terpilih == 'Semua Vendor' ? 'selected' : '' }}>Semua Vendor
                        </option>
                    </select>
                </div>
                <div>
                    <a href="{{ route('master.vendor.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Vendor
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
                            <th scope="col">ID</th>
                            <th scope="col">Nama Vendor</th>
                            <th scope="col">Badan Hukum</th>
                            <th scope="col">Status</th>
                            <th scope="col" style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_vendor as $vendor)
                            <tr>
                                <td>{{ $vendor->idvendor }}</td>
                                <td>{{ $vendor->nama_vendor }}</td>
                                <td>{{ $vendor->status_badan_hukum }}</td>
                                <td>
                                    @if($vendor->status_vendor == 'Aktif')
                                        <span class="badge bg-success">{{ $vendor->status_vendor }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $vendor->status_vendor }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('master.vendor.edit', $vendor->idvendor) }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('master.vendor.destroy', $vendor->idvendor) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Nonaktifkan?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Data tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection