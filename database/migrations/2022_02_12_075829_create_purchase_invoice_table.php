<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gudangId');
            $table->unsignedBigInteger('purchaseId');
            $table->string('total')->nullable();
            $table->date('paymentDue')->nullable();

            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('gudangId')->references('id')->on('gd_bahanBaku');
            $table->foreign('purchaseId')->references('id')->on('purchase');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['gudangId', 'purchaseId', 'userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_invoice');
    }
}
