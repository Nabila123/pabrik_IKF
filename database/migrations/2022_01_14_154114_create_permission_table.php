<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('menuId');
            $table->unsignedBigInteger('roleId');
            $table->boolean('isCreate')->default(0)->nullable();
            $table->boolean('isRead')->default(0)->nullable();
            $table->boolean('isUpdate')->default(0)->nullable();
            $table->boolean('isDelete')->default(0)->nullable();
            $table->timestamps();

            $table->foreign('menuId')->references('id')->on('mst_menu');
            $table->foreign('roleId')->references('id')->on('mst_role');

            $table->index(['menuId','roleId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission');
    }
}
