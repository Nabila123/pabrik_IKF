<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_pegawai', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nip')->nullable();
            $table->string('nama')->nullable();
            $table->string('kodeBagian')->nullable();
            $table->string('email')->nullable();
            $table->enum('jenisKelamin',['P','W'])->nullable();
            $table->string('tempatLahir')->nullable();
            $table->date('tanggalLahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('statusKawin')->nullable();
            $table->string('namaSuamiIstri')->nullable();
            $table->string('kerjaSuamiIstri')->nullable();
            $table->string('namaAnak')->nullable();
            $table->string('noTelp')->nullable();
            $table->string('noHp')->nullable();
            $table->string('pendidikanTerakhir')->nullable();
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
        Schema::dropIfExists('pegawai');
    }
}
