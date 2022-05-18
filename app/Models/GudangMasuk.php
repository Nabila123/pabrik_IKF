<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangMasuk extends Model
{
    use HasFactory;

    protected $table = 'tr_gudang_masuk';

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

    public function gudangKeluar()
    {
        return $this->hasOne('App\Models\GudangKeluar','id','gudangKeluarId');
    }

    public static function updateStatusDiterima($id, $gudangRequest)
    {
        $purchaseUpdated['statusDiterima'] = 1;

        self::where('gudangRequest', $gudangRequest)->where('id', $id)->update($purchaseUpdated);

        return 1;
    }    

    public static function createBarangKembali($request)
    {
        $AddGudangMasuk = new GudangMasuk;
        $AddGudangMasuk->gudangKeluarId = $request->id;
        $AddGudangMasuk->materialId = $request->material;
        $AddGudangMasuk->jenisId = $request->jenisId;
        $AddGudangMasuk->gudangRequest = $request->gudangRequest;
        $AddGudangMasuk->tanggal = date('Y-m-d H:i:s');
        $AddGudangMasuk->statusDiterima = 0;
        $AddGudangMasuk->userId = \Auth::user()->id;
        $AddGudangMasuk->created_at = date('Y-m-d H:i:s');

        if ($AddGudangMasuk->save()) {
            return $AddGudangMasuk->id;
        } else {
            return 0;
        }

    }

    
}
