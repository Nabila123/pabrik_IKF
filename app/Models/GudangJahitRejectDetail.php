<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitRejectDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_jahit_reject_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function bajuOpname()
    {
        return $this->hasOne('App\Models\GudangBajuStokOpname','id','gdBajuStokOpnameId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function reject()
    {
        return $this->hasOne('App\Models\GudangJahitReject','id','gdJahitRejectId');
    }

    public static function createGudangJahitRejectDetail($gdJahitRejectId, $gdBajuStokOpnameId, $keterangan)
    {
        $keluarDetail = new GudangJahitRejectDetail;
        $keluarDetail->gdJahitRejectId = $gdJahitRejectId;
        $keluarDetail->gdBajuStokOpnameId = $gdBajuStokOpnameId;
        $keluarDetail->keterangan = $keterangan;
        $keluarDetail->userId = \Auth::user()->id;
        $keluarDetail->created_at = date('Y-m-d H:i:s');

        if($keluarDetail->save()){
            return 1;
        }else{
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
