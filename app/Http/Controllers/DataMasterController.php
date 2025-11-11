<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataMasterController extends Controller
{
    // Menampilkan Data Barang (Aktif / Tidak Aktif)
    public function barang(Request $request)
    {
        $status = $request->input('status', 'Aktif'); // Default 'Aktif'
        
        // Memilih VIEW berdasarkan filter
        $viewName = ($status == 'Aktif') ? 'view_barang_aktif' : 'view_semua_barang';
        
        $data = DB::select("SELECT * FROM $viewName");
        
        return view('Master.barang', ['data_barang' => $data, 'status_terpilih' => $status]);
    }

    // Menampilkan Data Satuan (Aktif / Tidak Aktif)
    public function satuan(Request $request)
    {
        $status = $request->input('status', 'Aktif');
        $viewName = ($status == 'Aktif') ? 'view_satuan_aktif' : 'view_semua_satuan';
        $data = DB::select("SELECT * FROM $viewName");
        return view('Master.satuan', ['data_satuan' => $data, 'status_terpilih' => $status]);
    }

    // Menampilkan Data Vendor (Aktif / Tidak Aktif)
    public function vendor(Request $request)
    {
        $status = $request->input('status', 'Aktif');
        $viewName = ($status == 'Aktif') ? 'view_vendor_aktif' : 'view_semua_vendor';
        $data = DB::select("SELECT * FROM $viewName");
        return view('Master.vendor', ['data_vendor' => $data, 'status_terpilih' => $status]);
    }

    // Menampilkan Data Margin (Aktif / Tidak Aktif)
    public function margin(Request $request)
    {
        $status = $request->input('status', 'Aktif');
        $viewName = ($status == 'Aktif') ? 'view_margin_aktif' : 'view_semua_margin';
        $data = DB::select("SELECT * FROM $viewName");
        return view('Master.margin', ['data_margin' => $data, 'status_terpilih' => $status]);
    }

    // Menampilkan Data User (Tidak punya status)
    public function user()
    {
        $data = DB::select("SELECT * FROM view_user_role");
        return view('Master.user', ['data_user' => $data]);
    }
}