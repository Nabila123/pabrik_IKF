<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdJahitBasis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_jahit_basis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawaiId');
            $table->integer('qtyTarget');
            $table->integer('total')->nullable();
            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('pegawaiId')->references('id')->on('mst_pegawai');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['pegawaiId', 'userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gd_jahit_basis');
    }
}
