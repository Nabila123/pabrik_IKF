<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeteranganRusakInGdControlRekap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_controlrekap_detail', function (Blueprint $table) {
            $table->string('keterangan')->after('ukuranBaju')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gd_control_rekap', function (Blueprint $table) {
            //
        });
    }
}
