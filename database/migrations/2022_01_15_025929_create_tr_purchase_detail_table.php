<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrPurchaseDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_purchase_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchaseId');
            $table->unsignedBigInteger('materialId');
            $table->integer('qty')->nullable();
            $table->string('unit')->nullable();
            $table->string('unitPrice')->nullable();
            $table->string('amount')->nullable();
            $table->string('remark')->nullable();
            $table->timestamps();

            $table->foreign('purchaseId')->references('id')->on('tr_purchase');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->index(['purchaseId', 'materialId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_purchase_detail');
    }
}
