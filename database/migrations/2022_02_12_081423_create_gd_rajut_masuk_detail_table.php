<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdRajutMasukDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_rajutMasuk_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdRajutMId');
            $table->integer('gramasi')->nullable();
            $table->integer('diameter')->nullable();
            $table->integer('berat')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdRajutMId')->references('id')->on('gd_rajutMasuk');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['gdRajutMId', 'userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gd_rajutMasuk_detail');
    }
}
