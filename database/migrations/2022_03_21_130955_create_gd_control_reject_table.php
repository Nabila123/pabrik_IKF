<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdControlRejectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_controlreject', function (Blueprint $table) {
            $table->id();
            $table->string('gudangRequest');
            $table->datetime('tanggal');
            $table->integer('totalBaju');
            $table->boolean('statusProses')->default(0);
            $table->unsignedBigInteger('userId');
            $table->timestamps();
        });

        Schema::create('gd_controlreject_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gdControlRejectId');
            $table->unsignedBigInteger('gdBajuStokOpnameId');
            $table->string('keterangan');
            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdBajuStokOpnameId')->references('id')->on('gd_baju_stok_opname');
            $table->foreign('gdControlRejectId')->references('id')->on('gd_controlreject');
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
        Schema::dropIfExists('gd_control_reject');
    }
}
