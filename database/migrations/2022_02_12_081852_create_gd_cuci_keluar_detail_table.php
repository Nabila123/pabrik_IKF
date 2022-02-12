<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdCuciKeluarDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_cuciKeluar_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdCuciKId');
            $table->integer('gramasi')->nullable();
            $table->integer('diameter')->nullable();
            $table->integer('berat')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdCuciKId')->references('id')->on('gd_cuciKeluar');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['gdCuciKId', 'userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gd_cuciKeluar_detail');
    }
}
