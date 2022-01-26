<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangKeluar extends Model
{
    use HasFactory;

    protected $table = 'tr_gudang_keluar';

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

    public static function updateStatusDiterima($id, $gudangRequest)
    {
        $purchaseUpdated['statusDiterima'] = 1;

        self::where('gudangRequest', $gudangRequest)->where('id', $id)->update($purchaseUpdated);

        return 1;
    }
}
