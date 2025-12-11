@extends('layout.app')
@section('title', 'Data Margin')
@section('page_title', 'Master Data Margin')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('master.margin') }}" method="GET"
                class="d-flex justify-content-between align-items-center">
                <div class="col-md-4">
                    <label for="status" class="form-label">Tampilkan Status</label>
                    <select id="status" name="status" class="form-select" onchange="this.form.submit()">
                        <option value="Aktif" {{ $status_terpilih == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Semua Margin" {{ $status_terpilih == 'Semua Margin' ? 'selected' : '' }}>Semua Margin
                        </option>
                    </select>
                </div>
                <div>
                    <a href="{{ route('master.margin.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Margin
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
                            <th scope="col">Persentase</th>
                            <th scope="col">Dibuat Oleh</th>
                            <th scope="col">Tgl. Dibuat</th>
                            <th scope="col">Status</th>
                            <th scope="col" style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_margin as $margin)
                            <tr>
                                <td>{{ $margin->idmargin_penjualan }}</td>
                                <td>{{ $margin->persen }}%</td>
                                <td>{{ $margin->dibuat_oleh }}</td>
                                <td>{{ \Carbon\Carbon::parse($margin->tanggal_dibuat)->format('d M Y') }}</td>
                                <td>
                                    @if($margin->status_margin == 'Aktif')
                                        <span class="badge bg-success">{{ $margin->status_margin }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $margin->status_margin }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($margin->status_margin == 'Aktif')
                                        <a href="{{ route('master.margin.edit', $margin->idmargin_penjualan) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('master.margin.destroy', $margin->idmargin_penjualan) }}" method="POST"
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
                                <td colspan="6" class="text-center">Data tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection