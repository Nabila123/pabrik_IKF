<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdBarangJadiPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_barangjadi_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('kodeTransaksi');
            $table->string('customer')->nullable();
            $table->date('tanggal');
            $table->string('totalHarga');

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users');
        });

        Schema::create('gd_barangjadipenjualan_detail', function (Blueprint $table) {
            $table->id();
            $table->string('kodeProduct');
            $table->unsignedBigInteger('gdBajuStokOpnameId');
            $table->unsignedBigInteger('purchaseId');
            $table->string('jenisBaju')->nullable();
            $table->string('ukuranBaju')->nullable();
            $table->string('harga');

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdBajuStokOpnameId')->references('id')->on('gd_baju_stok_opname');
            $table->foreign('purchaseId')->references('id')->on('purchase');
            $table->foreign('userId')->references('id')->on('users');
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
