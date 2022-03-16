<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdJahitRekapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_jahitRekap', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('posisi',['Soom','Jahit','Bawahan'])->nullable();
            $table->date('tanggal')->nullable();
            $table->unsignedBigInteger('userId');
            
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users');
        });

        Schema::create('gd_jahitRekap_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdJahitRekapId');
            $table->unsignedBigInteger('pegawaiId');
            $table->date('tanggal')->nullable();
            $table->unsignedBigInteger('gdBajuStokOpnameId');
            $table->unsignedBigInteger('purchaseId');
            $table->string('jenisBaju');
            $table->string('ukuranBaju');
            
            $table->timestamps();

            $table->foreign('gdJahitRekapId')->references('id')->on('gd_jahitRekap');
            $table->foreign('pegawaiId')->references('id')->on('mst_pegawai');
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
        Schema::dropIfExists('gd__jahit_rekap');
    }
}
