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
            $detailPotong = GudangPotongProses::where('gPotongKId', $detail->gdPotongKId)->where('purchaseId', $detail->purchaseId)->get();
            if (count($detailPotong) == 0) {
                if (!in_array($detail->diameter, $data['diameter'])) {
                    $data['diameter'][$i] = $detail->diameter;
                    $i++;
                }
            } else {
                foreach ($detailPotong as $potong) {
                    $detailPotongProses = GudangPotongProsesDetail::where('gdPotongProsesId', $potong->id)->where('diameter', $detail->diameter)->first();
                    if ($detailPotongProses == null) {
                        if (!in_array($detail->diameter, $data['diameter'])) {
                            $data['diameter'][$i] = $detail->diameter;
                            $i++;
                        }
                    }
                }
            }            
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
