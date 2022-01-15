<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrGudangStokOpnameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_gudang_stok_opname', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchaseId');
            $table->unsignedBigInteger('materialId');
            $table->unsignedBigInteger('jenisId');
            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('purchaseId')->references('id')->on('tr_purchase');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->foreign('jenisId')->references('id')->on('mst_jenis_barang');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['purchaseId','materialId', 'jenisId','userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_gudang_stok_opname');
    }
}
