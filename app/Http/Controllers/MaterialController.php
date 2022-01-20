<?php

namespace App\Http\Controllers;
use App\Models\MaterialModel;

use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getSatuan(Request $request)
    {
        $materialId = $request['materialId'];
        $materials = MaterialModel::getSatuanMaterial($materialId);

        return json_encode($materials);
    }
}
