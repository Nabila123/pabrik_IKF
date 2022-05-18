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
            ['id' => 1, 'nama' => 'Developer', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'nama' => 'Direktur', 'created_at' => date('Y-m-d H:i:s')],

            ['id' => 4, 'nama' => 'Kepala Divisi Purchase Order', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 7, 'nama' => 'Kepala Departemen Purchase Order', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 10, 'nama' => 'Kepala Bagian Purchase Order', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 24, 'nama' => 'Admin Purchase Order', 'created_at' => date('Y-m-d H:i:s')],
            
            ['id' => 5, 'nama' => 'Kepala Divisi Produksi', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 8, 'nama' => 'Kepala Departemen Produksi', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 12, 'nama' => 'Kepala Bagian Gudang', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 13, 'nama' => 'Kepala Bagian Produksi', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 23, 'nama' => 'Kepala Bagian Gudang Barang Jadi', 'created_at' => date('Y-m-d H:i:s')],
            
            ['id' => 6, 'nama' => 'Kepala Divisi Finance', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 9, 'nama' => 'Kepala Departemen Finance', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 11, 'nama' => 'Kepala Bagian Finance', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 25, 'nama' => 'Admin Finance', 'created_at' => date('Y-m-d H:i:s')],


            ['id' => 27, 'nama' => 'Admin Rajut', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 28, 'nama' => 'Admin Cuci', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 29, 'nama' => 'Admin Compact', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 30, 'nama' => 'Admin Inspeksi', 'created_at' => date('Y-m-d H:i:s')],

            ['id' => 31, 'nama' => 'Admin Potong', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 32, 'nama' => 'Admin Jahit', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 33, 'nama' => 'Admin Gudang Batil', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 34, 'nama' => 'Admin Kontrol', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 35, 'nama' => 'Admin Setrika', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 36, 'nama' => 'Admin Packing', 'created_at' => date('Y-m-d H:i:s')],
            
            ['id' => 26, 'nama' => 'Admin Gudang Bahan Baku', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 37, 'nama' => 'Admin Gudang Barang Jadi', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 39, 'nama' => 'Admin Gudang Bahan Bantu', 'created_at' => date('Y-m-d H:i:s')],

            ['id' => 38, 'nama' => 'Production Planning and Inventory Control', 'created_at' => date('Y-m-d H:i:s')],
        ];

        DB::table('mst_role')->insert($rol);
    }
}
