<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdPotongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_potong', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchaseId');
            $table->unsignedBigInteger('materialId');
            $table->unsignedBigInteger('jenisId');

            $table->integer('jumlahRoll')->nullable();
            $table->integer('beratPotong')->nullable();
            $table->integer('diameter')->nullable();
            $table->integer('beratRoll')->nullable();
            $table->string('jenisBaju')->nullable();
            $table->string('ukuranBaju')->nullable();
            $table->integer('hasilDz')->nullable();
            $table->integer('hasilKg')->nullable();
            
            $table->integer('skb')->nullable();
            $table->integer('bs')->nullable();
            $table->integer('aKecil')->nullable();
            $table->integer('aKetek')->nullable();
            $table->integer('aKetekPtongan')->nullable();
            $table->integer('aSumbu')->nullable();
            $table->integer('aBunder')->nullable();
            $table->integer('aTanggungKecil')->nullable();
            $table->integer('aTanggungBesar')->nullable();
            $table->integer('aTangan')->nullable();
            $table->integer('aKepalaKainPutih')->nullable();
            $table->integer('aKepalaKainBelang')->nullable();


            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('purchaseId')->references('id')->on('purchase');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->foreign('jenisId')->references('id')->on('mst_jenisBarang');
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
        Schema::dropIfExists('gd_potong');
    }
}
