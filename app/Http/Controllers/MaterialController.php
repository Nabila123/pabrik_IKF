<?php

namespace App\Http\Controllers;
use App\Models\MaterialModel;
use App\Models\JenisBarang;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Gmail;
use Carbon\Carbon;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $materials = MaterialModel::all();
       dd($now = Carbon::today());

        return view('material.index')->with(['materials'=>$materials]);
    }

    public function create()
    {
        $jeniss = JenisBarang::all();
        return view('material.create')->with(['jeniss'=>$jeniss]);
    }

    public function store(Request $request)
    {

        $material = new MaterialModel;
        $material->jenisId    = $request['jenisId'];
        $material->nama       = $request['nama'];
        $material->jenis      = $request['jenis'];
        $material->satuan     = $request['satuan'];
        $material->keterangan = $request['keterangan'];
        $material->userId     = \Auth::user()->id;

        $material->save();
      
        return redirect('material');
    }

    public function detail($id)
    {
        $material = MaterialModel::find($id);
 
        return view('material.detail')->with(['material'=>$material]);
    }

    public function edit($id)
    {
        $material = MaterialModel::find($id);
        $jeniss = JenisBarang::all();

        return view('material.update')->with(['material'=>$material, 'jeniss'=>$jeniss]);
    }

    public function update($id, Request $request)
    {
        $material = MaterialModel::find($id);
        if($material){
            $data['jenisId'] = $request['jenisId'];
            $data['nama'] = $request['nama'];
            $data['satuan'] = $request['satuan'];
            $data['jenis'] = $request['jenis'];
            $data['keterangan'] = $request['keterangan'];
            $data['userId'] = \Auth::user()->id;

            $updateMaterial = MaterialModel::where('id',$id)->update($data);
        }
        return redirect('material');
    }

    public function delete(Request $request)
    {
        $find = MaterialModel::find($request['id']);
        if ($find) {
            MaterialModel::where('id', $request['id'])->delete();
        }
                
        return redirect('material');
    }

    public function getSatuan(Request $request)
    {
        $materialId = $request['materialId'];
        $materials = MaterialModel::getSatuanMaterial($materialId);

        return json_encode($materials);
    }
}
