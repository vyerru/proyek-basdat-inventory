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
        $status = $request->input('status');
        $query = DB::table('view_laporan_pengadaan');
        if ($status) {
            $query->where('status_po', $status);
        }
        $data = $query->orderBy('tgl_pengadaan', 'DESC')->get();

        $vendors = DB::select("SELECT * FROM view_vendor_aktif");

        return view('transaksi.pengadaan', [
            'data_pengadaan' => $data,
            'status_terpilih' => $status,
            'vendors' => $vendors
        ]);
    }

    // --- CREATE: Header PO ---
    public function storePengadaan(Request $request)
    {
        $request->validate(['vendor_idvendor' => 'required|integer']);
        $id_user = Auth::id();

        // Status Default 'A' (Proses)
        DB::insert("
            INSERT INTO pengadaan (user_iduser, vendor_idvendor, status, subtotal_nilai, ppn, total_nilai, timestamp)
            VALUES (?, ?, 'A', 0, 0, 0, NOW())
        ", [$id_user, $request->vendor_idvendor]);

        $newId = DB::getPdo()->lastInsertId();

        // Redirect ke halaman PROSES
        return redirect()->route('transaksi.pengadaan.proses', $newId);
    }

    // --- PAGE: Halaman Proses (Edit Mode) ---
    public function prosesPengadaan($id)
    {
        $po = DB::selectOne("SELECT * FROM view_laporan_pengadaan WHERE idpengadaan = ?", [$id]);

        // Jika status sudah 'S' (Selesai), tidak boleh edit lagi -> lempar ke Read Only
        if ($po->status_po == 'Selesai') {
            return redirect()->route('transaksi.pengadaan.show', $id);
        }

        $details = DB::select("
            SELECT d.*, b.nama AS nama_barang 
            FROM detail_pengadaan d JOIN barang b ON d.idbarang = b.idbarang
            WHERE d.idpengadaan = ?", [$id]);

        // Ambil Barang & Harga untuk Modal
        $barang_list = DB::select("SELECT idbarang, nama_barang, harga FROM view_barang_aktif");

        return view('transaksi.pengadaan_proses', [
            'po' => $po,
            'details' => $details,
            'barang_list' => $barang_list
        ]);
    }

    // --- ACTION: Tambah Detail (Panggil SP) ---
    public function storeDetailPengadaan(Request $request, $id_pengadaan)
    {
        $request->validate(['idbarang' => 'required|integer', 'jumlah' => 'required|min:1']);

        try {
            // Harga diambil otomatis oleh SP di database
            DB::statement("CALL sp_tambah_detail_pengadaan(?, ?, ?)", [
                $id_pengadaan,
                $request->idbarang,
                $request->jumlah
            ]);
            return redirect()->route('transaksi.pengadaan.proses', $id_pengadaan)
                ->with('success', 'Barang ditambahkan.');
        } catch (QueryException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // --- ACTION: Hapus Detail ---
    public function destroyDetailPengadaan($id_detail)
    {
        $detail = DB::selectOne("SELECT idpengadaan FROM detail_pengadaan WHERE iddetail_pengadaan = ?", [$id_detail]);
        if ($detail) {
            DB::delete("DELETE FROM detail_pengadaan WHERE iddetail_pengadaan = ?", [$id_detail]);
            return redirect()->route('transaksi.pengadaan.proses', $detail->idpengadaan)
                ->with('success', 'Item dihapus.');
        }
        return back();
    }

    // --- ACTION: Simpan / Selesai Input ---
    public function simpanPengadaan($id)
    {
        // REVISI: Status tetap 'A', hanya redirect ke halaman utama
        return redirect()->route('transaksi.pengadaan')
            ->with('success', 'PO #' . $id . ' berhasil disimpan. Menunggu penerimaan barang.');
    }

    // --- PAGE: Halaman Read-Only ---
    public function showPengadaan($id)
    {
        $po = DB::selectOne("SELECT * FROM view_laporan_pengadaan WHERE idpengadaan = ?", [$id]);
        $details = DB::select("
            SELECT d.*, b.nama AS nama_barang 
            FROM detail_pengadaan d JOIN barang b ON d.idbarang = b.idbarang
            WHERE d.idpengadaan = ?", [$id]);

        return view('transaksi.pengadaan_show', ['po' => $po, 'details' => $details]);
    }

    // --- ACTION: Hapus PO ---
    public function destroyPengadaan($id)
    {
        DB::beginTransaction();
        try {
            DB::delete("DELETE FROM detail_pengadaan WHERE idpengadaan = ?", [$id]);
            DB::delete("DELETE FROM pengadaan WHERE idpengadaan = ?", [$id]);
            DB::commit();
            return redirect()->route('transaksi.pengadaan')->with('success', 'PO dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus PO.']);
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

        // Status Default 'P' (Proses)
        DB::insert("
            INSERT INTO penerimaan (idpengadaan, iduser, status, created_at)
            VALUES (?, ?, 'P', NOW())
        ", [$request->idpengadaan, $id_user]);

        $newId = DB::getPdo()->lastInsertId();

        // Redirect ke Halaman PROSES
        return redirect()->route('transaksi.penerimaan.proses', $newId);
    }

    public function prosesPenerimaan($id)
    {
        // 1. Ambil Header Penerimaan + Info PO
        $penerimaan = DB::selectOne("
            SELECT p.*, po.status as status_po, v.nama_vendor, u.username as penerima
            FROM penerimaan p
            JOIN pengadaan po ON p.idpengadaan = po.idpengadaan
            JOIN vendor v ON po.vendor_idvendor = v.idvendor
            JOIN user u ON p.iduser = u.iduser
            WHERE p.idpenerimaan = ?", [$id]);

        // Jika status bukan 'P', lempar ke Read-Only
        if ($penerimaan->status != 'P') {
            return redirect()->route('transaksi.penerimaan.show', $id);
        }

        // 2. Ambil Barang yang SUDAH Diterima di sesi ini
        $details = DB::select("
            SELECT dp.*, b.nama AS nama_barang 
            FROM detail_penerimaan dp
            JOIN barang b ON dp.barang_idbarang = b.idbarang
            WHERE dp.idpenerimaan = ?", [$id]);

        // 3. Ambil Daftar Barang dari PO Terkait (Untuk Dropdown Modal)
        // Logic: Tampilkan barang yang ada di PO ini + Harga Master (sesuai request)
       $barang_po = DB::select("
            SELECT 
                dp.idbarang, 
                b.nama, 
                b.harga as harga_master,
                dp.jumlah as qty_pesan,
                fn_get_sisa_penerimaan(dp.idpengadaan, dp.idbarang) as sisa
            FROM detail_pengadaan dp
            JOIN barang b ON dp.idbarang = b.idbarang
            WHERE dp.idpengadaan = ?
            AND dp.idbarang NOT IN (
                SELECT barang_idbarang 
                FROM detail_penerimaan 
                WHERE idpenerimaan = ?
            )
            HAVING sisa > 0
        ", [$penerimaan->idpengadaan, $id]);

        return view('transaksi.penerimaan_proses', [
            'penerimaan' => $penerimaan,
            'details' => $details,
            'barang_list' => $barang_po
        ]);
    }

    /**
     * ACTION: Simpan Item Penerimaan (Panggil SP)
     */
    public function storeDetailPenerimaan(Request $request, $id)
    {
        $request->validate([
            'idbarang' => 'required|integer',
            'jumlah_terima' => 'required|integer|min:1'
        ]);

        try {
            // SP ini otomatis ambil harga dari master barang & validasi sisa PO
            DB::statement("CALL sp_tambah_detail_penerimaan(?, ?, ?)", [
                $id,
                $request->idbarang,
                $request->jumlah_terima
            ]);

            return redirect()->route('transaksi.penerimaan.proses', $id)
                ->with('success', 'Barang diterima.');

        } catch (QueryException $e) {
            // Tangkap error custom dari SP (misal: Jumlah berlebih)
            return back()->withErrors(['error' => $e->getMessage(), 'ERROR_LIMIT']);
        }
    }

    /**
     * ACTION: Hapus Item Penerimaan
     */
    public function destroyPenerimaanDetail($id_detail)
    {
        $detail = DB::selectOne("SELECT idpenerimaan FROM detail_penerimaan WHERE iddetail_penerimaan = ?", [$id_detail]);
        if ($detail) {
            DB::delete("DELETE FROM detail_penerimaan WHERE iddetail_penerimaan = ?", [$id_detail]);
            // Trigger di DB akan otomatis update status PO (jika sebelumnya Selesai jadi Aktif lagi)
            return redirect()->route('transaksi.penerimaan.proses', $detail->idpenerimaan)
                ->with('success', 'Item dihapus.');
        }
        return back();
    }

    /**
     * ACTION: Simpan Permanen (Ubah Status jadi 'Diterima')
     */
    public function simpanPenerimaan($id)
    {
        // Ubah status jadi 'S' (Selesai/Diterima) agar tidak bisa diedit lagi
        DB::update("UPDATE penerimaan SET status = 'S' WHERE idpenerimaan = ?", [$id]);

        return redirect()->route('transaksi.penerimaan')
            ->with('success', 'Penerimaan selesai dicatat.');
    }

    /**
     * PAGE: Halaman Read-Only
     */
    public function showPenerimaan($id)
    {
        $penerimaan = DB::selectOne("
            SELECT p.*, v.nama_vendor, u.username as penerima
            FROM penerimaan p
            JOIN pengadaan po ON p.idpengadaan = po.idpengadaan
            JOIN vendor v ON po.vendor_idvendor = v.idvendor
            JOIN user u ON p.iduser = u.iduser
            WHERE p.idpenerimaan = ?", [$id]);

        $details = DB::select("
            SELECT dp.*, b.nama AS nama_barang 
            FROM detail_penerimaan dp
            JOIN barang b ON dp.barang_idbarang = b.idbarang
            WHERE dp.idpenerimaan = ?", [$id]);

        return view('transaksi.penerimaan_show', [
            'penerimaan' => $penerimaan,
            'details' => $details
        ]);
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
        if (!$margin)
            return back()->withErrors(['error' => 'Margin belum disetting.']);

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
                $id,
                $request->idbarang,
                $request->jumlah
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