<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKategoriInGudangBarangJadiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_barangjadi_stokopname', function (Blueprint $table) {            
            $table->string('kategori')->after('gdBajuStokOpnameId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gudang_barang_jadi', function (Blueprint $table) {
            //
        });
    }
}
