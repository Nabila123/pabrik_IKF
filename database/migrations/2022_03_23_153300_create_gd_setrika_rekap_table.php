<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdSetrikaRekapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_setrikaRekap', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('tanggal')->nullable();
            $table->unsignedBigInteger('userId');
            
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users');
        });

        Schema::create('gd_setrikaRekap_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdSetrikaRekapId');
            $table->unsignedBigInteger('pegawaiId');
            $table->unsignedBigInteger('gdBajuStokOpnameId');
            $table->unsignedBigInteger('purchaseId');
            $table->string('jenisBaju');
            $table->string('ukuranBaju');
            
            $table->timestamps();

            $table->foreign('gdSetrikaRekapId')->references('id')->on('gd_setrikaRekap');
            $table->foreign('pegawaiId')->references('id')->on('mst_pegawai');
            $table->foreign('gdBajuStokOpnameId')->references('id')->on('gd_baju_stok_opname');
            $table->foreign('purchaseId')->references('id')->on('purchase');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
