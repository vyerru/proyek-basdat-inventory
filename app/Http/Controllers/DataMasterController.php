<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // <-- Import Auth
use Illuminate\Database\QueryException; // <-- Import untuk error handling

class DataMasterController extends Controller
{
    // ==================================================================
    // READ (Masih menggunakan VIEW dari DB)
    // ==================================================================

    public function barang(Request $request)
    {
        $status = $request->input('status', 'Aktif'); 
        $viewName = ($status == 'Aktif') ? 'view_barang_aktif' : 'view_semua_barang';
        $data = DB::select("SELECT * FROM $viewName");
        return view('master.barang', ['data_barang' => $data, 'status_terpilih' => $status]);
    }

    public function satuan(Request $request)
    {
        $status = $request->input('status', 'Aktif');
        $viewName = ($status == 'Aktif') ? 'view_satuan_aktif' : 'view_semua_satuan';
        $data = DB::select("SELECT * FROM $viewName");
        return view('master.satuan', ['data_satuan' => $data, 'status_terpilih' => $status]);
    }

    
    // (BARU)
    public function vendor(Request $request)
    {
        $status = $request->input('status', 'Aktif');
        $viewName = ($status == 'Aktif') ? 'view_vendor_aktif' : 'view_semua_vendor';
        $data = DB::select("SELECT * FROM $viewName");
        return view('master.vendor', ['data_vendor' => $data, 'status_terpilih' => $status]);
    }

    // (BARU)
    public function margin(Request $request)
    {
        $status = $request->input('status', 'Aktif');
        $viewName = ($status == 'Aktif') ? 'view_margin_aktif' : 'view_semua_margin';
        $data = DB::select("SELECT * FROM $viewName");
        return view('master.margin', ['data_margin' => $data, 'status_terpilih' => $status]);
    }
    
    public function user()
    {
        $data = DB::select("SELECT * FROM view_user_role");
        return view('master.user', ['data_user' => $data]);
    }

    // (BARU)
    public function role()
    {
        $data = DB::select("SELECT * FROM view_semua_role");
        return view('master.role', ['data_role' => $data]);
    }

    // ==================================================================
    // CREATE / DELETE (Menggunakan RAW QUERY)
    // ==================================================================

    // --- BARANG ---
    public function createBarang()
    {
        $satuan = DB::select("SELECT * FROM view_satuan_aktif");
        return view('master.create.barang_create', ['satuan_list' => $satuan]);
    }

    public function storeBarang(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:45',
            'idsatuan' => 'required|integer',
            'harga' => 'required|integer',
            'jenis' => 'required|string|max:1',
        ]);
        
        DB::insert("
            INSERT INTO barang (nama, idsatuan, harga, jenis, status) 
            VALUES (?, ?, ?, ?, 1)
        ", [$request->nama, $request->idsatuan, $request->harga, $request->jenis]);

        return redirect()->route('master.barang')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function destroyBarang($id)
    {
        DB::update("UPDATE barang SET status = 0 WHERE idbarang = ?", [$id]);
        return redirect()->route('master.barang')->with('success', 'Barang berhasil dinonaktifkan.');
    }

    // --- SATUAN ---
    public function createSatuan()
    {
        return view('master.create.satuan_create');
    }

    public function storeSatuan(Request $request)
    {
        $request->validate(['nama_satuan' => 'required|string|max:45']);
        
        DB::insert("
            INSERT INTO satuan (nama_satuan, status) 
            VALUES (?, 1)
        ", [$request->nama_satuan]);

        return redirect()->route('master.satuan')->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function destroySatuan($id)
    {
        DB::update("UPDATE satuan SET status = 0 WHERE idsatuan = ?", [$id]);
        return redirect()->route('master.satuan')->with('success', 'Satuan berhasil dinonaktifkan.');
    }

    // --- (BARU) VENDOR ---
    public function createVendor()
    {
        return view('master.create.vendor_create');
    }

    public function storeVendor(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|string|max:1',
        ]);
        
        // Status default 'A' (Aktif)
        DB::insert("
            INSERT INTO vendor (nama_vendor, badan_hukum, status) 
            VALUES (?, ?, 'A')
        ", [$request->nama_vendor, $request->badan_hukum]);
        
        return redirect()->route('master.vendor')->with('success', 'Vendor berhasil ditambahkan.');
    }

    public function destroyVendor($id)
    {
        // Soft Delete
        DB::update("UPDATE vendor SET status = 'N' WHERE idvendor = ?", [$id]);
        return redirect()->route('master.vendor')->with('success', 'Vendor berhasil dinonaktifkan.');
    }

    // --- (BARU) MARGIN ---
    public function createMargin()
    {
        return view('master.create.margin_create');
    }

    public function storeMargin(Request $request)
    {
        $request->validate(['persen' => 'required|numeric']);
        
        $iduser_yang_login = Auth::id(); 
        
        // Status '1' & 'iduser' otomatis terisi
        DB::insert("
            INSERT INTO margin_penjualan (persen, status, iduser, created_at, updated_at) 
            VALUES (?, 1, ?, NOW(), NOW())
        ", [$request->persen, $iduser_yang_login]);
        
        return redirect()->route('master.margin')->with('success', 'Margin berhasil ditambahkan.');
    }

    public function destroyMargin($id)
    {
        // Soft Delete
        DB::statement("UPDATE margin_penjualan SET status = 0 WHERE idmargin_penjualan = ?", [$id]);
        return redirect()->route('master.margin')->with('success', 'Margin berhasil dinonaktifkan.');
    }

    // --- (BARU) ROLE ---
    public function createRole()
    {
        return view('master.create.role_create');
    }

    public function storeRole(Request $request)
    {
        $request->validate(['nama_role' => 'required|string|max:100|unique:role,nama_role']);
        
        DB::insert("
            INSERT INTO role (nama_role) VALUES (?)
        ", [$request->nama_role]);
        
        return redirect()->route('master.role')->with('success', 'Role berhasil ditambahkan.');
    }

    public function destroyRole($id)
    {
        try {
            // Hard Delete (Tabel role tidak punya status)
            DB::delete("DELETE FROM role WHERE idrole = ?", [$id]);
            return redirect()->route('master.role')->with('success', 'Role berhasil dihapus.');
        } catch (QueryException $e) {
            // Error jika role masih dipakai user
            return redirect()->route('master.role')->withErrors(['error' => 'Role tidak bisa dihapus karena masih digunakan oleh user.']);
        }
    }
}