<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStatusDiterimaTrGudangKeluar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_gudang_keluar', function (Blueprint $table) {
            $table->boolean('statusDiterima')->after('tanggal')->default(0)->nullable();
        });

        Schema::table('tr_gudang_masuk', function (Blueprint $table) {
            $table->boolean('statusDiterima')->after('tanggal')->default(0)->nullable();
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
