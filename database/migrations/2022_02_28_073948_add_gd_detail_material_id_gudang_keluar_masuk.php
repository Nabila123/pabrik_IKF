<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGdDetailMaterialIdGudangKeluarMasuk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_rajutmasuk_detail', function (Blueprint $table) {            
            $table->unsignedBigInteger('gdDetailMaterialId')->after('gudangId');
           
            $table->foreign('gdDetailMaterialId')->references('id')->on('gd_bahanbaku_detail_material');
        });

        Schema::table('gd_cuciKeluar_detail', function (Blueprint $table) {            
            $table->unsignedBigInteger('gdDetailMaterialId')->after('gudangId');
           
            $table->foreign('gdDetailMaterialId')->references('id')->on('gd_bahanbaku_detail_material');
        });

        Schema::table('gd_compactKeluar_detail', function (Blueprint $table) {            
            $table->unsignedBigInteger('gdDetailMaterialId')->after('gudangId');
           
            $table->foreign('gdDetailMaterialId')->references('id')->on('gd_bahanbaku_detail_material');
        });

        Schema::table('gd_compactMasuk_detail', function (Blueprint $table) {            
            $table->unsignedBigInteger('gdDetailMaterialId')->after('gudangId');
           
            $table->foreign('gdDetailMaterialId')->references('id')->on('gd_bahanbaku_detail_material');
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
