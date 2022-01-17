<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission')->delete();
        $permission = [ 
            ['id' => 1, 'menuId' => 1, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            
            ['id' => 2, 'menuId' => 2, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
        ];

        DB::table('permission')->insert($permission);
    }
}
