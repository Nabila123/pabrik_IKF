<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrGudangKeluarDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_gudang_keluar_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gudangKeluarId');
            $table->unsignedBigInteger('purchaseId');
            $table->integer('qty')->nullable();
            $table->timestamps();

            
            $table->foreign('gudangKeluarId')->references('id')->on('tr_gudang_keluar');
            $table->foreign('purchaseId')->references('id')->on('tr_purchase');
            $table->unique(['gudangKeluarId', 'purchaseId'], 'my_unique_ref');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_gudang_keluar_detail');
    }
}
