<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdPotongRequestDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_potong_request_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdPotongReqId');
            $table->unsignedBigInteger('materialId');
            $table->string('ukuranBaju')->nullable();
            $table->integer('qty')->nullable();
            $table->timestamps();

            
            $table->foreign('gdPotongReqId')->references('id')->on('gd_potong_request');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->index(['gdPotongReqId', 'materialId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gd_potong_request_detail');
    }
}
