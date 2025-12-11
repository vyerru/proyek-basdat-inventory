@extends('layout.app')
@section('title', 'Edit Margin')
@section('page_title', 'Edit Margin')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('master.margin.update', $margin->idmargin_penjualan) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label>Persentase (%)</label>
                        <input type="number" step="0.01" class="form-control" name="persen" value="{{ $margin->persen }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('master.margin') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection