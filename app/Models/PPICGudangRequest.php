<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPICGudangRequest extends Model
{
    use HasFactory;
    protected $table = 'ppic_gudang_request';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function CreatePPICGudangRequest($gudangRequest)
    {
        $addPPICRequest = new PPICGudangRequest;
        $addPPICRequest->gudangRequest = $gudangRequest;
        $addPPICRequest->tanggal = date('Y-m-d H:i:s');
        $addPPICRequest->userId = \Auth::user()->id;
        $addPPICRequest->created_at = date('Y-m-d H:i:s');

        if ($addPPICRequest->save()) {
            return $addPPICRequest->id;
        } else {
            return 0;
        }
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $ppicRequestUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($ppicRequestUpdated);

        return 1;
    }
}
