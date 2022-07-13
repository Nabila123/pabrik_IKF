<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\GudangBarangJadiPenjualan;
use App\Models\GudangBarangJadiPenjualanDetail;
use App\Models\GudangBarangJadiPembayaran;
use App\Models\AdminPurchase;
use App\Models\adminPurchaseInvoice;
use App\Models\GudangBahanBaku;


class KeuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('keuangan.index');
    }

    public function getPenjualan(Request $request)
    {
        $data = [];
        $Penjualan = GudangBarangJadiPenjualan::where('kodeTransaksi', $request->kodeTransaksi)->first();
        $PenjualanDetail = GudangBarangJadiPenjualanDetail::where('barangJadiPenjualanId', $Penjualan->id)->groupBy('jenisBaju', 'ukuranBaju', 'qty', 'harga')->get();
        $Pembayaran = GudangBarangJadiPembayaran::where('kodeTransaksi', $request->kodeTransaksi)->get();
        $data['totalBayar'] = 0;
        if ($Pembayaran != []) {
            foreach ($Pembayaran as $bayar) {
                $data['totalBayar'] = $data['totalBayar'] + $bayar->totalHarga;
            }
        }

        $data['totalBayar'] = rupiah($data['totalBayar']);
        
        $data['customer'] = $Penjualan->customer;
        $data['total'] = $Penjualan->totalHarga;
        foreach ($PenjualanDetail as  $detail) {
            $data['detail'][] = $detail;
        }

        return json_encode($data);
    }

    public function pesanan()
    {
        $invoice = adminPurchaseInvoice::all();
        $poOrder = AdminPurchase::where('jenisPurchase', 'Purchase Order')->get();        
        foreach ($poOrder as $order) {
            $cekDatang = GudangBahanBaku::select('id', 'created_at')->where('purchaseId', $order->id)->first();

            if ($cekDatang != null) {
                $order['barangDatang'] = true;
                $order['barangDatangAt'] = $cekDatang->created_at;
            }
        }

        return view('keuangan.pesanan.index', ['poOrder' => $poOrder, 'invoice' => $invoice]);
    }

    public function penjualan()
    {
        $Penjualan = GudangBarangJadiPenjualan::groupBy('kodeTransaksi')->get();
        foreach ($Penjualan as $jual) {
            $Pembayaran = GudangBarangJadiPembayaran::where('kodeTransaksi', $jual->kodeTransaksi)->get();
            $tempBayar = 0;
            if (count($Pembayaran) != 0) {
                foreach ($Pembayaran as $bayar) {
                    $tempBayar += (int)$bayar->totalHarga;
                }
            }
            $jual->totalBayar = $tempBayar;
        }
        return view('keuangan.penjualan.index', ['pembayaran' => $Penjualan]);
    }

    public function penjualanCreate()
    {
        $kodeTransaksi = GudangBarangJadiPenjualan::groupBy('kodeTransaksi')->get();

        return view('keuangan.penjualan.create', ['kodeTransaksi' => $kodeTransaksi]);
    }

    public function penjualanStore(Request $request)
    {
        $Pembayaran = GudangBarangJadiPembayaran::CreateBarangJadiPembayaran($request['kodeTransaksi'], $request['customer'], date('Y-m-d'), $request['pembayaran']);

        if ($Pembayaran == 1) {
            return redirect('Keuangan/penjualan');
        }
    }

    public function penjualanDetail($kodeTransaksi)
    {
        $Pembayaran = GudangBarangJadiPembayaran::where('kodeTransaksi', $kodeTransaksi)->get();
        $Penjualan = GudangBarangJadiPenjualan::where('kodeTransaksi', $kodeTransaksi)->first();
        $PenjualanDetail = GudangBarangJadiPenjualanDetail::where('barangJadiPenjualanId', $Penjualan->id)->groupBy('jenisBaju', 'ukuranBaju', 'qty', 'harga')->get();

        return view('keuangan.penjualan.detail', ['pembayaran' => $Pembayaran, 'penjualan' => $Penjualan, 'penjualanDetail' => $PenjualanDetail]);
    }

    public function penjualanDelete(Request $request)
    {
        $Pembayaran = GudangBarangJadiPembayaran::where('id', $request->pembayaranId)->delete();
        if ($Pembayaran) {
            return redirect('Keuangan/penjualan/detail/'.$request->kodeTransaksi);
        }else{
            return redirect('Keuangan/penjualan/detail/'.$request->kodeTransaksi);
        }
    }
}
