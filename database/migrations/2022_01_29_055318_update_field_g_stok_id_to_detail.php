<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldGStokIdToDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::table('tr_gudang_keluar_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('gudangStokId')->after('gudangKeluarId');

            $table->foreign('gudangStokId')->references('id')->on('tr_gudang_stok_opname');
            $table->index(['gudangStokId']);

        });

        Schema::table('tr_gudang_masuk_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('gudangStokId')->after('gudangMasukId');

            $table->foreign('gudangStokId')->references('id')->on('tr_gudang_stok_opname');
            $table->index(['gudangStokId']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tr_gudang_keluar', function (Blueprint $table) {
            $table->unsignedBigInteger('gStokId');
  
            $table->foreign('gStokId')->references('id')->on('tr_gudang_stok_opname');
        });

        Schema::table('tr_gudang_masuk', function (Blueprint $table) {
            $table->unsignedBigInteger('gStokId');
  
            $table->foreign('gudangStokId')->references('id')->on('tr_gudang_stok_opname');
        });
    }
}
