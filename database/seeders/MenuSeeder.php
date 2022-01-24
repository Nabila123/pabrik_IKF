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
        DB::table('mst_menu')->delete();

        $menu = [
            ['id' => 1, 'parentId' => 0, 'urutan' => 1, 'nama' => 'Home', 'alias' => 'home', 'directori' => 'home', 'isActive' => 1],
            
            //Admin PO Menu
            ['id' => 2, 'parentId' => 0, 'urutan' => 2, 'nama' => 'Purchase', 'alias' => 'adminPO', 'directori' => 'adminPO', 'isActive' => 1],
            ['id' => 3, 'parentId' => 2, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'adminPO', 'directori' => 'adminPO/index', 'isActive' => 1],
            ['id' => 4, 'parentId' => 2, 'urutan' => 2, 'nama' => 'Purchase Request', 'alias' => 'adminPO.poRequest', 'directori' => 'adminPO/purchaseRequest/poRequest', 'isActive' => 1],
            ['id' => 5, 'parentId' => 2, 'urutan' => 3, 'nama' => 'Purchase Order', 'alias' => 'adminPO.poOrder', 'directori' => 'adminPO/purchaseOrder/poOrder', 'isActive' => 1],
            ['id' => 6, 'parentId' => 2, 'urutan' => 4, 'nama' => 'Laporan Admin PO', 'alias' => 'adminPO.laporanAdminPO', 'directori' => 'adminPO/laporanPurchase/laporanAdminPO', 'isActive' => 1],
            
            //Admin Gudang Bahan Baku

             //Admin Gudang Cuci
             ['id' => 7, 'parentId' => 0, 'urutan' => 4, 'nama' => 'Gudang Cuci', 'alias' => 'GCuci', 'directori' => 'gudangCuci', 'isActive' => 1],
             ['id' => 8, 'parentId' => 7, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GCuci', 'directori' => 'gudangCuci', 'isActive' => 1],
             ['id' => 9, 'parentId' => 7, 'urutan' => 2, 'nama' => 'Gudang Request', 'alias' => 'GCuci.request', 'directori' => 'gudangCuci/request/index', 'isActive' => 1],
             ['id' => 10, 'parentId' => 7, 'urutan' => 3, 'nama' => 'Gudang Kembali', 'alias' => 'GCuci.kembali', 'directori' => 'gudangCuci/kembali/index', 'isActive' => 1],
        ];

        DB::table('mst_menu')->insert($menu);
    }
}
