<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableGdBajuStokOpname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_baju_stok_opname', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gdPotongProsesId');
            $table->unsignedBigInteger('purchaseId');
            $table->string('jenisBaju')->nullable();
            $table->string('ukuranBaju')->nullable();
            $table->boolean('soom')->nullable();
            $table->boolean('bawahan')->nullable();
            $table->boolean('jahit')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdPotongProsesId')->references('id')->on('gd_potongproses_detail');
            $table->foreign('purchaseId')->references('id')->on('purchase');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['gdPotongProsesId', 'purchaseId', 'userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gd_baju_stok_opname');
    }
}
