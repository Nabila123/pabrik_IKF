<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdJahitReject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_jahit_reject', function (Blueprint $table) {
            $table->id();
            $table->string('gudangRequest');
            $table->datetime('tanggal');
            $table->integer('totalBaju');
            $table->boolean('statusProses')->default(0);
            $table->unsignedBigInteger('userId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gd_jahit_reject');
    }
}
