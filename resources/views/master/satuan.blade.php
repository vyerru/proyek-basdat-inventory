@extends('layout.app')
@section('title', 'Data Satuan')
@section('page_title', 'Master Data Satuan')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('master.satuan') }}" method="GET"
                class="d-flex justify-content-between align-items-center">
                <div class="col-md-4">
                    <label for="status" class="form-label">Tampilkan Status</label>
                    <select id="status" name="status" class="form-select" onchange="this.form.submit()">
                        <option value="Aktif" {{ $status_terpilih == 'Aktif' ? 'selected' : '' }}>Satuan Aktif</option>
                        <option value="Semua Satuan" {{ $status_terpilih == 'Semua Satuan' ? 'selected' : '' }}>Semua Satuan
                        </option>
                    </select>
                </div>
                <div>
                    <a href="{{ route('master.satuan.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Satuan
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
                            <th>Nama Satuan</th>
                            <th>Status</th>
                            <th style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_satuan as $satuan)
                            <tr>
                                <td>{{ $satuan->idsatuan }}</td>
                                <td>{{ $satuan->nama_satuan }}</td>
                                <td>
                                    @if($satuan->status_satuan == 'Aktif')
                                        <span class="badge bg-success">{{ $satuan->status_satuan }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $satuan->status_satuan }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($satuan->status_satuan == 'Aktif')
                                        <a href="{{ route('master.satuan.edit', $satuan->idsatuan) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('master.satuan.destroy', $satuan->idsatuan) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Nonaktifkan?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Data tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection