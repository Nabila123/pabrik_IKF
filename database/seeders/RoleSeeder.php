<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mst_role')->delete();

        $rol = [
            ['id' => 1, 'nama' => 'Admin'],
            ['id' => 2, 'nama' => 'Developer'],

            ['id' => 3, 'nama' => 'Direktur'],
            ['id' => 4, 'nama' => 'Kepala Divisi Purchase Order'],
            ['id' => 5, 'nama' => 'Kepala Divisi Produksi'],
            ['id' => 6, 'nama' => 'Kepala Divisi Finance'],

            ['id' => 7, 'nama' => 'Kepala Departemen Purchase Order'],
            ['id' => 8, 'nama' => 'Kepala Departemen Produksi'],
            ['id' => 9, 'nama' => 'Kepala Departemen Finance'],

            ['id' => 10, 'nama' => 'Kepala Bagian Purchase Order'],
            ['id' => 11, 'nama' => 'Kepala Bagian Finance'],

            ['id' => 12, 'nama' => 'Kepala Bagian Gudang Bahan Baku'],
            ['id' => 13, 'nama' => 'Kepala Bagian Rajut'],
            ['id' => 14, 'nama' => 'Kepala Bagian Cuci'],
            ['id' => 15, 'nama' => 'Kepala Bagian Compact'],
            ['id' => 16, 'nama' => 'Kepala Bagian Inspeksi'],
            ['id' => 17, 'nama' => 'Kepala Bagian Potong'],
            ['id' => 18, 'nama' => 'Kepala Bagian Jahit'],
            ['id' => 19, 'nama' => 'Kepala Bagian Gudang Batil'],
            ['id' => 20, 'nama' => 'Kepala Bagian Kontrol'],
            ['id' => 21, 'nama' => 'Kepala Bagian Setrika'],
            ['id' => 22, 'nama' => 'Kepala Bagian Packing'],
            ['id' => 23, 'nama' => 'Kepala Bagian Gudang Barang Jadi'],

            ['id' => 24, 'nama' => 'Admin Purchase Order'],
            ['id' => 25, 'nama' => 'Admin Finance'],

            ['id' => 26, 'nama' => 'Admin Gudang Bahan Baku'],
            ['id' => 27, 'nama' => 'Admin Rajut'],
            ['id' => 28, 'nama' => 'Admin Cuci'],
            ['id' => 29, 'nama' => 'Admin Compact'],
            ['id' => 30, 'nama' => 'Admin Inspeksi'],
            ['id' => 31, 'nama' => 'Admin Potong'],
            ['id' => 32, 'nama' => 'Admin Jahit'],
            ['id' => 33, 'nama' => 'Admin Gudang Batil'],
            ['id' => 34, 'nama' => 'Admin Kontrol'],
            ['id' => 35, 'nama' => 'Admin Setrika'],
            ['id' => 36, 'nama' => 'Admin Packing'],
            ['id' => 37, 'nama' => 'Admin Gudang Barang Jadi'],
        ];

        DB::table('mst_role')->insert($rol);
    }
}
