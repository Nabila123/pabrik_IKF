<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdJahitKeluarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_jahitKeluar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('posisi',['Soom','Jahit','Bawahan'])->nullable();
            $table->date('tanggal')->nullable();
            $table->unsignedBigInteger('pegawaiId');
            $table->unsignedBigInteger('userId');
            
            $table->timestamps();

            $table->foreign('pegawaiId')->references('id')->on('mst_pegawai');
            $table->foreign('userId')->references('id')->on('users');
        });

        Schema::create('gd_jahitKeluar_Detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdJahitKId');
            $table->date('tanggal')->nullable();
            $table->unsignedBigInteger('gdBajuStokOpnameId');
            $table->unsignedBigInteger('purchaseId');
            $table->string('jenisBaju');
            $table->string('ukuranBaju');
            
            $table->timestamps();

            $table->foreign('gdJahitKId')->references('id')->on('gd_jahitKeluar');
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
        Schema::dropIfExists('gd_jahit_keluar_');
    }
}
