<?php

namespace App\Http\Controllers;
use App\Models\GudangCuci;


use Illuminate\Http\Request;

class GudangCuciController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('gudangCuci.index');
    }
    
    public function gudangCuciRequest()
    {
        $gCuciRequest = GudangCuci::where('gudangRequest', 'Gudang Cuci')->get();

        return view('gudangCuci.request.index', ['gCuciRequest' => $gCuciRequest]);
    }

    public function gudangCuciKembali()
    {
        return view('gudangCuci.kembali.index');
    }
}
