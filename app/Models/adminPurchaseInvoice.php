<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class adminPurchaseInvoice extends Model
{
    use HasFactory;

    protected $table = 'purchase_invoice';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function bahanBaku()
    {
        return $this->hasOne('App\Models\GudangBahanBaku','id','gudangId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public static function createInvoicePurchase($request)
    {
       $AddInvoice = new adminPurchaseInvoice;
        $AddInvoice->gudangId = $request['barangDatangId'];
        $AddInvoice->purchaseId = $request['purchaseId'];
        $AddInvoice->total = $request['total'];
        $AddInvoice->paymentDue = $request['paymentDue'];
        $AddInvoice->userId = \Auth::user()->id;
        $AddInvoice->created_at = date('Y-m-d H:i:s');

        if ($AddInvoice->save()) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function updateInvoicePurchase($request)
    {
        $invoiceUpdate['gudangId'] = $request['barangDatangId'];
        $invoiceUpdate['purchaseId'] = $request['purchaseId'];
        $invoiceUpdate['total'] = $request['total'];
        $invoiceUpdate['paymentDue'] = $request['paymentDue'];
        $invoiceUpdate['userId'] = \Auth::user()->id;
        $invoiceUpdate['updated_at'] = date('Y-m-d H:i:s');

        self::where('id', $request['id'])->update($invoiceUpdate);

        return 1;
    }
}
