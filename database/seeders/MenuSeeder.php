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
            ['id' => 10, 'parentId' => 6, 'urutan' => 3, 'nama' => 'Purchase Invoice', 'alias' => 'adminPO.poInvoice', 'directori' => 'adminPO/purchaseInvoice/index', 'isActive' => 1],
            // ['id' => 10, 'parentId' => 6, 'urutan' => 4, 'nama' => 'Laporan Admin PO', 'alias' => 'adminPO.laporanAdminPO', 'directori' => 'adminPO/laporanPurchase/laporanAdminPO', 'isActive' => 1],
            
            //Admin Gudang Bahan Baku
            ['id' => 11, 'parentId' => 0, 'urutan' => 4, 'nama' => 'Gudang Bahan Baku', 'alias' => 'bahan_baku', 'directori' => 'bahan_baku', 'isActive' => 1],
            ['id' => 12, 'parentId' => 11, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'bahan_baku', 'directori' => 'bahan_baku/index', 'isActive' => 1],
            ['id' => 13, 'parentId' => 11, 'urutan' => 2, 'nama' => 'Supply barang', 'alias' => 'bahan_baku.supply.index', 'directori' => 'bahan_baku/supply', 'isActive' => 1],
            ['id' => 14, 'parentId' => 11, 'urutan' => 3, 'nama' => 'PPIC Request', 'alias' => 'bahan_baku.ppicRequest', 'directori' => 'bahan_baku/ppicRequest', 'isActive' => 1],
            ['id' => 15, 'parentId' => 11, 'urutan' => 4, 'nama' => 'Request Keluar Gudang', 'alias' => 'bahan_baku.keluar', 'directori' => 'bahan_baku/keluar', 'isActive' => 1],
            ['id' => 16, 'parentId' => 11, 'urutan' => 5, 'nama' => 'Request Masuk Gudang', 'alias' => 'bahan_baku.masuk', 'directori' => 'bahan_baku/masuk', 'isActive' => 1],


            //Admin Gudang Rajut
            ['id' => 17, 'parentId' => 0, 'urutan' => 5, 'nama' => 'Gudang Rajut', 'alias' => 'GRajut', 'directori' => 'gudangRajut', 'isActive' => 1],
            ['id' => 18, 'parentId' => 17, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GRajut', 'directori' => 'gudangRajut', 'isActive' => 1],
            ['id' => 19, 'parentId' => 17, 'urutan' => 2, 'nama' => 'Gudang Request', 'alias' => 'GRajut.request', 'directori' => 'gudangRajut/request/index', 'isActive' => 1],
            ['id' => 20, 'parentId' => 17, 'urutan' => 3, 'nama' => 'Gudang Kembali', 'alias' => 'GRajut.kembali', 'directori' => 'gudangRajut/kembali/index', 'isActive' => 1],


             //Admin Gudang Cuci
             ['id' => 21, 'parentId' => 0, 'urutan' => 6, 'nama' => 'Gudang Cuci', 'alias' => 'GCuci', 'directori' => 'gudangCuci', 'isActive' => 1],
             ['id' => 22, 'parentId' => 21, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GCuci', 'directori' => 'gudangCuci', 'isActive' => 1],
             ['id' => 23, 'parentId' => 21, 'urutan' => 2, 'nama' => 'Gudang Request', 'alias' => 'GCuci.request', 'directori' => 'gudangCuci/request/index', 'isActive' => 1],
             ['id' => 24, 'parentId' => 21, 'urutan' => 3, 'nama' => 'Gudang Kembali', 'alias' => 'GCuci.kembali', 'directori' => 'gudangCuci/kembali/index', 'isActive' => 1],

             //Admin Gudang Compact
             ['id' => 25, 'parentId' => 0, 'urutan' => 7, 'nama' => 'Gudang Compact', 'alias' => 'GCompact', 'directori' => 'gudangCompact', 'isActive' => 1],
             ['id' => 26, 'parentId' => 25, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GCompact', 'directori' => 'gudangCompact', 'isActive' => 1],
             ['id' => 27, 'parentId' => 25, 'urutan' => 2, 'nama' => 'Gudang Request', 'alias' => 'GCompact.request', 'directori' => 'gudangCompact/request/index', 'isActive' => 1],
             ['id' => 28, 'parentId' => 25, 'urutan' => 3, 'nama' => 'Gudang Kembali', 'alias' => 'GCompact.kembali', 'directori' => 'gudangCompact/kembali/index', 'isActive' => 1],

             //Admin Gudang Inspeksi
            ['id' => 29, 'parentId' => 0, 'urutan' => 8, 'nama' => 'Gudang Inspeksi', 'alias' => 'GInspeksi', 'directori' => 'gudangInspeksi', 'isActive' => 1],
            ['id' => 30, 'parentId' => 29, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GInspeksi', 'directori' => 'gudangInspeksi', 'isActive' => 1],
            ['id' => 31, 'parentId' => 29, 'urutan' => 2, 'nama' => 'Gudang Request', 'alias' => 'GInspeksi.request', 'directori' => 'gudangInspeksi/request/index', 'isActive' => 1],
            ['id' => 32, 'parentId' => 29, 'urutan' => 3, 'nama' => 'Proses Inspeksi', 'alias' => 'GInspeksi.proses', 'directori' => 'gudangInspeksi/proses/index', 'isActive' => 1],
            ['id' => 33, 'parentId' => 29, 'urutan' => 4, 'nama' => 'Gudang Kembali', 'alias' => 'GInspeksi.kembali', 'directori' => 'gudangInspeksi/kembali/index', 'isActive' => 1],
            
            //Gudang Potong
            ['id' => 34, 'parentId' => 0, 'urutan' => 9, 'nama' => 'Gudang Potong', 'alias' => 'GPotong', 'directori' => 'gudangPotong', 'isActive' => 1],
            ['id' => 35, 'parentId' => 34, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GPotong', 'directori' => 'gudangPotong', 'isActive' => 1],
            ['id' => 36, 'parentId' => 34, 'urutan' => 2, 'nama' => 'Request B. Jadi', 'alias' => 'GPotong.request', 'directori' => 'gudangPotong/request/index', 'isActive' => 1],
            ['id' => 37, 'parentId' => 34, 'urutan' => 3, 'nama' => 'Request B. Baku', 'alias' => 'GPotong.keluar', 'directori' => 'gudangPotong/keluar/index', 'isActive' => 1],
            ['id' => 38, 'parentId' => 34, 'urutan' => 4, 'nama' => 'Proses Potong', 'alias' => 'GPotong.proses', 'directori' => 'gudangPotong/proses/index', 'isActive' => 1],
            ['id' => 75, 'parentId' => 34, 'urutan' => 5, 'nama' => 'Request Reject', 'alias' => 'GPotong.reject', 'directori' => 'gudangPotong/reject/index', 'isActive' => 1],

            //Gudang Jahit
            ['id' => 39, 'parentId' => 0, 'urutan' => 10, 'nama' => 'Gudang Jahit', 'alias' => 'GJahit', 'directori' => 'gudangJahit', 'isActive' => 1],
            ['id' => 40, 'parentId' => 39, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GJahit', 'directori' => 'gudangJahit', 'isActive' => 1],
            ['id' => 41, 'parentId' => 39, 'urutan' => 2, 'nama' => 'Request G. Potong', 'alias' => 'GJahit.request', 'directori' => 'gudangJahit/request/index', 'isActive' => 1],
            ['id' => 42, 'parentId' => 39, 'urutan' => 3, 'nama' => 'Operator Jahit', 'alias' => 'GJahit.operator', 'directori' => 'gudangJahit/operator/index', 'isActive' => 1],
            ['id' => 43, 'parentId' => 39, 'urutan' => 4, 'nama' => 'Request Baju Reject', 'alias' => 'GJahit.reject', 'directori' => 'gudangJahit/reject/index', 'isActive' => 1],

            //Gudang Batil
            ['id' => 44, 'parentId' => 0, 'urutan' => 11, 'nama' => 'Gudang Batil', 'alias' => 'GBatil', 'directori' => 'gudangBatil', 'isActive' => 1],
            ['id' => 45, 'parentId' => 44, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GBatil', 'directori' => 'gudangBatil', 'isActive' => 1],
            ['id' => 46, 'parentId' => 44, 'urutan' => 2, 'nama' => 'Request G. Jahit', 'alias' => 'GBatil.request', 'directori' => 'gudangBatil/request/index', 'isActive' => 1],
            ['id' => 47, 'parentId' => 44, 'urutan' => 3, 'nama' => 'Operator Batil', 'alias' => 'GBatil.operator', 'directori' => 'gudangBatil/operator/index', 'isActive' => 1],
            ['id' => 48, 'parentId' => 44, 'urutan' => 4, 'nama' => 'Request Baju Reject', 'alias' => 'GBatil.reject', 'directori' => 'gudangBatil/reject/index', 'isActive' => 1],

            //Gudang Control
            ['id' => 49, 'parentId' => 0, 'urutan' => 12, 'nama' => 'Gudang Control', 'alias' => 'GControl', 'directori' => 'gudangControl', 'isActive' => 1],
            ['id' => 50, 'parentId' => 49, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GControl', 'directori' => 'gudangControl', 'isActive' => 1],
            ['id' => 51, 'parentId' => 49, 'urutan' => 2, 'nama' => 'Request G. Batil', 'alias' => 'GControl.request', 'directori' => 'gudangControl/request/index', 'isActive' => 1],
            ['id' => 52, 'parentId' => 49, 'urutan' => 3, 'nama' => 'Operator Control', 'alias' => 'GControl.operator', 'directori' => 'gudangControl/operator/index', 'isActive' => 1],
            ['id' => 53, 'parentId' => 49, 'urutan' => 4, 'nama' => 'Request Baju Reject', 'alias' => 'GControl.reject', 'directori' => 'gudangControl/reject/index', 'isActive' => 1],

            //Gudang Setrika
            ['id' => 59, 'parentId' => 0, 'urutan' => 13, 'nama' => 'Gudang Setrika', 'alias' => 'GSetrika', 'directori' => 'gudangSetrika', 'isActive' => 1],
            ['id' => 60, 'parentId' => 59, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GSetrika', 'directori' => 'gudangSetrika', 'isActive' => 1],
            ['id' => 61, 'parentId' => 59, 'urutan' => 2, 'nama' => 'Request G. Control', 'alias' => 'GSetrika.request', 'directori' => 'gudangSetrika/request/index', 'isActive' => 1],
            ['id' => 62, 'parentId' => 59, 'urutan' => 3, 'nama' => 'Operator Setrika', 'alias' => 'GSetrika.operator', 'directori' => 'gudangSetrika/operator/index', 'isActive' => 1],
            ['id' => 63, 'parentId' => 59, 'urutan' => 4, 'nama' => 'Request Baju Reject', 'alias' => 'GSetrika.reject', 'directori' => 'gudangSetrika/reject/index', 'isActive' => 1],


            //Gudang Packing
            ['id' => 54, 'parentId' => 0, 'urutan' => 14, 'nama' => 'Packing', 'alias' => 'GPacking', 'directori' => 'gudangPacking', 'isActive' => 1],
            ['id' => 55, 'parentId' => 54, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GPacking', 'directori' => 'gudangPacking', 'isActive' => 1],
            ['id' => 56, 'parentId' => 54, 'urutan' => 2, 'nama' => 'Request G. Setrika', 'alias' => 'GPacking.request', 'directori' => 'gudangPacking/request/index', 'isActive' => 1],
            ['id' => 57, 'parentId' => 54, 'urutan' => 3, 'nama' => 'Operator Packing', 'alias' => 'GPacking.operator', 'directori' => 'gudangPacking/operator/index', 'isActive' => 1],
            ['id' => 58, 'parentId' => 54, 'urutan' => 4, 'nama' => 'Request Baju Reject', 'alias' => 'GPacking.reject', 'directori' => 'gudangPacking/reject/index', 'isActive' => 1],

            //Master Material
            ['id' => 64, 'parentId' => 0, 'urutan' => 17, 'nama' => 'Data Master', 'alias' => 'Material', 'directori' => 'material', 'isActive' => 1],
            ['id' => 65, 'parentId' => 64, 'urutan' => 1, 'nama' => 'Master Material', 'alias' => 'Material', 'directori' => 'material', 'isActive' => 1],
            ['id' => 83, 'parentId' => 64, 'urutan' => 2, 'nama' => 'Master Jenis Baju', 'alias' => 'JenisBaju', 'directori' => 'jenisBaju', 'isActive' => 1],

            //Gudang Gudang Barang Jadi
            ['id' => 66, 'parentId' => 0, 'urutan' => 15, 'nama' => 'Gudang Barang Jadi', 'alias' => 'GBarangJadi', 'directori' => 'gudangBarangJadi', 'isActive' => 1],
            ['id' => 67, 'parentId' => 66, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GBarangJadi', 'directori' => 'gudangBarangJadi', 'isActive' => 1],
            ['id' => 68, 'parentId' => 66, 'urutan' => 3, 'nama' => 'Operator Barang Jadi', 'alias' => 'GBarangJadi.operator', 'directori' => 'gudangBarangJadi/operator/index', 'isActive' => 1],
            ['id' => 69, 'parentId' => 66, 'urutan' => 4, 'nama' => 'Request Potong Baju', 'alias' => 'GBarangJadi.requestPotong', 'directori' => 'gudangBarangJadi/requestPotong/index', 'isActive' => 1],

            //Keuangan
            ['id' => 70, 'parentId' => 0, 'urutan' => 16, 'nama' => 'Keuangan', 'alias' => 'Keuangan', 'directori' => 'keuangan', 'isActive' => 1],
            ['id' => 71, 'parentId' => 70, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'Keuangan', 'directori' => 'keuangan/index', 'isActive' => 1],
            ['id' => 72, 'parentId' => 70, 'urutan' => 2, 'nama' => 'Purchase Order', 'alias' => 'adminPO.poOrder', 'directori' => 'adminPO/purchaseOrder/poOrder', 'isActive' => 1],
            ['id' => 73, 'parentId' => 70, 'urutan' => 3, 'nama' => 'Purchase Invoice', 'alias' => 'adminPO.poInvoice', 'directori' => 'adminPO/purchaseInvoice/index', 'isActive' => 1],
            ['id' => 74, 'parentId' => 70, 'urutan' => 4, 'nama' => 'Penjualan Baju', 'alias' => 'Keuangan.penjualan', 'directori' => 'keuangan/penjualan/index', 'isActive' => 1],

            //Master Pegawai
            ['id' => 76, 'parentId' => 0, 'urutan' => 17, 'nama' => 'Master Pegawai', 'alias' => 'pegawai', 'directori' => 'pegawai', 'isActive' => 1],
            ['id' => 77, 'parentId' => 76, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'pegawai', 'directori' => 'pegawai/index', 'isActive' => 1],

             //Admin Gudang Bahan Pembantu
            ['id' => 78, 'parentId' => 0, 'urutan' => 4, 'nama' => 'Gudang Bahan Bantu', 'alias' => 'GBahanPembantu', 'directori' => 'gudangBahanPembantu', 'isActive' => 1],
            ['id' => 79, 'parentId' => 78, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'GBahanPembantu', 'directori' => 'gudangBahanPembantu/index', 'isActive' => 1],
            ['id' => 80, 'parentId' => 78, 'urutan' => 2, 'nama' => 'Supply barang', 'alias' => 'GBahanPembantu.supply.index', 'directori' => 'gudangBahanPembantu/supply', 'isActive' => 1],
            // ['id' => 81, 'parentId' => 78, 'urutan' => 3, 'nama' => 'PPIC Request', 'alias' => 'GBahanPembantu.ppicRequest', 'directori' => 'gudangBahanPembantu/ppicRequest', 'isActive' => 1],
            ['id' => 82, 'parentId' => 78, 'urutan' => 4, 'nama' => 'Request Keluar Gudang', 'alias' => 'GBahanPembantu.keluar', 'directori' => 'gudangBahanPembantu/keluar', 'isActive' => 1],

            //Master User
            ['id' => 84, 'parentId' => 0, 'urutan' => 18, 'nama' => 'Master User', 'alias' => 'user', 'directori' => 'user', 'isActive' => 1],
            ['id' => 85, 'parentId' => 84, 'urutan' => 1, 'nama' => 'Dashboard', 'alias' => 'user', 'directori' => 'user/index', 'isActive' => 1],

        ];

        DB::table('mst_menu')->insert($menu);
    }
}
