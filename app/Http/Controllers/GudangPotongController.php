<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GudangPotongController extends Controller
{
    public function index()
    {
        return view('gudangPotong.index');
    }

    public function gProses()
    {
        return view('gudangPotong.proses.index');
    }
}
