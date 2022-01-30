<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGudangInspeksiStokOpnameDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_inspeksi_stok_opname', function (Blueprint $table) {
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

        Schema::create('tr_inspeksi_stok_opname_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gudangInspeksiStokId');

            $table->string('roll')->nullable();
            $table->string('berat')->nullable();
            $table->string('yard')->nullable();
            $table->boolean('lubang')->default(0)->nullable();
            $table->boolean('plek')->default(0)->nullable();
            $table->boolean('belang')->default(0)->nullable();
            $table->boolean('tanah')->default(0)->nullable();
            $table->boolean('bs')->default(0)->nullable();
            $table->boolean('jarum')->default(0)->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->foreign('gudangInspeksiStokId')->references('id')->on('tr_inspeksi_stok_opname');
            $table->index(['gudangInspeksiStokId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gudang_inspeksi_stok_opname_detail');
    }
}
