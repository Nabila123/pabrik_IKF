<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdRajutKeluarDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_rajutkeluar', function (Blueprint $table) {
            $table->dropForeign('gd_rajutkeluar_gudangid_foreign');
            $table->dropForeign('gd_rajutkeluar_jenisid_foreign');
            $table->dropForeign('gd_rajutkeluar_materialid_foreign');
            $table->dropForeign('gd_rajutkeluar_purchaseid_foreign');

            $table->dropColumn('gudangId');
            $table->dropColumn('jenisId');
            $table->dropColumn('materialId');
            $table->dropColumn('purchaseId');
            $table->dropColumn('qty');
        });

        Schema::create('gd_rajutkeluar_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdRajutKId');
            $table->unsignedBigInteger('gudangId');
            $table->unsignedBigInteger('purchaseId');
            $table->unsignedBigInteger('materialId');
            $table->unsignedBigInteger('jenisId');
            $table->integer('qty')->nullable();

            $table->timestamps();

            $table->foreign('gdRajutKId')->references('id')->on('gd_rajutkeluar');
            $table->foreign('gudangId')->references('id')->on('gd_bahanBaku');
            $table->foreign('purchaseId')->references('id')->on('purchase');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->foreign('jenisId')->references('id')->on('mst_jenisBarang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gd_rajut_keluar_detail');
    }
}
