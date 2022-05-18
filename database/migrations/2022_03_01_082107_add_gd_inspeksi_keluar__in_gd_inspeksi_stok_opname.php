<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGdInspeksiKeluarInGdInspeksiStokOpname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_inspeksi_stok_opname', function (Blueprint $table) {
            $table->unsignedBigInteger('gdInspeksiKId')->after('id');
           
            $table->foreign('gdInspeksiKId')->references('id')->on('gd_inspeksiKeluar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gd_inspeksi_stok_opname', function (Blueprint $table) {
            //
        });
    }
}
