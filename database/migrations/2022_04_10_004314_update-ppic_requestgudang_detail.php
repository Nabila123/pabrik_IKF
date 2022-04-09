<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePpicRequestgudangDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ppic_gudangrequest_detail', function (Blueprint $table) {            
            $table->dropColumn('gramasi');
            $table->dropColumn('diameter');
        });


        Schema::table('ppic_gudangrequest_detail', function (Blueprint $table) {            
            $table->integer('gramasi')->after('jenisId')->nullable();
            $table->integer('diameter')->after('gramasi')->nullable();
            $table->string('jenisBaju')->after('diameter')->nullable();
            $table->string('ukuranBaju')->after('jenisBaju')->nullable();
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
