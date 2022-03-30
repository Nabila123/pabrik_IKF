<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangSetrikaMasuk extends Model
{
    use HasFactory;
    protected $table = 'gd_setrikamasuk';


    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function createSetrikaMasuk()
    {
       $addSetrikaMasuk = New GudangSetrikaMasuk;
       $addSetrikaMasuk->tanggal = date('Y-m-d');
       $addSetrikaMasuk->userId = \Auth::user()->id;
       $addSetrikaMasuk->created_at = date('Y-m-d H:i:s');

        if($addSetrikaMasuk->save()){
            return $addSetrikaMasuk->id;
        }else{
            return 0;
        }
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $dataUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($dataUpdated);

        return 1;
    }
}
