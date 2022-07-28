<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeOnGdBarngDatangDetailMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('barang_datang_detail_material', function (Blueprint $table) {
            $table->float('diameter')->change();
            $table->float('gramasi')->change();
            $table->float('brutto')->change();
            $table->float('netto')->change();
            $table->float('tarra')->change();
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
