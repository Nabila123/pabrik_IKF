<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBarangDatangInBahanBaku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_bahanbaku', function (Blueprint $table) {
            $table->unsignedBigInteger('datangId')->after('id')->nullable();

            $table->foreign('datangId')->references('id')->on('barang_datang');

        });

        Schema::table('gd_bahanbaku_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('datangDetailId')->after('id')->nullable();

            $table->foreign('datangDetailId')->references('id')->on('barang_datang_detail');

        });

        Schema::table('gd_bahanbaku_detail_material', function (Blueprint $table) {
            $table->unsignedBigInteger('detailMaterialId')->after('id')->nullable();

            $table->foreign('detailMaterialId')->references('id')->on('barang_datang_detail_material');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bahan_baku', function (Blueprint $table) {
            //
        });
    }
}
