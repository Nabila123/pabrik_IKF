<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->delete();
        $users = [
            ['nama' => 'Developer', 'nip' => 'ADM001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Direktur', 'nip' => 'OW001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 3, 'created_at' => date('Y-m-d H:i:s')],
            
            //Purchase
            ['nama' => 'K DIV PO', 'nip' => 'KDivPO001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 4, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Manager PO', 'nip' => 'KDeptPO001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 7, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Kepala Bagian PO', 'nip' => 'KBagPO001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 10, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Admin PO', 'nip' => 'APO001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 24, 'created_at' => date('Y-m-d H:i:s')],
          
            //Produksi
            ['nama' => 'K DIV Prod', 'nip' => 'KDivProd001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 5, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Manager Prod', 'nip' => 'KDeptProd001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 8, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'K. Bag Gudang', 'nip' => 'KBagGud001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 12, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'K. Bag Produksi', 'nip' => 'KBagProd001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 13, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'PPIC', 'nip' => 'PPIC001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 38, 'created_at' => date('Y-m-d H:i:s')],
            
            ['nama' => 'Gudang Rajut', 'nip' => 'GR001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 27, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Gudang Cuci', 'nip' => 'GC001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 28, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Gudang Compact', 'nip' => 'GCO001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 29, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Gudang Inspeksi', 'nip' => 'GI001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 30, 'created_at' => date('Y-m-d H:i:s')],
              
            ['nama' => 'Gudang Potong', 'nip' => 'GP001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 31, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Gudang Jahit', 'nip' => 'GJ001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 32, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Gudang Batil', 'nip' => 'GBO001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 33, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Gudang Control', 'nip' => 'GCT001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 34, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Gudang Setrika', 'nip' => 'GS001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 35, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Gudang Packing', 'nip' => 'GP001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 36, 'created_at' => date('Y-m-d H:i:s')],

            ['nama' => 'Gudang Bahan Baku', 'nip' => 'GBB001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 26, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Gudang Bahan Bantu', 'nip' => 'GBBA001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 39, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Gudang Barang Jadi', 'nip' => 'GBJ001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 37, 'created_at' => date('Y-m-d H:i:s')],
            
            //Finance
            ['nama' => 'K DIV Fin', 'nip' => 'KDivFin001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 6, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Manager Fin', 'nip' => 'KDeptFin001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 9, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'K. Bag Fin', 'nip' => 'KBagFin001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 11, 'created_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Admin Fin', 'nip' => 'AFin001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 25, 'created_at' => date('Y-m-d H:i:s')],
          
        ];

        DB::table('users')->insert($users);
    }
}
