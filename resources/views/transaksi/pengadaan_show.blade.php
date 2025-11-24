@extends('layout.app')
@section('title', 'Detail PO #' . $po->idpengadaan)
@section('page_title', 'Detail Purchase Order (PO)')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">PO #{{ $po->idpengadaan }}</h5>
        <form action="{{ route('transaksi.pengadaan.destroy', $po->idpengadaan) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus seluruh PO ini beserta detailnya?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" title="Hapus PO">
                <i class="bi bi-trash"></i> Hapus Pengadaan
            </button>
        </form>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <strong>Vendor:</strong> {{ $po->nama_vendor }}
            </div>
            <div class="col-md-4">
                <strong>Pembuat Pengadaan:</strong> {{ $po->username }}
            </div>
            <div class="col-md-4">
                <strong>Tanggal Dibuat:</strong> {{ \Carbon\Carbon::parse($po->timestamp)->format('d M Y H:i') }}
            </div>
        </div>
        <hr>
        <div class="row text-center">
            <div class="col-md-4">
                <h6 class="text-muted">Subtotal</h6>
                <h5>Rp {{ number_format($po->subtotal_nilai, 0, ',', '.') }}</h5>
            </div>
            <div class="col-md-4">
                <h6 class="text-muted">PPN 10%</h6>
                <h5>Rp {{ number_format($po->ppn, 0, ',', '.') }}</h5>
            </div>
            <div class="col-md-4">
                <h6 class="text-muted">Total Nilai</h6>
                <h5>Rp {{ number_format($po->total_nilai, 0, ',', '.') }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="mb-3 d-flex justify-content-end">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahBarangModal">
        <i class="bi bi-plus-circle"></i> Tambah Barang ke Pengadaan
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Barang di PO Ini</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID Detail</th>
                        <th>Nama Barang</th>
                        <th>Harga Beli </th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($details as $detail)
                    <tr>
                        <td>{{ $detail->iddetail_pengadaan }}</td>
                        <td>{{ $detail->nama_barang }}</td>
                        <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('transaksi.pengadaan.detail.destroy', $detail->iddetail_pengadaan) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus item ini dari PO?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus Item">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada barang yang ditambahkan ke Pengadaan ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="tambahBarangModal" tabindex="-1" aria-labelledby="tambahBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahBarangModalLabel">Tambah Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('transaksi.pengadaan.detail.store', $po->idpengadaan) }}" method="POST">
                @csrf
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label for="idbarang" class="form-label">Barang</label>
                        <select class="form-select @error('idbarang') is-invalid @enderror" id="idbarang" name="idbarang" required>
                            <option value="" data-harga="0">Pilih Barang...</option>
                            @foreach($barang_list as $barang)
                                <option value="{{ $barang->idbarang }}" data-harga="{{ $barang->harga }}">
                                    {{ $barang->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        @error('idbarang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga Satuan</label>
                        <input type="text" class="form-control" id="harga_satuan_display" value="Rp 0" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                               id="jumlah" name="jumlah" required>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const barangSelect = document.getElementById('idbarang');
        const hargaDisplay = document.getElementById('harga_satuan_display');

        barangSelect.addEventListener('change', function () {
            // Ambil harga dari atribut 'data-harga'
            const selectedOption = this.options[this.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga');
            
            // Format sebagai Rupiah dan tampilkan
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });
            
            hargaDisplay.value = formatter.format(harga).replace('Rp', 'Rp ');
        });
    });
</script>
@endsection