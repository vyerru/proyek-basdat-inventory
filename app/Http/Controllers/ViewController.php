<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Gunakan DB facade

class ViewController extends Controller
{
    // Method untuk menampilkan view_barang_all
    public function viewBarangAll()
    {
        $data = DB::select('SELECT * FROM view_barang_all');
        return view('views.barang_all', ['data' => $data, 'title' => 'Semua Barang']);
    }

    // Method untuk menampilkan view_barang_active
    public function viewBarangActive()
    {
        $data = DB::select('SELECT * FROM view_barang_active');
        return view('views.barang_active', ['data' => $data, 'title' => 'Barang Aktif']);
    }

    // Method untuk menampilkan view_satuan_all
    public function viewSatuanAll()
    {
        $data = DB::select('SELECT * FROM view_satuan_all');
        return view('views.satuan_all', ['data' => $data, 'title' => 'Semua Satuan']);
    }

    // Method untuk menampilkan view_satuan_active
    public function viewSatuanActive()
    {
        $data = DB::select('SELECT * FROM view_satuan_active');
        return view('views.satuan_active', ['data' => $data, 'title' => 'Satuan Aktif']);
    }

    // Method untuk menampilkan view_vendor_all
    public function viewVendorAll()
    {
        $data = DB::select('SELECT * FROM view_vendor_all');
        return view('views.vendor_all', ['data' => $data, 'title' => 'Semua Vendor']);
    }

    // Method untuk menampilkan view_vendor_active
    public function viewVendorActive()
    {
        $data = DB::select('SELECT * FROM view_vendor_active');
        return view('views.vendor_active', data: ['data' => $data, 'title' => 'Vendor Aktif']);
    }

    // Method untuk menampilkan view_user_role
    public function viewUserRole()
    {
        $data = DB::select('SELECT * FROM view_user_role');
        return view('views.user_role', ['data' => $data, 'title' => 'User dan Role']);
    }

    // Method untuk menampilkan view_margin_penjualan_all
    public function viewMarginPenjualanAll()
    {
        $data = DB::select('SELECT * FROM view_margin_penjualan_all');
        return view('views.margin_penjualan_all', ['data' => $data, 'title' => 'Semua Margin Penjualan']);
    }

     // Method untuk menampilkan view_margin_penjualan_active
     public function viewMarginPenjualanActive()
     {
         $data = DB::select('SELECT * FROM view_margin_penjualan_active');
         return view('views.margin_penjualan_active', ['data' => $data, 'title' => 'Margin Penjualan Aktif']);
     }

     // Method untuk menampilkan view_role_all
     public function viewRoleAll()
     {
         $data = DB::select('SELECT * FROM view_role_all');
         return view('views.role_all', ['data' => $data, 'title' => 'Semua Role']);
     }
}