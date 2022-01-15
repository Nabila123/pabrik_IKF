<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_menu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parentId');
            $table->integer('urutan')->nullable();
            $table->string('nama')->nullable();
            $table->string('alias')->nullable();
            $table->string('directori')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('isActive')->default(0)->nullable();    
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
        Schema::dropIfExists('menu');
    }
}
