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
            //Developer
            ['menuId' => 1, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 2, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 6, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 11, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 17, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 21, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 25, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 29, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 34, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 39, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 44, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 49, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 54, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 59, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 64, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 66, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 70, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 76, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 78, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 84, 'roleId' => 1, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
           

            //PPIC
            ['menuId' => 1, 'roleId' => 38, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 2, 'roleId' => 38, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            

            // Purchase
                //Kepala Div
                ['menuId' => 1, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 2, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 6, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 11, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 17, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 21, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 25, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 29, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 34, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 39, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 44, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 49, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 54, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 59, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 66, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 70, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 78, 'roleId' => 4, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                
                //Kepala Manager - Kepala Bagian - Admin
                ['menuId' => 1, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 6, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                
                ['menuId' => 1, 'roleId' => 10, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 6, 'roleId' => 10, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],

                ['menuId' => 1, 'roleId' => 24, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 6, 'roleId' => 24, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],


            // Gudang Bahan Baku
                //Kepala Produksi
                ['menuId' => 1, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 2, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 6, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 11, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 17, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 21, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 25, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 29, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 34, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 39, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 44, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 49, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 54, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 59, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 66, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 70, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 78, 'roleId' => 5, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                
                //Kepala Manager - Admin
                ['menuId' => 1, 'roleId' => 8, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 11, 'roleId' => 8, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                

                ['menuId' => 1, 'roleId' => 26, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 11, 'roleId' => 26, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],


        ];

        DB::table('permission')->insert($permission);
    }
}






















































































