<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminPoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('adminPO.index');
    }

    public function poRequest()
    {
        return view('adminPO.poRequest');
    }

    public function poRequestDetail()
    {
        return view('adminPO.poRequestDetail');
    }

    public function poOrder()
    {
        return view('adminPO.poOrder');
    }

    public function poOrderDetail()
    {
        return view('adminPO.poOrderDetail');
    }

    public function laporanAdminPO()
    {
        return view('adminPO.laporanAdminPO');
    }
}
