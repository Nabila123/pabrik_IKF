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
            
            //PPIC
            ['id' => 2, 'parentId' => 0, 'urutan' => 2, 'nama' => 'PPIC', 'alias' => 'ppic', 'directori' => 'ppic', 'isActive' => 1],
            ['id' => 3, 'parentId' => 2, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'ppic', 'directori' => 'ppic/index', 'isActive' => 1],
            ['id' => 4, 'parentId' => 2, 'urutan' => 2, 'nama' => 'Purchase Request', 'alias' => 'adminPO.poRequest', 'directori' => 'adminPO/purchaseRequest/poRequest', 'isActive' => 1],
            ['id' => 5, 'parentId' => 2, 'urutan' => 2, 'nama' => 'Gudang Request', 'alias' => 'ppic.gdRequest', 'directori' => 'ppic/gudangRequest/poRequest', 'isActive' => 1],
            
            //Admin PO Menu
            ['id' => 6, 'parentId' => 0, 'urutan' => 3, 'nama' => 'Purchase', 'alias' => 'adminPO', 'directori' => 'adminPO', 'isActive' => 1],
            ['id' => 7, 'parentId' => 6, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'adminPO', 'directori' => 'adminPO/index', 'isActive' => 1],
            ['id' => 8, 'parentId' => 6, 'urutan' => 2, 'nama' => 'Purchase Request', 'alias' => 'adminPO.poRequest', 'directori' => 'adminPO/purchaseRequest/poRequest', 'isActive' => 1],
            ['id' => 9, 'parentId' => 6, 'urutan' => 3, 'nama' => 'Purchase Order', 'alias' => 'adminPO.poOrder', 'directori' => 'adminPO/purchaseOrder/poOrder', 'isActive' => 1],
            ['id' => 10, 'parentId' => 6, 'urutan' => 4, 'nama' => 'Laporan Admin PO', 'alias' => 'adminPO.laporanAdminPO', 'directori' => 'adminPO/laporanPurchase/laporanAdminPO', 'isActive' => 1],
            
            //Admin Gudang Bahan Baku
            ['id' => 11, 'parentId' => 0, 'urutan' => 4, 'nama' => 'Gudang Bahan Baku', 'alias' => 'bahan_baku', 'directori' => 'bahan_baku', 'isActive' => 1],
            ['id' => 12, 'parentId' => 11, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'bahan_baku', 'directori' => 'bahan_baku/index', 'isActive' => 1],
            ['id' => 13, 'parentId' => 11, 'urutan' => 2, 'nama' => 'Supply barang', 'alias' => 'bahan_baku.supply.index', 'directori' => 'bahan_baku/supply', 'isActive' => 1],
            ['id' => 14, 'parentId' => 11, 'urutan' => 3, 'nama' => 'Request Keluar Gudang', 'alias' => 'bahan_baku.keluar', 'directori' => 'bahan_baku/keluar', 'isActive' => 1],
            ['id' => 15, 'parentId' => 11, 'urutan' => 4, 'nama' => 'Request Masuk Gudang', 'alias' => 'bahan_baku.masuk', 'directori' => 'bahan_baku/masuk', 'isActive' => 1],


            //Admin Gudang Rajut
            ['id' => 16, 'parentId' => 0, 'urutan' => 5, 'nama' => 'Gudang Rajut', 'alias' => 'GRajut', 'directori' => 'gudangRajut', 'isActive' => 1],
            ['id' => 17, 'parentId' => 16, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GRajut', 'directori' => 'gudangRajut', 'isActive' => 1],
            ['id' => 18, 'parentId' => 16, 'urutan' => 2, 'nama' => 'Gudang Request', 'alias' => 'GRajut.request', 'directori' => 'gudangRajut/request/index', 'isActive' => 1],
            ['id' => 19, 'parentId' => 16, 'urutan' => 3, 'nama' => 'Gudang Kembali', 'alias' => 'GRajut.kembali', 'directori' => 'gudangRajut/kembali/index', 'isActive' => 1],


             //Admin Gudang Cuci
             ['id' => 20, 'parentId' => 0, 'urutan' => 6, 'nama' => 'Gudang Cuci', 'alias' => 'GCuci', 'directori' => 'gudangCuci', 'isActive' => 1],
             ['id' => 21, 'parentId' => 20, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GCuci', 'directori' => 'gudangCuci', 'isActive' => 1],
             ['id' => 22, 'parentId' => 20, 'urutan' => 2, 'nama' => 'Gudang Request', 'alias' => 'GCuci.request', 'directori' => 'gudangCuci/request/index', 'isActive' => 1],
             ['id' => 23, 'parentId' => 20, 'urutan' => 3, 'nama' => 'Gudang Kembali', 'alias' => 'GCuci.kembali', 'directori' => 'gudangCuci/kembali/index', 'isActive' => 1],

             //Admin Gudang Compact
             ['id' => 24, 'parentId' => 0, 'urutan' => 7, 'nama' => 'Gudang Compact', 'alias' => 'GCompact', 'directori' => 'gudangCompact', 'isActive' => 1],
             ['id' => 25, 'parentId' => 24, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GCompact', 'directori' => 'gudangCompact', 'isActive' => 1],
             ['id' => 26, 'parentId' => 24, 'urutan' => 2, 'nama' => 'Gudang Request', 'alias' => 'GCompact.request', 'directori' => 'gudangCompact/request/index', 'isActive' => 1],
             ['id' => 27, 'parentId' => 24, 'urutan' => 3, 'nama' => 'Gudang Kembali', 'alias' => 'GCompact.kembali', 'directori' => 'gudangCompact/kembali/index', 'isActive' => 1],

             //Admin Gudang Inspeksi
            ['id' => 28, 'parentId' => 0, 'urutan' => 8, 'nama' => 'Gudang Inspeksi', 'alias' => 'GInspeksi', 'directori' => 'gudangInspeksi', 'isActive' => 1],
            ['id' => 29, 'parentId' => 28, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GInspeksi', 'directori' => 'gudangInspeksi', 'isActive' => 1],
            ['id' => 30, 'parentId' => 28, 'urutan' => 2, 'nama' => 'Gudang Request', 'alias' => 'GInspeksi.request', 'directori' => 'gudangInspeksi/request/index', 'isActive' => 1],
            ['id' => 31, 'parentId' => 28, 'urutan' => 3, 'nama' => 'Proses Inspeksi', 'alias' => 'GInspeksi.proses', 'directori' => 'gudangInspeksi/proses/index', 'isActive' => 1],
            ['id' => 32, 'parentId' => 28, 'urutan' => 4, 'nama' => 'Gudang Kembali', 'alias' => 'GInspeksi.kembali', 'directori' => 'gudangInspeksi/kembali/index', 'isActive' => 1],
        ];

        DB::table('mst_menu')->insert($menu);
    }
}
