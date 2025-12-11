@extends('layout.app')
@section('title', 'Edit Satuan')
@section('page_title', 'Edit Satuan')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('master.satuan.update', $satuan->idsatuan) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label>Nama Satuan</label>
                        <input type="text" class="form-control" name="nama_satuan" value="{{ $satuan->nama_satuan }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('master.satuan') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection