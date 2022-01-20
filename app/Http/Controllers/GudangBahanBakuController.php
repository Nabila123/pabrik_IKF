<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;

class GudangBahanBakuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('bahan_baku.index');
    }

    public function create()
    {
        $purchases = Purchase::all();
        return view('bahan_baku.create')->with(['purchases'=>$purchases]);
    }

    public function store(Request $request)
    {
        
    }
}
