<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteGdBajuStokOpnameIdInGdBarangJadiPenjualanDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_barangjadipenjualan_detail', function (Blueprint $table) {            
            $table->dropForeign('gd_barangjadipenjualan_detail_gdbajustokopnameid_foreign');
            $table->dropColumn('gdBajuStokOpnameId');
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
