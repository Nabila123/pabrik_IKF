<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiameterDanGramasiGdInspeksiStokOpname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_inspeksi_stok_opname', function (Blueprint $table) {            
            $table->integer('gramasi')->after('jenisId')->default(0);
            $table->integer('diameter')->after('gramasi')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
