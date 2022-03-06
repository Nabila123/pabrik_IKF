<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangPotongKeluar extends Model
{
    use HasFactory;
    protected $table = 'gd_potongkeluar';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function getPurchaseWithMaterial($request)
    {
        $dataId = explode("-",$request->purchaseId);
        $dataPurchase = AdminPurchase::select('suplierName')->where('id', $dataId[0])->first();
        $dataMaterial = MaterialModel::select('nama')->where('id', $request->materialId)->first();
        $detailMaterial = GudangPotongKeluarDetail::where('gdPotongKId', $dataId[1])->where('purchaseId', $request->purchaseId)->where('materialId', $request->materialId)->get();
    
        $data = [
            'suplierName' => $dataPurchase['suplierName'],
            'nama' => $dataMaterial['nama'],
            'diameter' => []
        ];

        $i = 0;
        foreach ($detailMaterial as $detail) {
           if (!in_array($detail->diameter, $data['diameter'])) {
            $data['diameter'][$i] = $detail->diameter;
           }
            $i++;
        }      
        
        return $data;
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $InspeksiUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($InspeksiUpdated);

        return 1;
    }
}
