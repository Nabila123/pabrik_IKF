<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdJahitDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_jahit_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gdJahitBasisId');
            $table->datetime('tanggal');
            $table->integer('jumlah')->nullable();
            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gdJahitBasisId')->references('id')->on('gd_jahit_basis');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['gdJahitBasisId', 'userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gd_jahit_detail');
    }
}
