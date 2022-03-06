<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMaterialIdToJenisBajuInGdPotongRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_potong_request', function (Blueprint $table) {
            $table->boolean('statusDiterima')->after('tanggal')->default(0);;
        });

        Schema::table('gd_potong_request_detail', function (Blueprint $table) {
            $table->dropForeign('gd_potong_request_detail_materialid_foreign');

            $table->dropColumn('materialId');
        });

        Schema::table('gd_potong_request_detail', function (Blueprint $table) {
            $table->string('jenisBaju')->after('gdPotongReqId');
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
