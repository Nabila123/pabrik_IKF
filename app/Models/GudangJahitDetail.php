<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_jahitbasis_pegawai';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function pegawai()
    {
        return $this->hasOne('App\Models\Pegawai','id','pegawaiId');
    }

    public static function CreateGudangBasisPegawai($gdJahitBasisId, $pegawaiId, $total)
    {
        $addGdBasisPegawai = NEW GudangJahitDetail;
        $addGdBasisPegawai->gdJahitBasisId = $gdJahitBasisId;
        $addGdBasisPegawai->pegawaiId = $pegawaiId;
        $addGdBasisPegawai->tanggal = date('Y-m-d');
        $addGdBasisPegawai->total = $total;
        $addGdBasisPegawai->created_at = date('Y-m-d H:i:s');

        if ($addGdBasisPegawai->save()) {
            return $addGdBasisPegawai->id;
        } else {
            return 0;
        }
    }

    public static function GudangBasisPegawaiUpdateField($fieldName, $updatedField, $id)
    {
        $GudangBasisPegawaiFieldUpdated[$fieldName] = $updatedField;
        $success = self::where('id', $id)->update($GudangBasisPegawaiFieldUpdated);

        if ($success) {
            return 1;
        }
    }
}
