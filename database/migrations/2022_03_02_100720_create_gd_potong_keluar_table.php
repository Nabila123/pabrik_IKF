<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdPotongKeluarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_potongKeluar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('tanggal')->nullable();
            $table->boolean('statusDiterima')->default(0);            
            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users');
        });

        Schema::create('gd_potongKeluar_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdPotongKId');
            $table->unsignedBigInteger('gudangId');
            $table->unsignedBigInteger('gdDetailMaterialId')->nullable();
            $table->unsignedBigInteger('gdInspeksiStokId')->nullable();
            $table->unsignedBigInteger('purchaseId');
            $table->unsignedBigInteger('materialId');
            $table->unsignedBigInteger('jenisId');
            $table->integer('gramasi')->nullable();
            $table->integer('diameter')->nullable();
            $table->float('berat')->nullable();
            $table->integer('qty')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdPotongKId')->references('id')->on('gd_potongKeluar');
            $table->foreign('gudangId')->references('id')->on('gd_bahanBaku');
            $table->foreign('gdDetailMaterialId')->references('id')->on('gd_bahanBaku_detail_material');
            $table->foreign('gdInspeksiStokId')->references('id')->on('gd_inspeksi_stok_opname');
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
        Schema::dropIfExists('gd_potong_keluar');
    }
}
