@extends('layout.app')
@section('title', 'Proses Penerimaan #' . $penerimaan->idpenerimaan)
@section('page_title', 'Proses Penerimaan Barang')

@section('content')
    @if(session('success')) <div class="alert alert-success mb-3">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger mb-3">{{ $errors->first() }}</div> @endif

    <div class="card shadow-sm mb-4 border-warning">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">Terima Barang <span class="badge bg-warning text-dark">PROSES</span></h5>
                <div class="text-muted">
                    Ref PO: <strong>#{{ $penerimaan->idpengadaan }}</strong> | 
                    Vendor: <strong>{{ $penerimaan->nama_vendor }}</strong>
                </div>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInput">
                <i class="bi bi-box-seam"></i> Input Barang
            </button>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Barang</th>
                        <th class="text-end">Harga (Master)</th>
                        <th class="text-center">Jml Diterima</th>
                        <th class="text-end">Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $item)
                    <tr>
                        <td>{{ $item->nama_barang }}</td>
                        <td class="text-end">Rp {{ number_format($item->harga_satuan_terima, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->jumlah_terima }}</td>
                        <td class="text-end">Rp {{ number_format($item->sub_total_terima, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('transaksi.penerimaan.detail.destroy', $item->iddetail_penerimaan) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <form action="{{ route('transaksi.penerimaan.destroy', $penerimaan->idpenerimaan) }}" method="POST" onsubmit="return confirm('Batalkan proses penerimaan ini?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger">Batalkan</button>
        </form>
        
        <form action="{{ route('transaksi.penerimaan.simpan', $penerimaan->idpenerimaan) }}" method="POST" onsubmit="return confirm('Selesaikan penerimaan? Stok akan bertambah.')">
            @csrf
            <button class="btn btn-success btn-lg"><i class="bi bi-check-circle"></i> SELESAI & SIMPAN</button>
        </form>
    </div>

    <div class="modal fade" id="modalInput" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('transaksi.penerimaan.detail.store', $penerimaan->idpenerimaan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Input Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Pilih Barang (Dari PO)</label>
                        <select name="idbarang" id="idbarang" class="form-select" required>
                            <option value="" data-harga="0">-- Pilih --</option>
                            @foreach($barang_list as $b)
                                <option value="{{ $b->idbarang }}" data-harga="{{ $b->harga_master }}">
                                    {{ $b->nama }} (Pesan: {{ $b->qty_pesan }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label>Harga Terima (Otomatis)</label>
                        <input type="text" class="form-control" id="display_harga" value="Rp 0" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label>Jumlah Diterima</label>
                        <input type="number" name="jumlah_terima" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
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