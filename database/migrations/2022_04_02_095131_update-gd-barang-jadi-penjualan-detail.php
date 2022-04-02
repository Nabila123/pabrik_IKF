<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGdBarangJadiPenjualanDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_barangjadipenjualan_detail', function (Blueprint $table) {            
            $table->unsignedBigInteger('barangJadiPenjualanId')->after('id');
            $table->foreign('barangJadiPenjualanId')->references('id')->on('gd_barangjadi_penjualan');
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
