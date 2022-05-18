<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdBahanBakuDetailMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_bahanBaku_detail_material', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gudangDetailId');
            $table->integer('diameter')->nullable();
            $table->integer('gramasi')->nullable();
            $table->integer('brutto')->nullable();
            $table->integer('netto')->nullable();
            $table->integer('tarra')->nullable();
            $table->string('unit')->nullable();
            $table->string('unitPrice')->nullable();
            $table->string('amount')->nullable();
            $table->string('remark')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gudangDetailId')->references('id')->on('gd_bahanBaku_detail');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['gudangDetailId', 'userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gd_bahanBaku_detail_material');
    }
}
