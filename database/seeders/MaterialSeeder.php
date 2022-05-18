<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $material = [
            ['id' => 1, 'jenisId' => 1, 'nama' => 'Benang Rajut', 'satuan' => 'Bal', 'jenis' => null, 'keterangan' => 'Bahan Baku', 'userId' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'jenisId' => 2, 'nama' => 'Grey / Kain Grey', 'satuan' => 'Kg', 'jenis' => null, 'keterangan' => 'Bahan Baku', 'userId' => 1, 'created_at' => date('Y-m-d H:i:s')],

            ['id' => 3, 'jenisId' => 3, 'nama' => 'Kain Putih', 'satuan' => 'Kg', 'jenis' => null, 'keterangan' => 'Bahan Baku', 'userId' => 1, 'created_at' => date('Y-m-d H:i:s')],
        ];

        DB::table('mst_material')->insert($material);
    }
}
