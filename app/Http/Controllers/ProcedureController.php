<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Untuk logging error

class ProcedureController extends Controller
{
    // --- BARANG ---
    public function createBarangForm()
    {
        // Ambil data satuan untuk dropdown, pastikan view satuan_active ada
        $satuans = DB::select('SELECT idsatuan, nama_satuan FROM view_satuan_active');
        return view('procedures.create_barang', ['satuans' => $satuans]);
    }

    public function storeBarang(Request $request)
    {
        // 1. Validasi Input Form
        $validated = $request->validate([
            'jenis' => 'required|string|max:1',
            'nama' => 'required|string|max:45',
            'idsatuan' => 'required|integer|exists:satuan,idsatuan', // Pastikan satuan ada
            'status' => 'required|integer|in:0,1',
            'harga' => 'required|integer|min:0',
        ]);

        try {
            // 2. Panggil Stored Procedure (Gunakan nama SP Anda)
            // Gunakan DB::statement jika SP tidak mengembalikan hasil set
            DB::statement('CALL insert_barang(?, ?, ?, ?, ?)', [
                $validated['jenis'],
                $validated['nama'],
                $validated['idsatuan'],
                $validated['status'],
                $validated['harga'],
            ]);

            // 3. Redirect dengan pesan sukses
            return redirect()->route('home')->with('success', 'Barang berhasil ditambahkan!');

        } catch (\Illuminate\Database\QueryException $e) {
            // 4. Tangani Error Database
            Log::error("Error calling insert_barang SP: " . $e->getMessage());
            return back()->withInput()->withErrors(['db_error' => 'Gagal menyimpan barang ke database. Error: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            // 5. Tangani Error Lain
            Log::error("General error in storeBarang: " . $e->getMessage());
            return back()->withInput()->withErrors(['general_error' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    // --- PENGADAAN ---
    public function createPengadaanForm()
    {
         // Ambil data vendor & user jika perlu untuk dropdown
         $vendors = DB::select('SELECT idvendor, nama_vendor FROM view_vendor_active');
         // $users = DB::select('SELECT iduser, username FROM user'); // Ambil user jika perlu dipilih
         return view('procedures.create_pengadaan', ['vendors' => $vendors]);
    }

    public function storePengadaan(Request $request)
    {
        // 1. Validasi (Sangat Sederhana - PERLU DISEMPURNAKAN)
         $validated = $request->validate([
             'vendor_idvendor' => 'required|integer|exists:vendor,idvendor',
             'subtotal_nilai' => 'required|integer|min:0',
             'ppn' => 'required|integer|min:0',
             // 'status' => 'required|string|max:1', // Mungkin status di-set default?
             // Detail pengadaan (barang, jumlah, harga) perlu divalidasi juga,
             // mungkin menggunakan array validation
         ]);

         // Hitung total menggunakan Function (Gunakan nama FN Anda)
         $totalResult = DB::selectOne('SELECT calculate_total_pengadaan(?, ?) AS total', [
             $validated['subtotal_nilai'],
             $validated['ppn']
         ]);
         $total_nilai = $totalResult->total ?? 0; // Ambil hasil atau default 0

         $userId = auth()->id(); // Ambil ID user yang login
         $status = 'P'; // Contoh status default 'Pending'

         try {
             // 2. Panggil Stored Procedure (Gunakan nama SP Anda)
             // Jika SP punya OUT parameter, gunakan DB::select/DB::selectOne
             // Contoh jika SP mengembalikan ID baru:
             // $result = DB::selectOne('CALL insert_pengadaan(?, ?, ?, ?, ?, ?, @lastId); SELECT @lastId AS pengadaanId', [
             //    $userId, $validated['vendor_idvendor'], $validated['subtotal_nilai'], $validated['ppn'], $total_nilai, $status
             // ]);
             // $newPengadaanId = $result->pengadaanId;
             // Lanjutkan simpan detail pengadaan jika perlu...

             // Contoh jika SP tidak butuh return ID:
              DB::statement('CALL insert_pengadaan(?, ?, ?, ?, ?, ?)', [
                 $userId,
                 $validated['vendor_idvendor'],
                 $validated['subtotal_nilai'],
                 $validated['ppn'],
                 $total_nilai, // Hasil dari function
                 $status
              ]);

             // 3. Redirect
             return redirect()->route('home')->with('success', 'Pengadaan berhasil ditambahkan!');

         } catch (\Illuminate\Database\QueryException $e) {
             Log::error("Error calling insert_pengadaan SP: " . $e->getMessage());
             return back()->withInput()->withErrors(['db_error' => 'Gagal menyimpan pengadaan. Error: ' . $e->getMessage()]);
         } catch (\Exception $e) {
             Log::error("General error in storePengadaan: " . $e->getMessage());
             return back()->withInput()->withErrors(['general_error' => 'Terjadi kesalahan.']);
         }
    }

    // --- PENJUALAN --- (Mirip dengan Pengadaan)
     public function createPenjualanForm()
     {
         // Ambil margin aktif, barang aktif, dll
         $margins = DB::select('SELECT idmargin_penjualan, persen FROM view_margin_penjualan_active');
         $barangs = DB::select('SELECT idbarang, nama_barang, harga FROM view_barang_active');
         return view('procedures.create_penjualan', ['margins' => $margins, 'barangs' => $barangs]);
     }

     public function storePenjualan(Request $request)
     {
         // 1. Validasi (Perlu disempurnakan, terutama detail barang)
         $validated = $request->validate([
             'idmargin_penjualan' => 'required|integer|exists:margin_penjualan,idmargin_penjualan',
             'subtotal_nilai' => 'required|integer|min:0',
             'ppn' => 'required|integer|min:0',
             // Detail barang (id, jumlah, harga satuan?) perlu validasi array
         ]);

          // Hitung total pakai Function (Gunakan nama FN Anda)
          $totalResult = DB::selectOne('SELECT calculate_total_penjualan(?, ?) AS total', [
             $validated['subtotal_nilai'],
             $validated['ppn']
         ]);
         $total_nilai = $totalResult->total ?? 0;

         $userId = auth()->id();

         try {
             // 2. Panggil Stored Procedure (Gunakan nama SP Anda)
             // Sesuaikan pemanggilan jika perlu return ID
             DB::statement('CALL insert_penjualan(?, ?, ?, ?, ?)', [
                  $userId,
                  $validated['idmargin_penjualan'],
                  $validated['subtotal_nilai'],
                  $validated['ppn'],
                  $total_nilai
             ]);
             // Lanjutkan simpan detail penjualan jika perlu...

             // 3. Redirect
             return redirect()->route('home')->with('success', 'Penjualan berhasil ditambahkan!');

         } catch (\Illuminate\Database\QueryException $e) {
             Log::error("Error calling insert_penjualan SP: " . $e->getMessage());
             return back()->withInput()->withErrors(['db_error' => 'Gagal menyimpan penjualan. Error: ' . $e->getMessage()]);
         } catch (\Exception $e) {
              Log::error("General error in storePenjualan: " . $e->getMessage());
             return back()->withInput()->withErrors(['general_error' => 'Terjadi kesalahan.']);
         }
     }

    // --- PENERIMAAN --- (Mirip)
     public function createPenerimaanForm()
     {
         // Ambil data pengadaan yang belum diterima?
         // $pengadaans = DB::select('SELECT idpengadaan, ... FROM pengadaan WHERE status = "P"'); // Contoh
         // return view('procedures.create_penerimaan', ['pengadaans' => $pengadaans]);
         return view('procedures.create_penerimaan'); // Form sederhana dulu
     }

     public function storePenerimaan(Request $request)
     {
          // 1. Validasi
          $validated = $request->validate([
              'idpengadaan' => 'required|integer|exists:pengadaan,idpengadaan',
              // 'status' => 'required|string|max:1', // Mungkin status di-set default?
              // Detail penerimaan perlu divalidasi juga
          ]);

          $userId = auth()->id();
          $status = 'R'; // Contoh Received

          try {
              // 2. Panggil Stored Procedure (Gunakan nama SP Anda)
              DB::statement('CALL insert_penerimaan(?, ?, ?)', [
                    $validated['idpengadaan'],
                    $userId,
                    $status
              ]);
              // Lanjutkan simpan detail penerimaan jika perlu...

              // 3. Redirect
              return redirect()->route('home')->with('success', 'Penerimaan berhasil ditambahkan!');

          } catch (\Illuminate\Database\QueryException $e) {
             Log::error("Error calling insert_penerimaan SP: " . $e->getMessage());
             return back()->withInput()->withErrors(['db_error' => 'Gagal menyimpan penerimaan. Error: ' . $e->getMessage()]);
         } catch (\Exception $e) {
             Log::error("General error in storePenerimaan: " . $e->getMessage());
             return back()->withInput()->withErrors(['general_error' => 'Terjadi kesalahan.']);
         }
     }
}