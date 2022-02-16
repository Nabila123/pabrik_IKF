<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDF;

class AdminPurchase extends Model
{
    use HasFactory;

    protected $table = 'purchase';
    protected $fillable = ['jenisPurchase'];

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function bahanBaku()
    {
        return $this->hasOne('App\Models\GudangBahanBaku','kodePurchase','kode');
    }

    public function roleKaDeptProdUser()
    {
        return $this->hasOne('App\Models\User','roleId','isKaDeptProd');
    }

    public function roleKaDeptPOUser()
    {
        return $this->hasOne('App\Models\User','roleId','isKaDeptPO');
    }

    public function roleKaDivPOUser()
    {
        return $this->hasOne('App\Models\User','roleId','isKaDivPO');
    }

    public function roleKaDivFinUser()
    {
        return $this->hasOne('App\Models\User','roleId','isKaDivFin');
    }

    public static function findOne($id) {
        return self::where(array(
          'id'	=> $id,
        ))->first();
    }

    public static function getPurchaseWithMaterial($request)
    {
        $dataPurchase = AdminPurchase::select('suplierName')->where('id', $request->purchaseId)->first();
        $dataMaterial = MaterialModel::select('nama')->where('id', $request->materialId)->first();
        
        $data = [
            'suplierName' => $dataPurchase['suplierName'],
            'nama' => $dataMaterial['nama'],
        ];
        
        return $data;
    }

    public static function getDataInvoice($purchaseId)
    {
        $data = [];
        $i = 0;
        $purchase = Self::where('id', $purchaseId)->first();
        $gudang = GudangBahanBaku::where('purchaseId', $purchase->id)->first();

        $data["purchase"] = [
            'gudangId' => $gudang->id,
            'suplierName' => $purchase->suplierName,
            'catatan' => $purchase->note
        ];

        $gudangDetail = GudangBahanBakuDetail::where('gudangId', $gudang->id)->get();
       foreach ($gudangDetail as $detail) {
            $gudangMaterial = GudangBahanBakuDetailMaterial::where('gudangDetailId', $detail->id)->get();
            foreach ($gudangMaterial as $material) {
                $data["material"][$i++] = [
                    'nama'      => $detail->material->nama,
                    'diameter'  => $material->diameter,
                    'gramasi'   => $material->gramasi,
                    'brutto'    => $material->brutto,
                    'netto'     => $material->netto,
                    'tarra'     => $material->tarra,
                    'satuan'    => $material->unit,
                    'harga'     => $material->unitPrice
                ];
            }
       }
        
       return $data;
    }

    public static function purchaseKode()
    {
        $panjang = 5;
        $Huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $Angka = "1234567890";
        $kodeHuruf = '';
        $kodeAngka = '';
        $kode = '';

        for ($i = 0; $i < $panjang; $i++) {
            $kodeHuruf .= $Huruf[rand(0, strlen($Huruf) - 1)];
            $kodeAngka .= $Angka[rand(0, strlen($Angka) - 1)];
        }

        $kode = $kodeHuruf . "" . $kodeAngka;
        return $kode;
    }

    public static function purchaseCreate($kode, $jenisPurchase, $suplierName, $tanggal, $waktu, $waktuPayment, $note, $total, $userId)
    {
        $AddPurchase = new AdminPurchase;
        $AddPurchase->kode = $kode;
        $AddPurchase->jenisPurchase = $jenisPurchase;
        $AddPurchase->suplierName = $suplierName;
        $AddPurchase->tanggal = $tanggal;
        $AddPurchase->waktu = $waktu;
        $AddPurchase->waktuPayment = $waktuPayment;
        $AddPurchase->note = $note;
        $AddPurchase->total = $total;

        $AddPurchase->userId = $userId;
        $AddPurchase->created_at = date('Y-m-d H:i:s');

        if ($AddPurchase->save()) {
            return $AddPurchase->id;
        } else {
            return 0;
        }
        
    }

    public static function purchaseUpdate($id, $kode, $jenisPurchase, $tanggal, $waktu, $waktuPayment, $note, $total, $userId)
    {
        $purchaseUpdated['kode'] = $kode;
        $purchaseUpdated['jenisPurchase'] = $jenisPurchase;
        $purchaseUpdated['tanggal'] = $tanggal;
        $purchaseUpdated['waktu'] = $waktu;
        $purchaseUpdated['waktuPayment'] = $waktuPayment;
        $purchaseUpdated['note'] = $note;
        $purchaseUpdated['total'] = $total;

        $purchaseUpdated['userId'] = $userId;
        $purchaseUpdated['updated_at'] = date('Y-m-d H:i:s');

        self::where('id', $id)->update($purchaseUpdated);

        return 1;
        
    }

    public static function purchaseUpdateField($fieldName, $updatedField, $id)
    {
        $purchaseFieldUpdated[$fieldName] = $updatedField;
        $success = self::where('id', $id)->update($purchaseFieldUpdated);

        if ($success) {
            return 1;
        }
    }
}
