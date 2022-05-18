<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGudangBahanBakuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_bahanBaku', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchaseId');
            $table->string('namaSuplier')->nullable();
            $table->string('total')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('purchaseId')->references('id')->on('purchase');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['purchaseId', 'userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gd_bahanBaku');
    }
}
