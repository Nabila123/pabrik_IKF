<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangPotongProses extends Model
{
    use HasFactory;

    protected $table = 'gd_potongproses';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
    }

    public function prosesPotongDetail()
    {
        return $this->hasOne('App\Models\GudangPotongProsesDetail','gdPotongProsesId','id');
    }

    public static function createPotongProses($gdPotongKId, $pegawaiId, $purchaseId, $materialId, $jenisId, $qty, $tanggal, $userId){
        $addPotongProses = new GudangPotongProses;
        $addPotongProses->gPotongKId = $gdPotongKId;
        $addPotongProses->pegawaiId = $pegawaiId;
        $addPotongProses->purchaseId = $purchaseId;
        $addPotongProses->materialId = $materialId;
        $addPotongProses->jenisId = $jenisId;
        $addPotongProses->qty = $qty;
        $addPotongProses->tanggal = $tanggal;
        $addPotongProses->userId = $userId;
        $addPotongProses->created_at = date('Y-m-d H:i:s');

        if ($addPotongProses->save()) {
            return $addPotongProses->id;
        } else {
            return 0;
        }
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $InspeksiUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($InspeksiUpdated);

        return 1;
    }
}
