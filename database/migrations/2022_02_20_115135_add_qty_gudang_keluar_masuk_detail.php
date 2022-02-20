<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQtyGudangKeluarMasukDetail extends Migration
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

        Schema::table('gd_bahanbaku_detail', function (Blueprint $table) {            
            $table->dropColumn('qty');
        });

        Schema::table('gd_bahanbaku_detail_material', function (Blueprint $table) {            
            $table->dropColumn('tarra');
        });

        Schema::table('gd_rajutmasuk_detail', function (Blueprint $table) {
            $table->dropColumn('berat');
        });

        Schema::table('gd_cucikeluar_detail', function (Blueprint $table) {
            $table->dropColumn('berat');
        });

        Schema::table('gd_compactkeluar_detail', function (Blueprint $table) {
            $table->dropColumn('berat');
        });

        Schema::table('gd_compactmasuk_detail', function (Blueprint $table) {
            $table->dropColumn('berat');
        });

        Schema::table('gd_inspeksikeluar_detail', function (Blueprint $table) {
            $table->dropColumn('berat');
        });

        Schema::table('gd_inspeksimasuk_detail', function (Blueprint $table) {
            $table->dropColumn('berat');
        });



   

        Schema::table('gd_bahanbaku_detail', function (Blueprint $table) {            
            $table->integer('qtyPermintaan')->after('materialId')->default(0);
            $table->integer('qtySaatIni')->after('qtyPermintaan')->default(0);
        });

        Schema::table('gd_bahanbaku_detail_material', function (Blueprint $table) {            
            $table->float('tarra')->after('netto')->default(0);
        });

        Schema::table('gd_rajutkeluar_detail', function (Blueprint $table) {            
            $table->integer('qty')->after('jenisId')->default(0);
        });

        Schema::table('gd_rajutmasuk_detail', function (Blueprint $table) {
            $table->float('berat')->after('diameter')->default(0);
            $table->integer('qty')->after('berat')->default(0);

        });

        Schema::table('gd_cucikeluar_detail', function (Blueprint $table) {
            $table->float('berat')->after('diameter')->default(0);
            $table->integer('qty')->after('berat')->default(0);

        });

        Schema::table('gd_compactkeluar_detail', function (Blueprint $table) {
            $table->float('berat')->after('diameter')->default(0);
            $table->integer('qty')->after('berat')->default(0);

        });

        Schema::table('gd_compactmasuk_detail', function (Blueprint $table) {
            $table->float('berat')->after('diameter')->default(0);
            $table->integer('qty')->after('berat')->default(0);

        });

        Schema::table('gd_inspeksikeluar_detail', function (Blueprint $table) {
            $table->float('berat')->after('diameter')->default(0);
            $table->integer('qty')->after('berat')->default(0);

        });

        Schema::table('gd_inspeksimasuk_detail', function (Blueprint $table) {
            $table->float('berat')->after('diameter')->default(0);
            $table->integer('qty')->after('berat')->default(0);

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
