<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateKeteranganInControlRejectDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_controlreject_detail', function (Blueprint $table) {
            $table->dropColumn('keterangan');           
        });

        Schema::table('gd_controlreject_detail', function (Blueprint $table) {
            $table->string('keterangan')->after('gdBajuStokOpnameId')->nullable();      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
