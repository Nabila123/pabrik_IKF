<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdCompactKeluarDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_compactKeluar_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdCompactKId');
            $table->integer('gramasi')->nullable();
            $table->integer('diameter')->nullable();
            $table->integer('berat')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdCompactKId')->references('id')->on('gd_compactKeluar');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['gdCompactKId', 'userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gd_compactKeluar_detail');
    }
}
