@extends('layout.app')
@section('title', 'Tambah Margin')
@section('page_title', 'Tambah Margin Baru')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('master.margin.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="persen" class="form-label">Persentase (%)</label>
                        <input type="number" step="0.01" class="form-control @error('persen') is-invalid @enderror" 
                               id="persen" name="persen" value="{{ old('persen') }}" required>
                        @error('persen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text">
                        Margin ini akan otomatis aktif (status = 1) dan iduser akan
                        diisi oleh ID Anda: (<b>{{ Auth::user()->username }}</b>).
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Simpan Margin</button>
                    <a href="{{ route('master.margin') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection