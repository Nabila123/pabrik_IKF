<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdJahitRejectDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_jahit_reject_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gdJahitRejectId');
            $table->unsignedBigInteger('gdBajuStokOpnameId');
            $table->string('keterangan');
            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdBajuStokOpnameId')->references('id')->on('gd_baju_stok_opname');
            $table->foreign('gdJahitRejectId')->references('id')->on('gd_jahit_reject');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['gdBajuStokOpnameId','gdJahitRejectId', 'userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gd_jahit_reject_detail');
    }
}
