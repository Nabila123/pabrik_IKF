<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGramasiInGdPotongProsesDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_potongProses_detail', function (Blueprint $table) {            
            $table->integer('gramasi')->after('diameter')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gd_potong_proses_detail', function (Blueprint $table) {
            //
        });
    }
}
