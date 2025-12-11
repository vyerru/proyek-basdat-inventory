@extends('layout.app')
@section('title', 'Edit Barang')
@section('page_title', 'Edit Barang')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('master.barang.update', $barang->idbarang) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- PENTING: Method PUT untuk update --}}
                    
                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" name="nama" value="{{ $barang->nama }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Satuan</label>
                            <select class="form-select" name="idsatuan" required>
                                @foreach($satuan_list as $s)
                                    <option value="{{ $s->idsatuan }}" {{ $barang->idsatuan == $s->idsatuan ? 'selected' : '' }}>
                                        {{ $s->nama_satuan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Jenis</label>
                            <select class="form-select" name="jenis" required>
                                <option value="J" {{ $barang->jenis == 'J' ? 'selected' : '' }}>Barang Jadi</option>
                                <option value="B" {{ $barang->jenis == 'B' ? 'selected' : '' }}>Bahan Baku</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Harga Jual</label>
                        <input type="number" class="form-control" name="harga" value="{{ $barang->harga }}" required>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Update Barang</button>
                    <a href="{{ route('master.barang') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection