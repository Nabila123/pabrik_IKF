<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldApproveTrPurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tr_purchase', function (Blueprint $table) {
            $table->unsignedBigInteger('isKaDeptProd')->default(0)->change();
            $table->unsignedBigInteger('isKaDeptPO')->default(0)->change();
            $table->unsignedBigInteger('isKaDivPO')->default(0)->change();
            $table->unsignedBigInteger('isKaDivFin')->default(0)->change();
        });
    }
}
