<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DataMasterController extends Controller
{
    // ================== 1. BARANG ==================
    public function barang(Request $request)
    {
        $status = $request->input('status', 'Aktif');
        $viewName = ($status == 'Aktif') ? 'view_barang_aktif' : 'view_semua_barang';
        $data = DB::select("SELECT * FROM $viewName");
        return view('master.barang', ['data_barang' => $data, 'status_terpilih' => $status]);
    }

    public function createBarang()
    {
        $satuan = DB::select("SELECT * FROM view_satuan_aktif");
        return view('master.create.barang_create', ['satuan_list' => $satuan]);
    }

    public function storeBarang(Request $request)
    {
        $request->validate(['nama' => 'required', 'idsatuan' => 'required', 'harga' => 'required', 'jenis' => 'required']);
        DB::insert(
            "INSERT INTO barang (nama, idsatuan, harga, jenis, status) VALUES (?, ?, ?, ?, 1)",
            [$request->nama, $request->idsatuan, $request->harga, $request->jenis]
        );
        return redirect()->route('master.barang')->with('success', 'Barang ditambahkan.');
    }

    // [BARU] Edit Barang
    public function editBarang($id)
    {
        $barang = DB::selectOne("SELECT * FROM barang WHERE idbarang = ?", [$id]);
        $satuan = DB::select("SELECT * FROM view_satuan_aktif");
        return view('master.edit.barang_edit', ['barang' => $barang, 'satuan_list' => $satuan]);
    }

    // [BARU] Update Barang
    public function updateBarang(Request $request, $id)
    {
        DB::update(
            "UPDATE barang SET nama=?, idsatuan=?, harga=?, jenis=? WHERE idbarang=?",
            [$request->nama, $request->idsatuan, $request->harga, $request->jenis, $id]
        );
        return redirect()->route('master.barang')->with('success', 'Barang diperbarui.');
    }

    public function destroyBarang($id)
    {
        DB::update("UPDATE barang SET status = 0 WHERE idbarang = ?", [$id]);
        return redirect()->route('master.barang')->with('success', 'Barang dinonaktifkan.');
    }


    // ================== 2. SATUAN ==================
    public function satuan(Request $request)
    {
        $status = $request->input('status', 'Aktif');
        $viewName = ($status == 'Aktif') ? 'view_satuan_aktif' : 'view_semua_satuan';
        $data = DB::select("SELECT * FROM $viewName");
        return view('master.satuan', ['data_satuan' => $data, 'status_terpilih' => $status]);
    }

    public function createSatuan()
    {
        return view('master.create.satuan_create');
    }

    public function storeSatuan(Request $request)
    {
        DB::insert("INSERT INTO satuan (nama_satuan, status) VALUES (?, 1)", [$request->nama_satuan]);
        return redirect()->route('master.satuan')->with('success', 'Satuan ditambahkan.');
    }

    // [BARU] Edit Satuan
    public function editSatuan($id)
    {
        $satuan = DB::selectOne("SELECT * FROM satuan WHERE idsatuan = ?", [$id]);
        return view('master.edit.satuan_edit', ['satuan' => $satuan]);
    }

    // [BARU] Update Satuan
    public function updateSatuan(Request $request, $id)
    {
        DB::update("UPDATE satuan SET nama_satuan=? WHERE idsatuan=?", [$request->nama_satuan, $id]);
        return redirect()->route('master.satuan')->with('success', 'Satuan diperbarui.');
    }

    public function destroySatuan($id)
    {
        DB::update("UPDATE satuan SET status = 0 WHERE idsatuan = ?", [$id]);
        return redirect()->route('master.satuan')->with('success', 'Satuan dinonaktifkan.');
    }


    // ================== 3. VENDOR ==================
    public function vendor(Request $request)
    {
        $status = $request->input('status', 'Aktif');
        $viewName = ($status == 'Aktif') ? 'view_vendor_aktif' : 'view_semua_vendor';
        $data = DB::select("SELECT * FROM $viewName");
        return view('master.vendor', ['data_vendor' => $data, 'status_terpilih' => $status]);
    }

    public function createVendor()
    {
        return view('master.create.vendor_create');
    }

    public function storeVendor(Request $request)
    {
        DB::insert(
            "INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES (?, ?, 'A')",
            [$request->nama_vendor, $request->badan_hukum]
        );
        return redirect()->route('master.vendor')->with('success', 'Vendor ditambahkan.');
    }

    // [BARU] Edit Vendor
    public function editVendor($id)
    {
        $vendor = DB::selectOne("SELECT * FROM vendor WHERE idvendor = ?", [$id]);
        return view('master.edit.vendor_edit', ['vendor' => $vendor]);
    }

    // [BARU] Update Vendor
    public function updateVendor(Request $request, $id)
    {
        DB::update(
            "UPDATE vendor SET nama_vendor=?, badan_hukum=? WHERE idvendor=?",
            [$request->nama_vendor, $request->badan_hukum, $id]
        );
        return redirect()->route('master.vendor')->with('success', 'Vendor diperbarui.');
    }

    public function destroyVendor($id)
    {
        DB::update("UPDATE vendor SET status = 'N' WHERE idvendor = ?", [$id]);
        return redirect()->route('master.vendor')->with('success', 'Vendor dinonaktifkan.');
    }


    // ================== 4. MARGIN ==================
    public function margin(Request $request)
    {
        $status = $request->input('status', 'Aktif');
        $viewName = ($status == 'Aktif') ? 'view_margin_aktif' : 'view_semua_margin';
        $data = DB::select("SELECT * FROM $viewName");
        return view('master.margin', ['data_margin' => $data, 'status_terpilih' => $status]);
    }

    public function createMargin()
    {
        return view('master.create.margin_create');
    }

    public function storeMargin(Request $request)
    {
        $request->validate(['persen' => 'required|numeric']);

        $iduser_yang_login = Auth::id();

        try {
            // Mulai Transaksi
            DB::beginTransaction();

            // 1. Nonaktifkan semua margin yang statusnya '1' (Aktif)
            DB::update("UPDATE margin_penjualan SET status = 0 WHERE status = 1");

            // 2. Insert margin baru dengan status '1' (Aktif)
            DB::insert("
                INSERT INTO margin_penjualan (persen, status, iduser, created_at, updated_at) 
                VALUES (?, 1, ?, NOW(), NOW())
            ", [
                $request->persen,
                $iduser_yang_login
            ]);

            // Simpan perubahan
            DB::commit();

            return redirect()->route('master.margin')
                ->with('success', 'Margin baru aktif! Margin sebelumnya telah dinonaktifkan.');

        } catch (\Exception $e) {
            // Batalkan jika ada error
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memproses margin: ' . $e->getMessage()]);
        }
    }

    // [BARU] Edit Margin
    public function editMargin($id)
    {
        $margin = DB::selectOne("SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?", [$id]);
        return view('master.edit.margin_edit', ['margin' => $margin]);
    }

    // [BARU] Update Margin (Hanya update persen, status dan user tidak diubah)
    public function updateMargin(Request $request, $id)
    {
        $iduser = Auth::id();
        DB::update(
            "UPDATE margin_penjualan SET persen=?, iduser=?, updated_at=NOW() WHERE idmargin_penjualan=?",
            [$request->persen, $iduser, $id]
        );
        return redirect()->route('master.margin')->with('success', 'Margin diperbarui.');
    }

    public function destroyMargin($id)
    {
        DB::update("UPDATE margin_penjualan SET status = 0 WHERE idmargin_penjualan = ?", [$id]);
        return redirect()->route('master.margin')->with('success', 'Margin dinonaktifkan.');
    }


    // ================== 5. ROLE ==================
    public function role()
    {
        $data = DB::select("SELECT * FROM view_semua_role");
        return view('master.role', ['data_role' => $data]);
    }

    public function createRole()
    {
        return view('master.create.role_create');
    }

    public function storeRole(Request $request)
    {
        DB::insert("INSERT INTO role (nama_role) VALUES (?)", [$request->nama_role]);
        return redirect()->route('master.role')->with('success', 'Role ditambahkan.');
    }

    // [BARU] Edit Role
    public function editRole($id)
    {
        $role = DB::selectOne("SELECT * FROM role WHERE idrole = ?", [$id]);
        return view('master.edit.role_edit', ['role' => $role]);
    }

    // [BARU] Update Role
    public function updateRole(Request $request, $id)
    {
        DB::update("UPDATE role SET nama_role=? WHERE idrole=?", [$request->nama_role, $id]);
        return redirect()->route('master.role')->with('success', 'Role diperbarui.');
    }

    public function destroyRole($id)
    {
        try {
            DB::delete("DELETE FROM role WHERE idrole = ?", [$id]); // Hard Delete
            return redirect()->route('master.role')->with('success', 'Role dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Role tidak bisa dihapus, sedang digunakan.']);
        }
    }

    // ================== 6. USER ==================
    public function user()
    {
        $data = DB::select("SELECT * FROM view_user_role");
        return view('master.user', ['data_user' => $data]);
    }
}