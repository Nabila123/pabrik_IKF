<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalInBatilStokOpname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_batil_stokOpname', function (Blueprint $table) {
            $table->date('tanggal')->after('gdBajuStokOpnameId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batil_stok_opname', function (Blueprint $table) {
            //
        });
    }
}
