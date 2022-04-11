<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForegnKeyGdPotongKIdInGdPotongProses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_potongproses', function (Blueprint $table) {            
            $table->dropForeign('gd_potongproses_gpotongkid_foreign');
            $table->dropColumn('gPotongKId');
        });

        Schema::table('gd_potongproses', function (Blueprint $table) {            
            $table->unsignedBigInteger('gPotongKId')->after('id');
            $table->foreign('gPotongKId')->references('id')->on('gd_potongkeluar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
