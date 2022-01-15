<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrGudangBahanBakuDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_gudang_bahan_baku_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gudangId');
            $table->unsignedBigInteger('materialId');
            $table->integer('qty')->nullable();
            $table->integer('brutto')->nullable();
            $table->integer('netto')->nullable();
            $table->integer('tarra')->nullable();
            $table->string('unit')->nullable();
            $table->string('unitPrice')->nullable();
            $table->string('amount')->nullable();
            $table->string('remark')->nullable();
            $table->timestamps();

            $table->foreign('gudangId')->references('id')->on('tr_gudang_bahan_baku');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->index(['gudangId', 'materialId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_gudang_bahan_baku_detail');
    }
}
