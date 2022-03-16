<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnqtyPermintaantableBarangDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang_datang_detail', function (Blueprint $table) {
            $table->integer('qtyPermintaan')->after('materialId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barang_datang_detail', function (Blueprint $table) {
            $table->dropColumn('qtyPermintaan');
        });
    }
}
