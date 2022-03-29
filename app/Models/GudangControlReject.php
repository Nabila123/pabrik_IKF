<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangControlReject extends Model
{
    use HasFactory;

    protected $table = 'gd_controlreject';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function CreateGudangControlReject($gudangRequest, $tanggal, $totalBaju, $userId)
    {
        $addGdReject = NEW GudangControlReject;
        $addGdReject->gudangRequest = $gudangRequest;
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

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $jahitRejectUpdated['statusProses'] = $statusDiterima;

        self::where('id', $id)->update($jahitRejectUpdated);

        return 1;
    }
    
}
