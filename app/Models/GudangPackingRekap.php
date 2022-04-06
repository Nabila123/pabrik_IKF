<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class GudangPackingRekap extends Model
{
    use HasFactory;
    protected $table = 'gd_packingrekap';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function pegawai()
    {
        return $this->hasOne('App\Models\Pegawai','id','pegawaiId');
    }

    public static function kodePacking() {
        $packingRekap =  Self::select('kodePacking')->get(); 
        $kode = rand(100000000000, 999999999999);
        
        if(strlen($kode) == 12){
            foreach ($packingRekap as $value) {
                if ($value->kodePacking == $kode) {
                    Self::kodePacking();
                }            
            }
        }else{
            Self::kodePacking();
        }

        return $kode;
    }

    public static function PackingRekapCreate($kodePacking, $userId)
    {
        $addPackingRekap = New GudangPackingRekap;
        $addPackingRekap->kodePacking = $kodePacking;
        $addPackingRekap->tanggal = date('Y-m-d');
        $addPackingRekap->userId = $userId;
        $addPackingRekap->created_at = date('Y-m-d H:i:s');

        if ($addPackingRekap->save()) {
            return $addPackingRekap->id;
        } else {
            return 0;
        }
    }

}
