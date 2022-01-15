<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrGudangPotongRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_gudang_potong_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('tanggal')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_gudang_potong_request');
    }
}
