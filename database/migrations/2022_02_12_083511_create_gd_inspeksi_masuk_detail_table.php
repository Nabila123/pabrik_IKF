<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdInspeksiMasukDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_inspeksiMasuk_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdInspeksiMId');
            $table->unsignedBigInteger('gdDetailMaterialId');
            $table->integer('gramasi')->nullable();
            $table->integer('diameter')->nullable();
            $table->integer('berat')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdInspeksiMId')->references('id')->on('gd_inspeksiMasuk');
            $table->foreign('gdDetailMaterialId')->references('id')->on('gd_bahanBaku_detail_material');
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
        Schema::dropIfExists('gd_inspeksiMasuk_detail');
    }
}
