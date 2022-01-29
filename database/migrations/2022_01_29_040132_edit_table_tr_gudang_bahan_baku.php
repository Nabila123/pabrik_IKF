<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditTableTrGudangBahanBaku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_gudang_bahan_baku_detail', function (Blueprint $table) {
            $table->dropColumn('qty');
            $table->integer('qty_permintaan');
            $table->integer('qty_saat_ini')->nullable();
            $table->integer('gramasi')->nullable();
            $table->integer('diameter')->nullable();
        });

        Schema::table('tr_gudang_bahan_baku', function (Blueprint $table) {
            $table->dropColumn('gramasi');
            $table->dropColumn('diameter');
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
