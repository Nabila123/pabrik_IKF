<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdJahitDetailMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_jahit_detail_material', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gdBajuStokOpnameId');
            $table->unsignedBigInteger('gdJahitDetailId');
            $table->string('jenisBaju');
            $table->string('ukuranBaju')->nullable();
            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdBajuStokOpnameId')->references('id')->on('gd_baju_stok_opname');
            $table->foreign('gdJahitDetailId')->references('id')->on('gd_jahit_detail');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['gdBajuStokOpnameId','gdJahitDetailId', 'userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gd_jahit_detail_material');
    }
}
