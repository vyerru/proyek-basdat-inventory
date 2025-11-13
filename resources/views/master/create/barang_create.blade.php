@extends('layout.app')
@section('title', 'Tambah Barang')
@section('page_title', 'Tambah Barang Baru')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('master.barang.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" name="nama" value="{{ old('nama') }}" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="idsatuan" class="form-label">Satuan</label>
                            <select class="form-select @error('idsatuan') is-invalid @enderror" 
                                    id="idsatuan" name="idsatuan" required>
                                <option value="">Pilih Satuan...</option>
                                @foreach($satuan_list as $satuan)
                                    <option value="{{ $satuan->idsatuan }}" {{ old('idsatuan') == $satuan->idsatuan ? 'selected' : '' }}>
                                        {{ $satuan->nama_satuan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idsatuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jenis" class="form-label">Jenis Barang</label>
                            <select class="form-select @error('jenis') is-invalid @enderror" 
                                    id="jenis" name="jenis" required>
                                <option value="">Pilih Jenis...</option>
                                <option value="J" {{ old('jenis') == 'J' ? 'selected' : '' }}>Barang Jadi</option>
                                <option value="B" {{ old('jenis') == 'B' ? 'selected' : '' }}>Bahan Baku</option>
                            </select>
                            @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga Jual</label>
                        <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                               id="harga" name="harga" value="{{ old('harga') }}" required>
                        @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-text">Status barang akan otomatis diatur ke "Aktif".</div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Simpan Barang</button>
                    <a href="{{ route('master.barang') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection