<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrGudangPotongRequestDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_gudang_potong_request_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gudangPotongRequestId');
            $table->unsignedBigInteger('materialId');
            $table->string('ukuranBaju')->nullable();
            $table->integer('qty')->nullable();
            $table->timestamps();

            
            $table->foreign('gudangPotongRequestId')->references('id')->on('tr_gudang_potong_request');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->unique(['gudangPotongRequestId', 'materialId'], 'my_unique_ref');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_gudang_potong_request_detail');
    }
}
