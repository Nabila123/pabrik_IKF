<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialModel extends Model
{
    use HasFactory;
    protected $table = 'mst_material';

    public static function getSatuanMaterial($materialId)
    {
        $materials = Self::where('id', $materialId)
                    ->first();

        return $materials;
    }

    public function jenisBarang()
    {
        return $this->hasOne('App\Models\JenisBarang','id','jenisId');
    }

}
