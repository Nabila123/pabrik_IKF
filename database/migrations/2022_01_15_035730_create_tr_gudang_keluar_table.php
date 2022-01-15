<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrGudangKeluarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_gudang_keluar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gStokId');
            $table->unsignedBigInteger('purchaseId');
            $table->unsignedBigInteger('materialId');
            $table->unsignedBigInteger('jenisId');

            $table->string('gudangRequest')->nullable();
            $table->date('tanggal')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gStokId')->references('id')->on('tr_gudang_stok_opname');
            $table->foreign('purchaseId')->references('id')->on('tr_purchase');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->foreign('jenisId')->references('id')->on('mst_jenis_barang');
            $table->foreign('userId')->references('id')->on('users');
            $table->unique(['gStokId', 'purchaseId','materialId', 'jenisId','userId'], 'my_unique_ref');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_gudang_keluar');
    }
}
