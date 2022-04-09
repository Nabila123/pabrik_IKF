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
        DB::table('users')->delete();
        $users = [ 
            ['id' => 1, 'nama' => 'admin', 'nip' => 'ADM001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 1, 'created_at' => date('Y-m-d H:i:s')],
            
            ['id' => 2, 'nama' => 'K DIV PO', 'nip' => 'KDivPO001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 4, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'nama' => 'K DIV Prod', 'nip' => 'KDivProd001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 5, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'nama' => 'K DIV Fin', 'nip' => 'KDivFin001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 6, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 5, 'nama' => 'Manager PO', 'nip' => 'KDeptPO001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 7, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 6, 'nama' => 'Manager Prod', 'nip' => 'KDeptProd001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 8, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 7, 'nama' => 'Manager Fin', 'nip' => 'KDeptFin001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 9, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 8, 'nama' => 'PPIC', 'nip' => 'PPIC001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 38, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 9, 'nama' => 'Gudang Bahan Baku', 'nip' => 'GBB001', 'password' => bcrypt('12345678'), 'passwordAsli' => '12345678', 'roleId' => 26, 'created_at' => date('Y-m-d H:i:s')],
        ];

        DB::table('users')->insert($users);
    }
}
