<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdJahitMasukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_jahitMasuk', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->boolean('statusProses')->default(0);

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users');
        });

        Schema::create('gd_jahitMasuk_Detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gdJahitMId');
            $table->unsignedBigInteger('gdPotongProsesId');
            $table->unsignedBigInteger('purchaseId');
            $table->string('jenisBaju')->nullable();
            $table->string('ukuranBaju')->nullable();
            $table->integer('qty')->nullable();

            $table->timestamps();

            $table->foreign('gdJahitMId')->references('id')->on('gd_jahitMasuk');
            $table->foreign('gdPotongProsesId')->references('id')->on('gd_potongproses_detail');
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
    }
}
