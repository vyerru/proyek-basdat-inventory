@extends('layout.app')
@section('title', 'Tambah Satuan')
@section('page_title', 'Tambah Satuan Baru')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('master.satuan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_satuan" class="form-label">Nama Satuan</label>
                        <input type="text" class="form-control @error('nama_satuan') is-invalid @enderror" 
                               id="nama_satuan" name="nama_satuan" value="{{ old('nama_satuan') }}" required>
                        @error('nama_satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Simpan Satuan</button>
                    <a href="{{ route('master.satuan') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection