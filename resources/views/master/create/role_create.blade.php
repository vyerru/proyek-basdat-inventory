@extends('layout.app')
@section('title', 'Tambah Role')
@section('page_title', 'Tambah Role Baru')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('master.role.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_role" class="form-label">Nama Role</label>
                        <input type="text" class="form-control @error('nama_role') is-invalid @enderror" 
                               id="nama_role" name="nama_role" value="{{ old('nama_role') }}" required>
                        @error('nama_role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Simpan Role</button>
                    <a href="{{ route('master.role') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection