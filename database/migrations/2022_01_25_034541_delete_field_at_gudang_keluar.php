<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteFieldAtGudangKeluar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tr_gudang_keluar', function($table) {
            $table->dropForeign('tr_gudang_keluar_purchaseId_foreign');
            $table->dropColumn('purchaseId');
        });
    }
}
