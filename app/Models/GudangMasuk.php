<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangMasuk extends Model
{
    use HasFactory;

    protected $table = 'tr_gudang_masuk';

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
    }

    public static function updateStatusDiterima($id, $gudangRequest)
    {
        $purchaseUpdated['statusDiterima'] = 1;

        self::where('gudangRequest', $gudangRequest)->where('id', $id)->update($purchaseUpdated);

        return 1;
    }

    public static function createBarangKembali($request)
    {
        $AddPurchase = new GudangMasuk;
        $AddPurchase->gudangStokId = $request->gStokId;
        $AddPurchase->gudangKeluarId = $request->id;
        $AddPurchase->purchaseId = $request->purchaseId;
        $AddPurchase->materialId = $request->materialId;
        $AddPurchase->jenisId = $request->jenisId;
        $AddPurchase->gudangRequest = $request->gudangRequest;
        $AddPurchase->tanggal = $request->tanggal;
        $AddPurchase->statusDiterima = 0;
        $AddPurchase->userId = \Auth::user()->id;
        $AddPurchase->created_at = date('Y-m-d H:i:s');

        if ($AddPurchase->save()) {
            return 1;
        } else {
            return 0;
        }
    }
}
