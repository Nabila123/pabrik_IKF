<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGdBajuStokOpnameIdInGdBatilMasukDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('gd_jahitkeluar_detail');
        Schema::drop('gd_jahitkeluar');
        Schema::table('gd_batilMasuk_Detail', function (Blueprint $table) {
            $table->unsignedBigInteger('gdBajuStokOpnameId')->after('gdBatilMId');

            $table->foreign('gdBajuStokOpnameId')->references('id')->on('gd_baju_stok_opname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gd_batil_masuk_detail', function (Blueprint $table) {
            //
        });
    }
}
