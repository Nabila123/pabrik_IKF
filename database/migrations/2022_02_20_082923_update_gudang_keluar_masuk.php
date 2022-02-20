<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGudangKeluarMasuk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_rajutmasuk', function (Blueprint $table) {
            $table->dropForeign('gd_rajutmasuk_gudangid_foreign');
            $table->dropForeign('gd_rajutmasuk_jenisid_foreign');
            $table->dropForeign('gd_rajutmasuk_materialid_foreign');
            $table->dropForeign('gd_rajutmasuk_purchaseid_foreign');

            $table->dropColumn('gudangId');
            $table->dropColumn('jenisId');
            $table->dropColumn('materialId');
            $table->dropColumn('purchaseId');
        });

        Schema::table('gd_cucikeluar', function (Blueprint $table) {
            $table->dropForeign('gd_cucikeluar_gudangid_foreign');
            $table->dropForeign('gd_cucikeluar_jenisid_foreign');
            $table->dropForeign('gd_cucikeluar_materialid_foreign');
            $table->dropForeign('gd_cucikeluar_purchaseid_foreign');

            $table->dropColumn('gudangId');
            $table->dropColumn('jenisId');
            $table->dropColumn('materialId');
            $table->dropColumn('purchaseId');
        });

        Schema::table('gd_compactkeluar', function (Blueprint $table) {
            $table->dropForeign('gd_compactkeluar_gudangid_foreign');
            $table->dropForeign('gd_compactkeluar_jenisid_foreign');
            $table->dropForeign('gd_compactkeluar_materialid_foreign');
            $table->dropForeign('gd_compactkeluar_purchaseid_foreign');

            $table->dropColumn('gudangId');
            $table->dropColumn('jenisId');
            $table->dropColumn('materialId');
            $table->dropColumn('purchaseId');
        });

        Schema::table('gd_compactmasuk', function (Blueprint $table) {
            $table->dropForeign('gd_compactmasuk_gudangid_foreign');
            $table->dropForeign('gd_compactmasuk_jenisid_foreign');
            $table->dropForeign('gd_compactmasuk_materialid_foreign');
            $table->dropForeign('gd_compactmasuk_purchaseid_foreign');

            $table->dropColumn('gudangId');
            $table->dropColumn('jenisId');
            $table->dropColumn('materialId');
            $table->dropColumn('purchaseId');
        });

        Schema::table('gd_inspeksikeluar', function (Blueprint $table) {
            $table->dropForeign('gd_inspeksikeluar_gudangid_foreign');
            $table->dropForeign('gd_inspeksikeluar_jenisid_foreign');
            $table->dropForeign('gd_inspeksikeluar_materialid_foreign');
            $table->dropForeign('gd_inspeksikeluar_purchaseid_foreign');

            $table->dropColumn('gudangId');
            $table->dropColumn('jenisId');
            $table->dropColumn('materialId');
            $table->dropColumn('purchaseId');
        });

        Schema::table('gd_inspeksimasuk', function (Blueprint $table) {
            $table->dropForeign('gd_inspeksimasuk_gudangid_foreign');
            $table->dropForeign('gd_inspeksimasuk_jenisid_foreign');
            $table->dropForeign('gd_inspeksimasuk_materialid_foreign');
            $table->dropForeign('gd_inspeksimasuk_purchaseid_foreign');

            $table->dropColumn('gudangId');
            $table->dropColumn('jenisId');
            $table->dropColumn('materialId');
            $table->dropColumn('purchaseId');
        });

        Schema::table('gd_rajutmasuk_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('gudangId')->after('id');
            $table->unsignedBigInteger('purchaseId')->after('gdRajutMId');
            $table->unsignedBigInteger('materialId')->after('purchaseId');
            $table->unsignedBigInteger('jenisId')->after('materialId');

            $table->foreign('gudangId')->references('id')->on('gd_bahanBaku');
            $table->foreign('purchaseId')->references('id')->on('purchase');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->foreign('jenisId')->references('id')->on('mst_jenisBarang');
        });

        Schema::table('gd_cucikeluar_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('gudangId')->after('id');
            $table->unsignedBigInteger('purchaseId')->after('gdCuciKId');
            $table->unsignedBigInteger('materialId')->after('purchaseId');
            $table->unsignedBigInteger('jenisId')->after('materialId');

            $table->foreign('gudangId')->references('id')->on('gd_bahanBaku');
            $table->foreign('purchaseId')->references('id')->on('purchase');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->foreign('jenisId')->references('id')->on('mst_jenisBarang');
        });

        Schema::table('gd_compactkeluar_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('gudangId')->after('id');
            $table->unsignedBigInteger('purchaseId')->after('gdCompactKId');
            $table->unsignedBigInteger('materialId')->after('purchaseId');
            $table->unsignedBigInteger('jenisId')->after('materialId');

            $table->foreign('gudangId')->references('id')->on('gd_bahanBaku');
            $table->foreign('purchaseId')->references('id')->on('purchase');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->foreign('jenisId')->references('id')->on('mst_jenisBarang');
        });

        Schema::table('gd_compactmasuk_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('gudangId')->after('id');
            $table->unsignedBigInteger('purchaseId')->after('gdCompactMId');
            $table->unsignedBigInteger('materialId')->after('purchaseId');
            $table->unsignedBigInteger('jenisId')->after('materialId');

            $table->foreign('gudangId')->references('id')->on('gd_bahanBaku');
            $table->foreign('purchaseId')->references('id')->on('purchase');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->foreign('jenisId')->references('id')->on('mst_jenisBarang');
        });

        Schema::table('gd_inspeksikeluar_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('gudangId')->after('gdInspeksiKId');
            $table->unsignedBigInteger('purchaseId')->after('gdDetailMaterialId');
            $table->unsignedBigInteger('materialId')->after('purchaseId');
            $table->unsignedBigInteger('jenisId')->after('materialId');

            $table->foreign('gudangId')->references('id')->on('gd_bahanBaku');
            $table->foreign('purchaseId')->references('id')->on('purchase');
            $table->foreign('materialId')->references('id')->on('mst_material');
            $table->foreign('jenisId')->references('id')->on('mst_jenisBarang');
        });

        Schema::table('gd_inspeksimasuk_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('gudangId')->after('gdInspeksiMId');
            $table->unsignedBigInteger('purchaseId')->after('gdDetailMaterialId');
            $table->unsignedBigInteger('materialId')->after('purchaseId');
            $table->unsignedBigInteger('jenisId')->after('materialId');

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
        //
    }
}
