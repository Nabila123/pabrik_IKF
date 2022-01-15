<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_purchase', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode')->nullable();
            $table->enum('jenisPurchase',['Purchase Order','Purchase Request'])->nullable();
            $table->date('tanggal')->nullable();
            $table->date('waktu')->nullable();
            $table->date('waktuPayment')->nullable();
            $table->text('note')->nullable();
            $table->string('total')->nullable();

            $table->boolean('isKaDeptProd')->default(0)->nullable();
            $table->date('isKaDeptProdAt')->nullable();
            $table->boolean('isKaDeptPO')->default(0)->nullable();
            $table->date('isKaDeptPOAt')->nullable();
            $table->boolean('isKaDivPO')->default(0)->nullable();
            $table->date('isKaDivPOAt')->nullable();
            $table->boolean('isKaDivFin')->default(0)->nullable();
            $table->date('isKaDivFinAt')->nullable();
            
            $table->boolean('statusDatang')->default(0)->nullable();
            $table->unsignedBigInteger('userId');
            $table->timestamps();

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
        Schema::dropIfExists('tr_purchase');
    }
}
