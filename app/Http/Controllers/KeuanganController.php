<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangBarangJadiPenjualan;
use App\Models\GudangBarangJadiPenjualanDetail;
use App\Models\GudangBarangJadiPembayaran;

class KeuanganController extends Controller
{
    public function index()
    {
        return view('keuangan.index');
    }

    public function KeluarMasuk()
    {
        return view('keuangan.keluarMasuk.index');
    }
}
