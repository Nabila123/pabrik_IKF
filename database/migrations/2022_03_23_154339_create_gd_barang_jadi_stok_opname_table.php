<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdBarangJadiStokOpnameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_barangJadi_stokOpname', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->string('jenisBaju')->nullable();
            $table->string('ukuranBaju')->nullable();
            $table->integer('qty')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();
        });

        Schema::create('gd_barangJadiStokOpname_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gdBarangJadiStokOpnameId');
            $table->unsignedBigInteger('gdBajuStokOpnameId');
            $table->unsignedBigInteger('purchaseId');
            $table->string('kodeScan')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdBarangJadiStokOpnameId')->references('id')->on('gd_barangJadi_stokOpname');
            $table->foreign('gdBajuStokOpnameId')->references('id')->on('gd_baju_stok_opname');
            $table->foreign('purchaseId')->references('id')->on('purchase');
            $table->foreign('userId')->references('id')->on('users');
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
