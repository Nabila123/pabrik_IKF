<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdPotongRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_potong_request', function (Blueprint $table) {
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
        Schema::dropIfExists('gd_potong_request');
    }
}
