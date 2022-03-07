<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GudangJahitController extends Controller
{
    public function index()
    {
        return view('gudangJahit.index');
    }

    public function gRequest()
    {
        return view('gudangJahit.request.index');
    }

    public function gOperator()
    {
        return view('gudangJahit.operator.index');
    }

    public function gReject()
    {
        return view('gudangJahit.reject.index');
    }
}
