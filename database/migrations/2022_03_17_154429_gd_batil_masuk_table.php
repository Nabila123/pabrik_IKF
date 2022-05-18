<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GdBatilMasukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_batilMasuk', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->boolean('statusDiterima')->default(0);

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users');
        });

        Schema::create('gd_batilMasuk_Detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gdBatilMId');
            $table->unsignedBigInteger('purchaseId');
            $table->string('jenisBaju')->nullable();
            $table->string('ukuranBaju')->nullable();
            $table->integer('qty')->nullable();

            $table->timestamps();

            $table->foreign('gdBatilMId')->references('id')->on('gd_batilMasuk');
            $table->foreign('purchaseId')->references('id')->on('purchase');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
