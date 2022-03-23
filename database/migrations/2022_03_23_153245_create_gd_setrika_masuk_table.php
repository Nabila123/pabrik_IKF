<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdSetrikaMasukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_setrikaMasuk', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->boolean('statusDiterima')->default(0);

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users');
        });

        Schema::create('gd_setrikaMasuk_Detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gdSetrikaMId');
            $table->unsignedBigInteger('gdBajuStokOpnameId');
            $table->unsignedBigInteger('purchaseId');
            $table->string('jenisBaju')->nullable();
            $table->string('ukuranBaju')->nullable();
            $table->integer('qty')->nullable();

            $table->timestamps();

            $table->foreign('gdSetrikaMId')->references('id')->on('gd_setrikaMasuk');
            $table->foreign('gdBajuStokOpnameId')->references('id')->on('gd_baju_stok_opname');
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
