<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW view_atk AS
            SELECT
                b.idbarang,
                b.nama AS nama_barang,
                s.nama_satuan,
                b.harga,
                CASE b.jenis
                    WHEN 'J' THEN 'Barang Jadi'
                    WHEN 'B' THEN 'Bahan Baku'
                    ELSE 'Lainnya'
                END AS jenis_barang,
                CASE b.status
                    WHEN 1 THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END AS status_barang
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan;
        ");


        DB::statement("
            CREATE VIEW view_satuan AS
            SELECT
                idsatuan,
                nama_satuan,
                 CASE status
                    WHEN 1 THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END AS status_satuan
            FROM satuan;
        ");

        // View untuk Vendor
        DB::statement("
            CREATE VIEW view_vendor AS
            SELECT
                idvendor,
                nama_vendor,
                CASE badan_hukum
                    WHEN 'Y' THEN 'Ya'
                    WHEN 'C' THEN 'CV'
                    WHEN 'N' THEN 'Tidak'
                    ELSE 'Lainnya'
                END AS status_badan_hukum,
                CASE status
                    WHEN 'A' THEN 'Aktif'
                    WHEN 'N' THEN 'Tidak Aktif'
                    ELSE 'Lainnya'
                END AS status_vendor
            FROM vendor;
        ");

         // View untuk User dan Role
        DB::statement("
            CREATE VIEW view_user_role AS
            SELECT
                u.iduser,
                u.username,
                r.nama_role
            FROM user u
            JOIN role r ON u.idrole = r.idrole;
        ");

         // View untuk Margin Penjualan
        DB::statement("
            CREATE VIEW view_margin_penjualan AS
            SELECT
                mp.idmargin_penjualan,
                mp.persen,
                mp.created_at AS tanggal_dibuat,
                mp.updated_at AS tanggal_diupdate,
                u.username AS dibuat_oleh,
                CASE mp.status
                    WHEN 1 THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END AS status_margin
            FROM margin_penjualan mp
            JOIN user u ON mp.iduser = u.iduser;
        ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_atk");
        DB::statement("DROP VIEW IF EXISTS view_satuan");
        DB::statement("DROP VIEW IF EXISTS view_vendor");
        DB::statement("DROP VIEW IF EXISTS view_user_role");
        DB::statement("DROP VIEW IF EXISTS view_margin_penjualan");
    }
};
