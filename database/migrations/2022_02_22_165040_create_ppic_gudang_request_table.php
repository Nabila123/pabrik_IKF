<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePpicGudangRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ppic_gudang_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('gudangRequest')->nullable();
            $table->date('tanggal')->nullable();
            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users');
        });

        Schema::create('ppic_gudangRequest_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdPpicRequestId');
            $table->date('tanggal')->nullable();
            $table->unsignedBigInteger('materialId');
            $table->unsignedBigInteger('jenisId');

            $table->integer('gramasi')->nullable();
            $table->integer('diameter')->nullable();
            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdPpicRequestId')->references('id')->on('ppic_gudang_request');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->foreign('jenisId')->references('id')->on('mst_jenisbarang');
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
        Schema::dropIfExists('ppic_gudang_request');
    }
}
