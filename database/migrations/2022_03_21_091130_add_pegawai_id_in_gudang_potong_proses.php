<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

class AddPegawaiIdInGudangPotongProses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_potongproses', function (Blueprint $table) {
            $table->unsignedBigInteger('pegawaiId')->after('gPotongKId')->nullable();

            $table->foreign('pegawaiId')->references('id')->on('mst_pegawai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gudang_potong_proses', function (Blueprint $table) {
            //
        });
    }
}
