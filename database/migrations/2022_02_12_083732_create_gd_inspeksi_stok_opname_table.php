<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdInspeksiStokOpnameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_inspeksi_stok_opname', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdDetailMaterialId');
            $table->unsignedBigInteger('purchaseId');
            $table->unsignedBigInteger('materialId');
            $table->unsignedBigInteger('jenisId');
            $table->date('tanggal')->nullable();

            $table->string('berat')->nullable();
            $table->string('yard')->nullable();
            $table->boolean('lubang')->default(0)->nullable();
            $table->boolean('plek')->default(0)->nullable();
            $table->boolean('belang')->default(0)->nullable();
            $table->boolean('tanah')->default(0)->nullable();
            $table->boolean('bs')->default(0)->nullable();
            $table->boolean('jarum')->default(0)->nullable();
            $table->text('keterangan')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdDetailMaterialId')->references('id')->on('gd_bahanBaku_detail_material');
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
        Schema::dropIfExists('gd_inspeksi_stok_opname');
    }
}
