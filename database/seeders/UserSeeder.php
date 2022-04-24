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
            // ['id' => 1, 'nama' => 'Developer', 'nip' => 'ADM001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 1, 'created_at' => date('Y-m-d H:i:s')],
            
            //Purchase
            // ['id' => 2, 'nama' => 'K DIV PO', 'nip' => 'KDivPO001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 4, 'created_at' => date('Y-m-d H:i:s')],
            // ['id' => 5, 'nama' => 'Manager PO', 'nip' => 'KDeptPO001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 7, 'created_at' => date('Y-m-d H:i:s')],
            
            //Finance
            // ['id' => 4, 'nama' => 'K DIV Fin', 'nip' => 'KDivFin001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 6, 'created_at' => date('Y-m-d H:i:s')],
            // ['id' => 7, 'nama' => 'Manager Fin', 'nip' => 'KDeptFin001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 9, 'created_at' => date('Y-m-d H:i:s')],
            
            //Produksi
            // ['id' => 3, 'nama' => 'K DIV Prod', 'nip' => 'KDivProd001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 5, 'created_at' => date('Y-m-d H:i:s')],
            // ['id' => 6, 'nama' => 'Manager Prod', 'nip' => 'KDeptProd001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 8, 'created_at' => date('Y-m-d H:i:s')],
            // ['id' => 8, 'nama' => 'PPIC', 'nip' => 'PPIC001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 38, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 12, 'nama' => 'Gudang Rajut', 'nip' => 'GR001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 27, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 13, 'nama' => 'Gudang Cuci', 'nip' => 'GC001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 28, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 14, 'nama' => 'Gudang Compact', 'nip' => 'GCO001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 29, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 15, 'nama' => 'Gudang Inspeksi', 'nip' => 'GI001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 30, 'created_at' => date('Y-m-d H:i:s')],
              
            ['id' => 16, 'nama' => 'Gudang Potong', 'nip' => 'GP001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 31, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 17, 'nama' => 'Gudang Jahit', 'nip' => 'GJ001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 32, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 18, 'nama' => 'Gudang Batil', 'nip' => 'GBO001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 33, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 19, 'nama' => 'Gudang Control', 'nip' => 'GCT001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 34, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 20, 'nama' => 'Gudang Setrika', 'nip' => 'GS001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 35, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 21, 'nama' => 'Gudang Packing', 'nip' => 'GP001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 36, 'created_at' => date('Y-m-d H:i:s')],

            //Gudang
            // ['id' => 9, 'nama' => 'Gudang Bahan Baku', 'nip' => 'GBB001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 26, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 10, 'nama' => 'Gudang Bahan Bantu', 'nip' => 'GBBA001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 39, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 11, 'nama' => 'Gudang Barang Jadi', 'nip' => 'GBJ001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 37, 'created_at' => date('Y-m-d H:i:s')],
        ];

        DB::table('users')->insert($users);
    }
}
