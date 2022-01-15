<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_material', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('jenisId');
            $table->string('nama')->nullable();
            $table->string('satuan')->nullable();
            $table->string('jenis')->nullable();
            $table->enum('keterangan',['Bahan Baku','Bahan Bantu / Merchandise']);
            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('jenisId')->references('id')->on('mst_jenis_barang');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['jenisId','userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_material');
    }
}
