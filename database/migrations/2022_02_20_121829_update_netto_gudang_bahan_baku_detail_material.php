<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNettoGudangBahanBakuDetailMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_bahanbaku_detail_material', function (Blueprint $table) {            
            $table->dropColumn('brutto');
            $table->dropColumn('netto');
            $table->dropColumn('tarra');
        });

        Schema::table('gd_bahanbaku_detail_material', function (Blueprint $table) {            
            $table->float('brutto')->after('gramasi')->default(0);
            $table->float('netto')->after('brutto')->default(0);
            $table->float('tarra')->after('netto')->default(0);
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
