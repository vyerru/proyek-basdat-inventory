<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // Menampilkan Pengadaan (Pakai SP Filter)
    public function pengadaan(Request $request)
    {
        $status = $request->input('status'); // Default NULL (Semua)
        
        // Memanggil STORED PROCEDURE filter
        $data = DB::select("CALL sp_filter_pengadaan_by_status(?)", [$status]);
        
        return view('Transaksi.pengadaan', ['data_pengadaan' => $data, 'status_terpilih' => $status]);
    }

    // Menampilkan Penerimaan (Pakai SP Filter)
    public function penerimaan(Request $request)
    {
        $status = $request->input('status'); // Default NULL (Semua)
        
        // Memanggil STORED PROCEDURE filter
        $data = DB::select("CALL sp_filter_penerimaan_by_status(?)", [$status]);
        
        return view('Transaksi.penerimaan', ['data_penerimaan' => $data, 'status_terpilih' => $status]);
    }
}