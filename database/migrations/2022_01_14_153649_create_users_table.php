<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama')->nullable();
            $table->string('nip')->nullable();
            $table->string('password')->nullable();
            $table->string('passwordAsli')->nullable();
            $table->rememberToken();
            $table->unsignedBigInteger('roleId');
            $table->timestamps();

            $table->foreign('roleId')->references('id')->on('mst_role');
            $table->index(['roleId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
