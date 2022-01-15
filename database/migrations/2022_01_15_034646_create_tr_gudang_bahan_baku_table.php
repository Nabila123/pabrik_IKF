<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrGudangBahanBakuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_gudang_bahan_baku', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kodePurchase')->nullable();
            $table->string('namaSuplier')->nullable();
            $table->string('diameter')->nullable();
            $table->string('gramasi')->nullable();
            $table->string('total')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users');
            $table->index(['userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_gudang_bahan_baku');
    }
}
