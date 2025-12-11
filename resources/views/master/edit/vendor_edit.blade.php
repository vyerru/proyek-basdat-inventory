@extends('layout.app')
@section('title', 'Edit Vendor')
@section('page_title', 'Edit Vendor')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('master.vendor.update', $vendor->idvendor) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label>Nama Vendor</label>
                        <input type="text" class="form-control" name="nama_vendor" value="{{ $vendor->nama_vendor }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Badan Hukum</label>
                        <select class="form-select" name="badan_hukum" required>
                            <option value="Y" {{ $vendor->badan_hukum == 'Y' ? 'selected' : '' }}>Ya (PT/CV)</option>
                            <option value="N" {{ $vendor->badan_hukum == 'N' ? 'selected' : '' }}>Tidak</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('master.vendor') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection