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
        // Mengambil data dari View Laporan Penerimaan (Header)
        // Kita group by idpenerimaan agar tampil unik per transaksi
        $data = DB::table('view_laporan_penerimaan')
            ->select('idpenerimaan', 'tgl_terima', 'referensi_po_id', 'penerima', 'status_penerimaan')
            ->groupBy('idpenerimaan', 'tgl_terima', 'referensi_po_id', 'penerima', 'status_penerimaan')
            ->orderBy('tgl_terima', 'desc')
            ->get();

        // Ambil daftar PO Aktif/Selesai untuk dropdown "Buat Penerimaan Baru"
        $po_list = DB::select("
        SELECT idpengadaan, nama_vendor 
        FROM view_laporan_pengadaan 
        WHERE status_po = 'Aktif'
        GROUP BY idpengadaan, nama_vendor 
        ORDER BY idpengadaan DESC
    ");
        return view('transaksi.penerimaan', [
            'data_penerimaan' => $data,
            'po_list' => $po_list
        ]);
    }

    public function storePenerimaan(Request $request)
    {
        $request->validate(['idpengadaan' => 'required|integer']);

        $id_user = Auth::id();

        // [UBAH] Status default 'P' (Proses Input)
        DB::insert("
            INSERT INTO penerimaan (idpengadaan, iduser, status, created_at)
            VALUES (?, ?, 'P', NOW())
        ", [$request->idpengadaan, $id_user]);

        $newId = DB::getPdo()->lastInsertId();

        return redirect()->route('transaksi.penerimaan.show', $newId)
            ->with('success', 'Header penerimaan dibuat. Silakan input barang satu per satu.');
    }

    public function lockPenerimaan($id)
    {
        // Update status menjadi 'D' (Diterima)
        DB::update("UPDATE penerimaan SET status = 'D' WHERE idpenerimaan = ?", [$id]);

        return redirect()->route('transaksi.penerimaan.show', $id)
            ->with('success', 'Penerimaan berhasil diselesaikan dan dikunci.');
    }

    public function showPenerimaan($id)
    {
        // Ambil Header
        $header = DB::selectOne("
            SELECT p.*, u.username, v.nama_vendor 
            FROM penerimaan p
            JOIN user u ON p.iduser = u.iduser
            JOIN pengadaan po ON p.idpengadaan = po.idpengadaan
            JOIN vendor v ON po.vendor_idvendor = v.idvendor
            WHERE p.idpenerimaan = ?
        ", [$id]);

        if (!$header)
            abort(404);

        // Ambil Detail Barang yang sudah diterima
        $details = DB::select("
            SELECT dp.*, b.nama AS nama_barang 
            FROM detail_penerimaan dp
            JOIN barang b ON dp.barang_idbarang = b.idbarang
            WHERE dp.idpenerimaan = ?
        ", [$id]);

        // Ambil Daftar Barang yang ADA di PO ini (untuk dropdown modal)
        // Kita hanya boleh menerima barang yang dipesan di PO terkait
        $barang_po = DB::select("
            SELECT dp.idbarang, b.nama
            FROM detail_pengadaan dp
            JOIN barang b ON dp.idbarang = b.idbarang
            WHERE dp.idpengadaan = ?
        ", [$header->idpengadaan]);

        return view('transaksi.penerimaan_show', [
            'penerimaan' => $header,
            'details' => $details,
            'barang_list' => $barang_po
        ]);
    }

    public function storeDetailPenerimaan(Request $request, $id)
    {
        $request->validate([
            'idbarang' => 'required|integer',
            'jumlah_terima' => 'required|integer|min:1',
            'harga_terima' => 'required|integer|min:0',
        ]);

        try {
            // 1. Coba Panggil SP
            DB::statement("CALL sp_tambah_detail_penerimaan(?, ?, ?, ?)", [
                $id,
                $request->idbarang,
                $request->jumlah_terima,
                $request->harga_terima
            ]);

            return redirect()->route('transaksi.penerimaan.show', $id)
                ->with('success', 'Barang berhasil diterima.');

        } catch (QueryException $e) {
            // 2. Tangkap Error
            $errorCode = $e->getMessage();

            // Cek apakah errornya adalah 'ERROR_LIMIT' yang kita buat di SP
            if (str_contains($errorCode, 'ERROR_LIMIT')) {

                // A. Ambil ID Pengadaan dari tabel penerimaan (untuk parameter function)
                $id_pengadaan = DB::table('penerimaan')->where('idpenerimaan', $id)->value('idpengadaan');

                // B. Panggil Function Database untuk ambil sisa stok
                $sisa = DB::selectOne("SELECT fn_get_sisa_penerimaan(?, ?) as sisa", [
                    $id_pengadaan,
                    $request->idbarang
                ])->sisa;

                // C. Susun Pesan Error di Laravel
                $pesan = "Gagal! Jumlah diterima melebihi pesanan. Sisa yang belum diterima hanya: <strong>{$sisa}</strong> unit.";

                return redirect()->back()->withErrors(['error' => $pesan])->withInput();
            }

            // Error lain (misal koneksi putus, dll)
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan database: ' . $e->getMessage()]);
        }
    }

    public function penjualan(Request $request)
    {
        // Ambil data penjualan dari view yang sudah ada (view_laporan_penjualan)
        // Kita group by header agar tidak duplikat
        $data = DB::table('view_laporan_penjualan')
            ->select('idpenjualan', 'tgl_penjualan', 'kasir', 'total_transaksi')
            ->groupBy('idpenjualan', 'tgl_penjualan', 'kasir', 'total_transaksi')
            ->orderBy('tgl_penjualan', 'desc')
            ->get();

        return view('transaksi.penjualan', ['data_penjualan' => $data]);
    }

    /**
     * CREATE: Header Penjualan
     */
 public function storePenjualan(Request $request)
    {
        $id_user = Auth::id();
        
        // Pastikan ada margin aktif
        $margin = DB::selectOne("SELECT idmargin_penjualan FROM margin_penjualan WHERE status = 1 LIMIT 1");
        if (!$margin) return back()->withErrors(['error' => 'Margin belum disetting.']);

        // Buat Header
        DB::insert("
            INSERT INTO penjualan (created_at, subtotal_nilai, ppn, total_nilai, iduser, idmargin_penjualan)
            VALUES (NOW(), 0, 0, 0, ?, ?)
        ", [$id_user, $margin->idmargin_penjualan]);

        $newId = DB::getPdo()->lastInsertId();

        // Redirect ke Mode Edit
        return redirect()->route('transaksi.penjualan.proses', $newId);
    }

    /**
     * PAGE: Halaman Proses Input (Edit Mode)
     */
    public function prosesPenjualan($id)
    {
        $penjualan = DB::selectOne("SELECT * FROM penjualan WHERE idpenjualan = ?", [$id]);
        
        // Ambil Detail
        $details = DB::select("
            SELECT dp.*, b.nama AS nama_barang 
            FROM detail_penjualan dp 
            JOIN barang b ON dp.idbarang = b.idbarang 
            WHERE penjualan_idpenjualan = ?", [$id]);

        // Ambil Barang & Stok untuk Modal (Harga dihitung live nanti saat insert)
        $barang_list = DB::select("
            SELECT idbarang, nama, fn_hitung_stok(idbarang) as stok 
            FROM barang WHERE status = 1");

        return view('transaksi.penjualan_proses', [
            'penjualan' => $penjualan,
            'details' => $details,
            'barang_list' => $barang_list
        ]);
    }

    /**
     * ACTION: Simpan Item (Panggil SP Baru)
     */
    public function storeDetailPenjualan(Request $request, $id)
    {
        $request->validate(['idbarang' => 'required', 'jumlah' => 'required|min:1']);

        try {
            // SP ini sekarang otomatis menghitung harga jual
            DB::statement("CALL sp_tambah_detail_penjualan(?, ?, ?)", [
                $id, $request->idbarang, $request->jumlah
            ]);
            return redirect()->route('transaksi.penjualan.proses', $id)->with('success', 'Barang ditambahkan.');
        } catch (QueryException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * PAGE: Halaman Read-Only (Detail Selesai)
     */
    public function showPenjualan($id)
    {
        // Sama seperti proses, tapi view-nya beda (tanpa tombol edit)
        $penjualan = DB::selectOne("SELECT * FROM view_laporan_penjualan WHERE idpenjualan = ?", [$id]);
        $details = DB::select("
            SELECT dp.*, b.nama AS nama_barang 
            FROM detail_penjualan dp 
            JOIN barang b ON dp.idbarang = b.idbarang 
            WHERE penjualan_idpenjualan = ?", [$id]);

        return view('transaksi.penjualan_show', [
            'penjualan' => $penjualan,
            'details' => $details
        ]);
    }
}