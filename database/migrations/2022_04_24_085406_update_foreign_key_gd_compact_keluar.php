<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForeignKeyGdCompactKeluar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_compactkeluar', function (Blueprint $table) {            
            $table->dropForeign('gd_compactkeluar_gdcucikid_foreign');
            $table->dropColumn('gdCuciKId');
        });

        Schema::table('gd_compactkeluar', function (Blueprint $table) {            
            $table->unsignedBigInteger('gdCuciKId')->after('id')->nullable();
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
