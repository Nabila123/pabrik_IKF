<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateKodeScanAtPackingRekapDetailToPackingRekap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_packingrekap', function (Blueprint $table) {
            $table->dropForeign('gd_packingrekap_pegawaiid_foreign');
            $table->dropColumn('pegawaiId');           
        });

        Schema::table('gd_packingrekap_detail', function (Blueprint $table) {
            $table->dropColumn('kodeScan');        
        });


        Schema::table('gd_packingrekap', function (Blueprint $table) {
            $table->string('kodePacking')->after('id')->nullable();           
        });

        Schema::table('gd_packingrekap_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('pegawaiId')->after('gdBajuStokOpnameId');
            $table->foreign('pegawaiId')->references('id')->on('mst_pegawai');        
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
