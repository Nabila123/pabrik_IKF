<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GdPotongProsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_potongProses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gPotongKId');
            $table->unsignedBigInteger('purchaseId');
            $table->unsignedBigInteger('materialId');
            $table->unsignedBigInteger('jenisId');            
            $table->integer('qty');
            $table->date('tanggal')->nullable();
            $table->boolean('statusDiterima')->default(0);
            
            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gPotongKId')->references('id')->on('gd_potongKeluar_detail');
            $table->foreign('purchaseId')->references('id')->on('purchase');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->foreign('jenisId')->references('id')->on('mst_jenisBarang');
            $table->foreign('userId')->references('id')->on('users');
        });

        Schema::create('gd_potongProses_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdPotongProsesId');
            $table->integer('jumlahRoll');
            $table->float('beratPotong');
            $table->integer('diameter');
            $table->float('beratRoll');
            $table->string('jenisBaju')->nullable();
            $table->string('ukuranBaju')->nullable();
            $table->integer('hasilDz')->nullable();
            $table->integer('hasilKg')->nullable();
            $table->integer('skb')->nullable();
            $table->integer('bs')->nullable();
            $table->integer('aKecil')->nullable();
            $table->integer('aKetek')->nullable();
            $table->integer('aKetekPotong')->nullable();
            $table->integer('aSumbu')->nullable();
            $table->integer('aBunder')->nullable();
            $table->integer('aTanggungKecil')->nullable();
            $table->integer('aTanggungBesar')->nullable();
            $table->integer('aTangan')->nullable();
            $table->integer('aKepalaPutih')->nullable();
            $table->integer('aKepalaBelang')->nullable();
            
            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdPotongProsesId')->references('id')->on('gd_potongProses');
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
