<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteFieldAtGudangMasuk extends Migration
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
        Schema::table('tr_gudang_masuk', function($table) {
            $table->dropForeign('tr_gudang_masuk_purchaseId_foreign');
            $table->dropColumn('purchaseId');
        });
    }
}
