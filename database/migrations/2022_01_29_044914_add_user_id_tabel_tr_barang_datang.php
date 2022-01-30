<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdTabelTrBarangDatang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_barang_datang', function (Blueprint $table) {
            $table->unsignedBigInteger('userId')->after('namaSuplier');

            $table->foreign('userId')->references('id')->on('users');
            $table->index(['userId']);
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
