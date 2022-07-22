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
           
            //Owner
            ['menuId' => 1, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 2, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 6, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 11, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 17, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 21, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 25, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 29, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 34, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 39, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 44, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 49, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 54, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 59, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 64, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 66, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 70, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 76, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 78, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['menuId' => 84, 'roleId' => 2, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')], 

            // Purchase
                //Kepala Div
                ['menuId' => 1, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 2, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 6, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 11, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 17, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 21, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 25, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 29, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 34, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 39, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 44, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 49, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 54, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 59, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 66, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 70, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 78, 'roleId' => 3, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                
                //Kepala Manager 
                ['menuId' => 1, 'roleId' => 6, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 6, 'roleId' => 6, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                

            // Produksi
                //Kepala Div Produksi
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
                
                //Kepala Manager                
                ['menuId' => 1, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 6, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 11, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 66, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 78, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 17, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 21, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 25, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 29, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 34, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 39, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 44, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 49, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 59, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 54, 'roleId' => 7, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                
                
                //PPIC
                ['menuId' => 1, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 2, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 11, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 78, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 66, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 17, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 21, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 25, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 29, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 34, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 39, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 44, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 49, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 59, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                ['menuId' => 54, 'roleId' => 9, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],

                // Admin Bagian Gudang                
                    // Admin Gudang Bahan Baku
                    ['menuId' => 1, 'roleId' => 10, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                    ['menuId' => 11, 'roleId' => 10, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                
                    // Admin Gudang Bahan Bantu
                    ['menuId' => 1, 'roleId' => 12, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                    ['menuId' => 78, 'roleId' => 12, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                    
                    // Admin Gudang Bahan Jadi
                    ['menuId' => 1, 'roleId' => 11, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                    ['menuId' => 66, 'roleId' => 11, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],

                    // Gudang Rajut            
                    ['menuId' => 1, 'roleId' => 13, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                    ['menuId' => 17, 'roleId' => 13, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],

                    //Admin Gudang Cuci                                     
                    ['menuId' => 1, 'roleId' => 14, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                    ['menuId' => 21, 'roleId' => 14, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],

                    //Admin Gudang Compact                                     
                    ['menuId' => 1, 'roleId' => 15, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                    ['menuId' => 25, 'roleId' => 15, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],

                    //Admin Gudang Inspeksi                                     
                    ['menuId' => 1, 'roleId' => 16, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                    ['menuId' => 29, 'roleId' => 16, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],

                    //Admin Gudang Potong                                    
                    ['menuId' => 1, 'roleId' => 17, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                    ['menuId' => 34, 'roleId' => 17, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],

                    //Admin Gudang Jahit                                    
                    ['menuId' => 1, 'roleId' => 18, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                    ['menuId' => 39, 'roleId' => 18, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],

                    //Admin Gudang Batil                                    
                    ['menuId' => 1, 'roleId' => 19, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                    ['menuId' => 44, 'roleId' => 19, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],

                    //Admin Gudang Control                                    
                    ['menuId' => 1, 'roleId' => 20, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                    ['menuId' => 49, 'roleId' => 20, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],

                    //Admin Gudang Setrika                                    
                    ['menuId' => 1, 'roleId' => 21, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                    ['menuId' => 59, 'roleId' => 21, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],

                    //Admin Gudang Packing                                    
                    ['menuId' => 1, 'roleId' => 22, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],
                    ['menuId' => 54, 'roleId' => 22, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],


            // Gudang Finance
                //Kepala Divisi Finance
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
                ['menuId' => 70, 'roleId' => 8, 'isCreate' => 1, 'isRead' => 1, 'isUpdate' => 1, 'isDelete' => 1, 'created_at' => date('Y-m-d H:i:s')],   

        ];

        DB::table('permission')->insert($permission);
    }
}
