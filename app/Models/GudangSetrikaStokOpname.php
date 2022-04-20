<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangSetrikaStokOpname extends Model
{
    use HasFactory;
    protected $table = 'gd_setrika_stokopname';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public static function kodeBarcode() {
        $packingGenerate =  Self::select('kodeBarcode')->get(); 
        $kode = rand(100000000000, 999999999999);
        
        if(strlen($kode) == 12){
            foreach ($packingGenerate as $value) {
                if ($value->kodeBarcode == $kode) {
                    Self::kodeBarcode();
                }            
            }
        }else{
            Self::kodeBarcode();
        }

        return $kode;
    }

    public static function SetrikaStokOpnameCreate($gdBajuStokOpnameId, $tanggal, $purchaseId, $jenisBaju, $ukuranBaju, $statusSetrika, $userId)
    {
        $AddSetrikaStokOpname = new GudangSetrikaStokOpname;
        $AddSetrikaStokOpname->gdBajuStokOpnameId = $gdBajuStokOpnameId;
        $AddSetrikaStokOpname->tanggal = $tanggal;
        $AddSetrikaStokOpname->purchaseId = $purchaseId;
        $AddSetrikaStokOpname->jenisBaju = $jenisBaju;
        $AddSetrikaStokOpname->ukuranBaju = $ukuranBaju;
        $AddSetrikaStokOpname->statusSetrika = $statusSetrika;

        $AddSetrikaStokOpname->userId = $userId;
        $AddSetrikaStokOpname->created_at = date('Y-m-d H:i:s');

        $AddSetrikaStokOpname->save();        
        
        return 1;
    }

    public static function bajuUpdateField($fieldName, $updatedField, $id)
    {
        $bajuFieldUpdated[$fieldName] = $updatedField;
        $success = self::where('id', $id)->update($bajuFieldUpdated);

        if ($success) {
            return 1;
        }
    }
}
