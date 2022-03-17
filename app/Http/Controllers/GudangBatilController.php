<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GudangBatilController extends Controller
{
    public function index()
    {
        return view('gudangBatil.index');
    }

    public function gRequest()
    {
        return view('gudangBatil.request.index');
    }

    public function gOperator()
    {
        return view('gudangBatil.operator.index');
    }

    public function gReject()
    {
        return view('gudangBatil.reject.index');
    }
}
