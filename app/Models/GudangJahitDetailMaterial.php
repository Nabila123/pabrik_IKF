<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitDetailMaterial extends Model
{
    use HasFactory;

    protected $table = 'gd_jahitbasis_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }
    
    public static function CreateGudangBasisDetail($gdJahitBasisPegawaiId, $total)
    {
        $addGdBasisDetail = NEW GudangJahitDetailMaterial;
        $addGdBasisDetail->gdJahitBasisPegawaiId = $gdJahitBasisPegawaiId;
        $addGdBasisDetail->tanggal = date('Y-m-d');
        $addGdBasisDetail->total = $total;
        $addGdBasisDetail->created_at = date('Y-m-d H:i:s');

        if ($addGdBasisDetail->save()) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $inspeksiUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($inspeksiUpdated);

        return 1;
    }
}
