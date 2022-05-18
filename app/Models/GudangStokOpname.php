<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangStokOpname extends Model
{
    use HasFactory;

    protected $table = 'tr_gudang_stok_opname';

    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public static function CreateStokOpname($purchaseId, $materialId, $jenisId, $qty, $userId)
    {
        $addStokOpname = new GudangStokOpname;
        $addStokOpname->purchaseId = $purchaseId;
        $addStokOpname->materialId = $materialId;
        $addStokOpname->jenisId = $jenisId;
        $addStokOpname->qty = $qty;
        $addStokOpname->userId = $userId;
        $addStokOpname->created_at = date('Y-m-d H:i:s');

        if ($addStokOpname->save()) {
            return $addStokOpname->id;
        } else {
            return 0;
        }
    }

    public static function CheckStokOpnameData($request)
    {
        $stokOpnameId = [];
        for ($i=0; $i < count($request->purchaseId); $i++) { 
            $stokOpname = Self::where('purchaseId', $request->purchaseId[$i])->where('materialId', $request->material)->first();
            if ($stokOpname == null) {
                $createStok = Self::CreateStokOpname($request->purchaseId[$i], $request->material, $request->jenisId, 0, \Auth::user()->id);
                if ($createStok != 0) {
                    $stokOpnameId[] = $createStok;
                }
            }else {
                $stokOpnameId[] = $stokOpname->id;
            }
        } 

        return $stokOpnameId;
    }
}
