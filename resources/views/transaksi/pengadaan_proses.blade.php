@extends('layout.app')
@section('title', 'Proses PO #' . $po->idpengadaan)
@section('page_title', 'Proses Purchase Order')

@section('content')
    <div class="card shadow-sm mb-4 border-warning">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">PO #{{ $po->idpengadaan }} <span class="badge bg-warning text-dark">PROSES (A)</span></h5>
                <small class="text-muted">Vendor: <strong>{{ $po->nama_vendor }}</strong></small>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle"></i> Tambah Barang
            </button>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Barang</th>
                        <th class="text-end">Harga (Auto)</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $item)
                    <tr>
                        <td>{{ $item->nama_barang }}</td>
                        <td class="text-end">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->jumlah }}</td>
                        <td class="text-end">Rp {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('transaksi.pengadaan.detail.destroy', $item->iddetail_pengadaan) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-end">TOTAL (Inc. PPN 10%):</td>
                        <td class="text-end fs-5 text-primary">Rp {{ number_format($po->total_nilai, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <form action="{{ route('transaksi.pengadaan.destroy', $po->idpengadaan) }}" method="POST" onsubmit="return confirm('Hapus PO ini?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger">Batalkan PO</button>
        </form>
        
        <form action="{{ route('transaksi.pengadaan.simpan', $po->idpengadaan) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success btn-lg">SELESAI INPUT & KEMBALI</button>
        </form>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('transaksi.pengadaan.detail.store', $po->idpengadaan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Barang</label>
                        <select name="idbarang" id="idbarang" class="form-select" required>
                            <option value="" data-harga="0">-- Pilih --</option>
                            @foreach($barang_list as $b)
                                <option value="{{ $b->idbarang }}" data-harga="{{ $b->harga }}">
                                    {{ $b->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Harga Satuan</label>
                        <input type="text" class="form-control" id="display_harga" value="Rp 0" readonly disabled>
                        <div class="form-text">Harga diambil otomatis dari Master Barang.</div>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('idbarang').addEventListener('change', function() {
            const harga = this.options[this.selectedIndex].getAttribute('data-harga');
            document.getElementById('display_harga').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga);
        });
    </script>
@endsection