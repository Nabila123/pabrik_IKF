<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGdJahitBasisUntilGdJahitDetailMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('gd_jahit_basis', 'gd_jahitBasis');
        Schema::rename('gd_jahit_detail', 'gd_jahitBasis_Pegawai');
        Schema::rename('gd_jahit_detail_material', 'gd_jahitBasis_Detail');

        Schema::table('gd_jahitBasis', function (Blueprint $table) {
            $table->enum('posisi',['Soom','Jahit','Bawahan'])->after('id')->nullable();
            $table->dropForeign('gd_jahit_basis_pegawaiid_foreign');
            $table->dropColumn('pegawaiId');
        });

        Schema::table('gd_jahitBasis_Pegawai', function (Blueprint $table) {
            $table->unsignedBigInteger('pegawaiId')->after('gdJahitBasisId');
            $table->foreign('pegawaiId')->references('id')->on('mst_pegawai');

            $table->dropForeign('gd_jahit_detail_userid_foreign');
            $table->dropColumn('userId');

            $table->dropColumn('jumlah');

            $table->integer('total')->after('tanggal');
        });

        Schema::table('gd_jahitBasis_Detail', function (Blueprint $table) {
            $table->dropForeign('gd_jahit_detail_material_gdbajustokopnameid_foreign');
            $table->dropColumn('gdBajuStokOpnameId');

            $table->dropForeign('gd_jahit_detail_material_gdjahitdetailid_foreign');
            $table->dropColumn('gdJahitDetailId');

            $table->dropForeign('gd_jahit_detail_material_userid_foreign');
            $table->dropColumn('userId');

            $table->dropColumn('tanggal');
            $table->dropColumn('ukuranBaju');

            $table->unsignedBigInteger('gdJahitBasisPegawaiId')->after('id');
            $table->foreign('gdJahitBasisPegawaiId')->references('id')->on('gd_jahitBasis_Pegawai');

            $table->date('tanggal')->after('gdJahitBasisPegawaiId');
            $table->integer('total')->after('tanggal');

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
