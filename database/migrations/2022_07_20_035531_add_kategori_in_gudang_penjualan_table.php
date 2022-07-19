<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKategoriInGudangPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_barangjadi_stokopname', function (Blueprint $table) {            
            $table->dropColumn('kategori');
        });
        
        Schema::table('gd_barangjadi_penjualan', function (Blueprint $table) {
            $table->string('kategori')->after('kodeTransaksi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gudang_penjualan', function (Blueprint $table) {
            //
        });
    }
}
