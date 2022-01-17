<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = [
            ['id' => 1, 'id_parent' => 0, 'urutan' => 1, 'nama' => 'Home', 'alias' => 'home', 'direktori' => 'home', 'isActive' => 1],
            
            //Admin PO Menu
            ['id' => 2, 'id_parent' => 0, 'urutan' => 1, 'nama' => 'Admin PO', 'alias' => 'adminPO', 'direktori' => 'adminPO', 'isActive' => 1],
            ['id' => 3, 'id_parent' => 2, 'urutan' => 2, 'nama' => 'Dashboard', 'alias' => 'adminPO', 'direktori' => 'adminPO/index', 'isActive' => 1],
            ['id' => 4, 'id_parent' => 2, 'urutan' => 3, 'nama' => 'Purchase Request', 'alias' => 'adminPO.poRequest', 'direktori' => 'adminPO/poRequest', 'isActive' => 1],
            ['id' => 5, 'id_parent' => 2, 'urutan' => 4, 'nama' => 'Purchase Order', 'alias' => 'adminPO.poOrder', 'direktori' => 'adminPO/poOrder', 'isActive' => 1],
            ['id' => 6, 'id_parent' => 2, 'urutan' => 5, 'nama' => 'Laporan Admin PO', 'alias' => 'adminPO.laporanAdminPO', 'direktori' => 'adminPO/laporanAdminPO', 'isActive' => 1],

            //Admin Gudang Bahan Baku
        ];

        DB::table('mst_menu')->insert($menu);
    }
}
