<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdInspeksiMasukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_inspeksiMasuk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gudangId');
            $table->unsignedBigInteger('gdInspeksiKId');
            $table->unsignedBigInteger('purchaseId');
            $table->unsignedBigInteger('materialId');
            $table->unsignedBigInteger('jenisId');
            $table->date('tanggal')->nullable();
            $table->boolean('statusDiterima')->default(0);

            
            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gudangId')->references('id')->on('gd_bahanBaku');
            $table->foreign('gdInspeksiKId')->references('id')->on('gd_inspeksiKeluar');
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
        Schema::dropIfExists('gd_inspeksiMasuk');
    }
}
