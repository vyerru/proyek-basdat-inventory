<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB; // Pastikan DB facade di-use

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // --- View Barang ---
        // View Barang (Semua Status)
        DB::statement("
            CREATE OR REPLACE VIEW view_barang_all AS
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
                    WHEN A THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END AS status_barang
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan;
        ");

        // View Barang (Hanya Aktif)
        DB::statement("
            CREATE OR REPLACE VIEW view_barang_active AS
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
                'Aktif' AS status_barang
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE b.status = 1;
        ");

        // --- View Satuan ---
        // View Satuan (Semua Status)
        DB::statement("
            CREATE OR REPLACE VIEW view_satuan_all AS
            SELECT
                idsatuan,
                nama_satuan,
                 CASE status
                    WHEN 1 THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END AS status_satuan
            FROM satuan;
        ");

        // View Satuan (Hanya Aktif)
        DB::statement("
            CREATE OR REPLACE VIEW view_satuan_active AS
            SELECT
                idsatuan,
                nama_satuan,
                'Aktif' AS status_satuan
            FROM satuan
            WHERE status = 1;
        ");


        // --- View Vendor ---
        // View Vendor (Semua Status)
        DB::statement("
            CREATE OR REPLACE VIEW view_vendor_all AS
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

        // View Vendor (Hanya Aktif)
        DB::statement("
            CREATE OR REPLACE VIEW view_vendor_active AS
            SELECT
                idvendor,
                nama_vendor,
                CASE badan_hukum
                    WHEN 'Y' THEN 'Ya'
                    WHEN 'C' THEN 'CV'
                    WHEN 'N' THEN 'Tidak'
                    ELSE 'Lainnya'
                END AS status_badan_hukum,
                'Aktif' AS status_vendor
            FROM vendor
            WHERE status = 'A';
        ");


        // --- View User dan Role ---
        // View User dan Role (Tidak punya status spesifik di tabel master)
        DB::statement("
            CREATE OR REPLACE VIEW view_user_role AS
            SELECT
                u.iduser,
                u.username,
                r.nama_role
            FROM user u
            JOIN role r ON u.idrole = r.idrole;
        ");

        // --- View Margin Penjualan ---
        // View Margin Penjualan (Semua Status)
        DB::statement("
            CREATE OR REPLACE VIEW view_margin_penjualan_all AS
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

         // View Margin Penjualan (Hanya Aktif)
         DB::statement("
            CREATE OR REPLACE VIEW view_margin_penjualan_active AS
            SELECT
                mp.idmargin_penjualan,
                mp.persen,
                mp.created_at AS tanggal_dibuat,
                mp.updated_at AS tanggal_diupdate,
                u.username AS dibuat_oleh,
                'Aktif' AS status_margin
            FROM margin_penjualan mp
            JOIN user u ON mp.iduser = u.iduser
            WHERE mp.status = 1;
        ");

        // --- View Role ---
        // View Role (Tidak punya status spesifik di tabel master)
        DB::statement("
            CREATE OR REPLACE VIEW view_role_all AS
            SELECT
                idrole,
                nama_role
            FROM role;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_barang_all");
        DB::statement("DROP VIEW IF EXISTS view_barang_active");
        DB::statement("DROP VIEW IF EXISTS view_satuan_all");
        DB::statement("DROP VIEW IF EXISTS view_satuan_active");
        DB::statement("DROP VIEW IF EXISTS view_vendor_all");
        DB::statement("DROP VIEW IF EXISTS view_vendor_active");
        DB::statement("DROP VIEW IF EXISTS view_user_role"); // Tetap ada
        DB::statement("DROP VIEW IF EXISTS view_margin_penjualan_all");
        DB::statement("DROP VIEW IF EXISTS view_margin_penjualan_active");
        DB::statement("DROP VIEW IF EXISTS view_role_all"); // Baru ditambahkan
    }
};