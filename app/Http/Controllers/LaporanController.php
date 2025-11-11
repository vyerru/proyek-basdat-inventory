<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    // Menampilkan Laporan Penjualan (dari VIEW)
    public function penjualan()
    {
        // Langsung ambil dari VIEW (SP Filter bisa ditambahkan di sini jika perlu)
        $data = DB::select("SELECT * FROM view_laporan_penjualan ORDER BY tgl_penjualan DESC");
        return view('Laporan.penjualan', ['data_penjualan' => $data]);
    }

    // Menampilkan Kartu Stok (Tabel Asli)
    public function kartuStok(Request $request)
    {
        // 1. Ambil daftar barang untuk dropdown filter
        $daftar_barang = DB::select("SELECT idbarang, nama FROM barang WHERE status = 1 ORDER BY nama");
        
        // 2. Tentukan barang yang dipilih (default barang pertama)
        $idbarang_terpilih = $request->input('idbarang');
        if (empty($idbarang_terpilih) && !empty($daftar_barang)) {
            $idbarang_terpilih = $daftar_barang[0]->idbarang;
        }

        // 3. Ambil data kartu stok berdasarkan barang yang dipilih
        $data = [];
        if (!empty($idbarang_terpilih)) {
            $data = DB::select("SELECT * FROM kartu_stok WHERE idbarang = ? ORDER BY idkartu_stok ASC", 
                [$idbarang_terpilih]
            );
        }

        return view('Laporan.kartu_stok', [
            'data_kartu_stok' => $data,
            'daftar_barang' => $daftar_barang,
            'idbarang_terpilih' => $idbarang_terpilih
        ]);
    }
}