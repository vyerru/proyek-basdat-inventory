@extends('layout.app')
@section('title', 'Data Satuan')
@section('page_title', 'Master Data Satuan')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('master.satuan') }}" method="GET">
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
                        <th scope="col">Nama Satuan</th>
                        <th scope="col">Status</th>
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
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection