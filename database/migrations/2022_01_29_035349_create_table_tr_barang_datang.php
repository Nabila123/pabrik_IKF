<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTrBarangDatang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_barang_datang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchaseId');
            $table->string('namaSuplier')->nullable();
            $table->timestamps();
        });
        Schema::create('tr_barang_datang_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barangDatangId');
            $table->unsignedBigInteger('materialId');
            $table->string('jumlah_datang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_tr_barang_datang');
    }
}
