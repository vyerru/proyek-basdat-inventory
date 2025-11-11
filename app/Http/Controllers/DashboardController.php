<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Panggil FUNCTION fn_get_omzet_range (Asumsi bulan Oktober 2025)
        $omzet = DB::select("SELECT fn_get_omzet_range('2025-10-01', '2025-10-31') AS total")[0]->total;

        // 2. Panggil FUNCTION fn_get_total_terjual_barang (Asumsi barang ID 1)
        // $terjual = DB::select("SELECT fn_get_total_terjual_barang(1) AS total")[0]->total;

        // // 3. Panggil FUNCTION fn_hitung_stok (Asumsi barang ID 1)
        // $stok = DB::select("SELECT fn_hitung_stok(1) AS sisa_stok")[0]->sisa_stok;

        return view('dashboard', [
            'omzet_bulan_ini' => $omzet,
            // 'barang_terjual' => $terjual,
            // 'stok_barang_1' => $stok
        ]);
    }
}