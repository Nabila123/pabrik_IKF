<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQtyGudangRajutKeluarDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_rajutkeluar_detail', function (Blueprint $table) {
            $table->dropColumn('qty');
        });

        Schema::table('gd_rajutkeluar_detail', function (Blueprint $table) {            
            $table->float('qty')->after('jenisId')->default(0);
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
