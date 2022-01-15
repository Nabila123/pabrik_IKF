<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrInspeksiStokOpnameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_inspeksi_stok_opname', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gudangStokId');
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

            $table->foreign('gudangStokId')->references('id')->on('tr_gudang_stok_opname');
            $table->foreign('purchaseId')->references('id')->on('tr_purchase');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->foreign('jenisId')->references('id')->on('mst_jenis_barang');
            $table->foreign('userId')->references('id')->on('users');
            $table->unique(['gudangStokId', 'purchaseId','materialId', 'jenisId','userId'], 'my_unique_ref');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_inspeksi_stok_opname');
    }
}
