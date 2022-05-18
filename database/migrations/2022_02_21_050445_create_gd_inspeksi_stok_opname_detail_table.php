<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdInspeksiStokOpnameDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_inspeksi_stok_opname', function (Blueprint $table) {            
            $table->dropColumn('berat');
            $table->dropColumn('yard');
            $table->dropColumn('lubang');
            $table->dropColumn('plek');
            $table->dropColumn('belang');
            $table->dropColumn('tanah');
            $table->dropColumn('bs');
            $table->dropColumn('jarum');
            $table->dropColumn('keterangan');
        });

        Schema::create('gd_inspeksi_stokOpname_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdInspeksiStokId');

            $table->string('roll')->nullable();
            $table->string('berat')->nullable();
            $table->string('yard')->nullable();
            $table->integer('lubang')->default(0)->nullable();
            $table->integer('plek')->default(0)->nullable();
            $table->integer('belang')->default(0)->nullable();
            $table->integer('tanah')->default(0)->nullable();
            $table->integer('bs')->default(0)->nullable();
            $table->integer('jarum')->default(0)->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->foreign('gdInspeksiStokId')->references('id')->on('gd_inspeksi_stok_opname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gd_inspeksi_stok_opname_detail');
    }
}
