<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class TransaksiController extends Controller
{
    public function pengadaan(Request $request)
    {
        // 1. Ambil nilai status dari form filter
        $status = $request->input('status'); // 'Aktif', 'Selesai', atau null

        // 2. Buat query dasar ke VIEW Anda
        $query = DB::table('view_laporan_pengadaan'); 

        // 3. Tambahkan filter HANYA JIKA status dipilih
        if ($status) {
            // 'status_po' adalah nama kolom di 'view_header_pengadaan'
            $query->where('status_po', $status);
        }

        // 4. Eksekusi query, urutkan berdasarkan terbaru
        $data = $query->orderBy('tgl_pengadaan', 'DESC')->get();
        
        // 5. Kirim data ke view
        return view('transaksi.pengadaan', [ // Pastikan path view benar
            'data_pengadaan' => $data, 
            'status_terpilih' => $status
        ]);
    }

    // ... (Semua method Anda yang lain seperti createPengadaan, storePengadaan, dll,
    //      tetap sama dan tidak perlu diubah) ...
    
    /**
     * CREATE: Menampilkan form 'Buat PO Header'
     */
    public function createPengadaan()
    {
        // Ambil data vendor untuk dropdown
        $vendors = DB::select("SELECT * FROM view_vendor_aktif");
        return view('transaksi.create.pengadaan_create', ['vendors' => $vendors]);
    }

    public function storePengadaan(Request $request)
    {
        $request->validate(['vendor_idvendor' => 'required|integer']);

        $id_user_login = Auth::id();
        $status_default = 'A'; 
        
        DB::insert("
            INSERT INTO pengadaan (user_iduser, vendor_idvendor, status, subtotal_nilai, ppn, total_nilai, timestamp)
            VALUES (?, ?, ?, 0, 0, 0, NOW())
        ", [$id_user_login, $request->vendor_idvendor, $status_default]);

        $newPengadaanId = DB::getPdo()->lastInsertId();

        return redirect()->route('transaksi.pengadaan.show', $newPengadaanId)
                         ->with('success', 'Header PO berhasil dibuat. Silakan tambahkan detail barang.');
    }

    public function showPengadaan($id)
    {
        // Ambil data Header PO
        $po_header = DB::selectOne(
            "SELECT p.*, v.nama_vendor, u.username
             FROM pengadaan p
             JOIN vendor v ON p.vendor_idvendor = v.idvendor
             JOIN user u ON p.user_iduser = u.iduser
             WHERE p.idpengadaan = ?",
            [$id]
        );

        if (!$po_header) { abort(404); }

        // Ambil data Detail PO yang sudah ada
        $po_detail = DB::select(
            "SELECT d.*, b.nama AS nama_barang
             FROM detail_pengadaan d
             JOIN barang b ON d.idbarang = b.idbarang
             WHERE d.idpengadaan = ?",
            [$id]
        );

        // Ambil daftar barang untuk dropdown
        $barang_list = DB::select("SELECT * FROM view_barang_aktif");
        
        return view('transaksi.pengadaan_show', [
            'po' => $po_header,
            'details' => $po_detail,
            'barang_list' => $barang_list
        ]);
    }

    /**
     * CREATE: Menyimpan item detail baru ke PO (Poin 2)
     */
    public function storeDetailPengadaan(Request $request, $id_pengadaan)
    {
        $request->validate([
            'idbarang' => 'required|integer', 
            'jumlah' => 'required|integer|min:1', 
            'harga_satuan' => 'required|integer|min:0' 
        ]);
        
        DB::insert("
            INSERT INTO detail_pengadaan (idpengadaan, idbarang, jumlah, harga_satuan)
            VALUES (?, ?, ?, ?)
        ", [
            $id_pengadaan,
            $request->idbarang,
            $request->jumlah,
            $request->harga_satuan 
        ]);
        
        return redirect()->route('transaksi.pengadaan.show', $id_pengadaan)
                         ->with('success', 'Barang berhasil ditambahkan ke PO.');
    }

    /**
     * DELETE: Menghapus 1 item detail dari PO
     */
    public function destroyDetailPengadaan($id_detail)
    {
        $detail = DB::selectOne("SELECT idpengadaan FROM detail_pengadaan WHERE iddetail_pengadaan = ?", [$id_detail]);
        
        if (!$detail) { abort(404); }
        $id_pengadaan = $detail->idpengadaan;
        
        DB::delete("DELETE FROM detail_pengadaan WHERE iddetail_pengadaan = ?", [$id_detail]);

        return redirect()->route('transaksi.pengadaan.show', $id_pengadaan)
                         ->with('success', 'Item barang berhasil dihapus dari PO.');
    }

    /**
     * DELETE: Menghapus 1 PO (Header dan Detail)
     */
    public function destroyPengadaan($id)
    {
        try {
            DB::beginTransaction();
            DB::delete("DELETE FROM detail_pengadaan WHERE idpengadaan = ?", [$id]);
            DB::delete("DELETE FROM pengadaan WHERE idpengadaan = ?", [$id]);
            DB::commit();
            
            return redirect()->route('transaksi.pengadaan')
                             ->with('success', 'PO berhasil dihapus.');
                             
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->route('transaksi.pengadaan')
                             ->withErrors(['error' => 'PO tidak bisa dihapus, mungkin sudah memiliki data penerimaan.']);
        }
    }

    /**
     * READ: Menampilkan halaman utama Penerimaan (filter SP)
     */
    public function penerimaan(Request $request)
    {
        $status = $request->input('status');

        $query = DB::table("view_laporan_penerimaan");

        if ($status) {
            // 'status_penerimaan' adalah nama kolom di 'view_header_penerimaan'
            $query->where('status_penerimaan', $status);
        }
        
        $data = $query->orderBy('tgl_terima', 'DESC')->get();
        
        return view('transaksi.penerimaan', [
            'data_penerimaan' => $data, 
            'status_terpilih' => $status
        ]);
    }
}