@extends('layout.app')
@section('title', 'Buat PO Baru')
@section('page_title', 'Buat Purchase Order Baru (Header)')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <p>Langkah 1: Tentukan vendor. Anda akan diarahkan ke halaman detail untuk menambah barang.</p>
                
                <form action="{{ route('transaksi.pengadaan.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="vendor_idvendor" class="form-label">Vendor</label>
                        <select class="form-select @error('vendor_idvendor') is-invalid @enderror" 
                                id="vendor_idvendor" name="vendor_idvendor" required>
                            <option value="">Pilih Vendor...</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->idvendor }}">
                                    {{ $vendor->nama_vendor }}
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_idvendor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-text mb-3">
                        <ul>
                            <li><b>ID User</b> (Poin 1.c) akan diisi otomatis ({{ Auth::user()->username }}).</li>
                            <li><b>Status</b> (Poin 1.d) akan diatur ke "Aktif".</li>
                            <li><b>Subtotal</b> (Poin 1.e) akan diatur ke 0.</li>
                        </ul>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Lanjutkan & Tambah Detail</button>
                    <a href="{{ route('transaksi.pengadaan') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection