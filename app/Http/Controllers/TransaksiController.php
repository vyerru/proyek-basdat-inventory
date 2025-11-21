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
        $status = $request->input('status');

        // 2. Buat query dasar ke VIEW Anda
        $query = DB::table('view_laporan_pengadaan');
        if ($status) {
            $query->where('status_po', $status);
        }
        $data = $query->orderBy('tgl_pengadaan', 'DESC')->get();

        // (BARU) Ambil data vendor untuk dropdown di modal create
        $vendors = DB::select("SELECT * FROM view_vendor_aktif");

        // 5. Kirim data ke view
        return view('transaksi.pengadaan', [
            'data_pengadaan' => $data,
            'status_terpilih' => $status,
            'vendors' => $vendors  // <-- Kirim data vendor ke view
        ]);
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

        if (!$po_header) {
            abort(404);
        }

        // Ambil data Detail PO yang sudah ada
        $po_detail = DB::select(
            "SELECT d.*, b.nama AS nama_barang
             FROM detail_pengadaan d
             JOIN barang b ON d.idbarang = b.idbarang
             WHERE d.idpengadaan = ?",
            [$id]
        );

        // (PENTING) Ambil daftar barang untuk dropdown di modal
        // Kita ambil semua info barang aktif
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
        // (Revisi) Validasi hanya idbarang dan jumlah
        $request->validate([
            'idbarang' => 'required|integer', // (Poin 2.e)
            'jumlah' => 'required|integer|min:1', // (Poin 2.c)
        ]);

        // (REVISI) Panggil Stored Procedure (Metode Baru)
        DB::statement("CALL sp_tambah_detail_pengadaan(?, ?, ?)", [
            $id_pengadaan,
            $request->idbarang,
            $request->jumlah
        ]);

        /* * SAAT INI DATABASE OTOMATIS BEKERJA:
         * 1. SP mengambil harga dari tabel 'barang' (Revisi 2.b)
         * 2. SP melakukan INSERT
         * 3. Trigger 'BEFORE INSERT' menghitung sub_total (2.d)
         * 4. Trigger 'AFTER INSERT' menghitung total header (1.e, 1.f, 1.g)
         */

        return redirect()->route('transaksi.pengadaan.show', $id_pengadaan)
            ->with('success', 'Barang berhasil ditambahkan ke PO.');
    }

    /**
     * DELETE: Menghapus 1 item detail dari PO
     */
    public function destroyDetailPengadaan($id_detail)
    {
        $detail = DB::selectOne("SELECT idpengadaan FROM detail_pengadaan WHERE iddetail_pengadaan = ?", [$id_detail]);

        if (!$detail) {
            abort(404);
        }
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
                ->withErrors(['error' => 'Pengadaan tidak bisa dihapus, mungkin sudah memiliki data penerimaan.']);
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