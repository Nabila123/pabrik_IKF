<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitReject extends Model
{
    use HasFactory;

    protected $table = 'gd_jahit_reject';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function CreateGudangJahitReject($gudangRequest, $rejectTo, $tanggal, $totalBaju, $userId)
    {
        $addGdReject = NEW GudangJahitReject;
        $addGdReject->gudangRequest = $gudangRequest;
        $addGdReject->rejectTo = $rejectTo;
        $addGdReject->tanggal = $tanggal;
        $addGdReject->totalBaju = $totalBaju;
        $addGdReject->userId = $userId;
        $addGdReject->created_at = date('Y-m-d H:i:s');

        if ($addGdReject->save()) {
            return $addGdReject->id;
        } else {
            return 0;
        }

    }

    public static function updateStatusDiterima($fieldName, $updatedField, $id)
    {
        $GudangBasisFieldUpdated[$fieldName] = $updatedField;
        $success = self::where('id', $id)->update($GudangBasisFieldUpdated);

        if ($success) {
            return 1;
        }
    }
}
