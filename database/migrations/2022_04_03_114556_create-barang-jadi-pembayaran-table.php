<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangJadiPembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_barangjadi_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('kodeTransaksi');
            $table->string('customer')->nullable();
            $table->date('tanggal');
            $table->string('totalHarga');

            $table->unsignedBigInteger('userId');
            $table->timestamps();

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
