<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitBasis extends Model
{
    use HasFactory;
    protected $table = 'gd_jahitbasis';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function CreateGudangBasis($posisi, $qtyTarget, $total, $userId)
    {
        $addGdBasis = NEW GudangJahitBasis;
        $addGdBasis->posisi = $posisi;
        $addGdBasis->qtyTarget = $qtyTarget;
        $addGdBasis->total = $total;
        $addGdBasis->userId = $userId;
        $addGdBasis->created_at = date('Y-m-d H:i:s');

        if ($addGdBasis->save()) {
            return $addGdBasis->id;
        } else {
            return 0;
        }

    }

    public static function GudangBasisUpdateField($fieldName, $updatedField, $id)
    {
        $GudangBasisFieldUpdated[$fieldName] = $updatedField;
        $success = self::where('id', $id)->update($GudangBasisFieldUpdated);

        if ($success) {
            return 1;
        }
    }
}
