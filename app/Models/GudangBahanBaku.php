<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'gd_bahanbaku';

    public function bahanBakuDetail()
    {
        return $this->hasOne('App\Models\GudangBahanBakuDetail','gudangId','id');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public static function updateFieldGudang($fieldName, $updatedField, $id)
    {
        $gudangFieldUpdated[$fieldName] = $updatedField;
        $success = self::where('id', $id)->update($gudangFieldUpdated);

        if ($success) {
            return 1;
        }
    }

    public static function CheckBahanBakuData($request)
    {
        // dd($request);

        for ($i=1; $i <= $request->totalData; $i++) { 
            $bahanBaku = Self::where('id', $request["gudangId_".$i])->where('purchaseId', $request["purchaseId_".$i])->first();
            // dd($bahanBaku);
            if ($bahanBaku != null) {
                $bahanBakuDetail = GudangBahanBakuDetail::where('gudangId', $bahanBaku->id)->where('purchaseId', $bahanBaku->purchaseId)->where('materialId', $request->materialId)->first();
                if ($bahanBakuDetail != null) {
                    for ($j=0; $j < count($request["gramasi_".$i]); $j++) { 
                        // dd($request["gramasi_".$i][$j]);
                        $bahanBakuDetail = GudangBahanBakuDetailMaterial::CreateDetailMaterial($bahanBakuDetail->id, $request["diameter_".$i][$j], $request["gramasi_".$i][$j], 0, $request["berat_".$i][$j], 0, "Kg", 0, null, null);
                    }
                }else{
                    $bahanBakuDetail = GudangBahanBakuDetail::CreateBahanBakuDetail($bahanBaku->id, $request["purchaseId_".$i], $request->materialId, 0);
                    if ($bahanBakuDetail) {
                        for ($j=0; $j < count($request["gramasi_".$i]); $j++) { 
                            $bahanBakuDetail = GudangBahanBakuDetailMaterial::CreateDetailMaterial($bahanBakuDetail->id, $request["diameter_".$i][$j], $request["gramasi_".$i][$j], 0, $request["berat_".$i][$j], 0, "Kg", 0, null, null);
                        }
                    }
                }
            }
        } 

        return 1;
    }
}
