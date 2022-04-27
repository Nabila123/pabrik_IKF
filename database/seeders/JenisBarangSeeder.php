<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class JenisBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rol = [
            ['id' => 1, 'nama' => 'Benang', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'nama' => 'Grey', 'created_at' => date('Y-m-d H:i:s')],

            ['id' => 3, 'nama' => 'Kain', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'nama' => 'Pembantu', 'created_at' => date('Y-m-d H:i:s')],
        ];

        DB::table('mst_jenisBarang')->insert($rol);
    }
}
