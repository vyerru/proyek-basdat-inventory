@extends('layout.app')
@section('title', 'Tambah Vendor')
@section('page_title', 'Tambah Vendor Baru')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('master.vendor.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_vendor" class="form-label">Nama Vendor</label>
                        <input type="text" class="form-control @error('nama_vendor') is-invalid @enderror" 
                               id="nama_vendor" name="nama_vendor" value="{{ old('nama_vendor') }}" required>
                        @error('nama_vendor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="badan_hukum" class="form-label">Badan Hukum</label>
                        <select class="form-select @error('badan_hukum') is-invalid @enderror" 
                                id="badan_hukum" name="badan_hukum" required>
                            <option value="">Pilih Status...</option>
                            <option value="Y" {{ old('badan_hukum') == 'Y' ? 'selected' : '' }}>Ya (Berbadan Hukum)</option>
                            <option value="C" {{ old('badan_hukum') == 'C' ? 'selected' : '' }}>CV</option>
                            <option value="N" {{ old('badan_hukum') == 'N' ? 'selected' : '' }}>Tidak (Perseorangan)</option>
                        </select>
                        @error('badan_hukum')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text">Status vendor akan otomatis diatur ke "Aktif".</div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Simpan Vendor</button>
                    <a href="{{ route('master.vendor') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection