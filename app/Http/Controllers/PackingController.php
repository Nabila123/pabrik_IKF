<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PackingController extends Controller
{
    public function index()
    {
        return view('packing.index');
    }

    public function gOperator()
    {
        return view('packing.operator.index');
    }

    public function gReject()
    {
        return view('packing.reject.index');
    }
}
