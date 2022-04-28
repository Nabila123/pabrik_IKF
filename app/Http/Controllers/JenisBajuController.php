<?php

namespace App\Http\Controllers;
use App\Models\JenisBaju;
use App\Models\JenisBarang;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class JenisBajuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $jenisBajus = JenisBaju::orderBy('type', 'asc')->get();
        return view('jenisBaju.index', ['jenisBajus'=>$jenisBajus]);
    }

    public function create()
    {
        $jeniss = JenisBarang::all();
        return view('jenisBaju.create')->with(['jeniss'=>$jeniss]);
    }

    public function store(Request $request)
    {

        $material = new JenisBaju;
        $material->jenis      = $request['jenis'];
        $material->ukuran     = $request['ukuran'];
        $material->type     = $request['type'];
        $material->userId     = \Auth::user()->id;

        $material->save();
      
        return redirect('jenisBaju');
    }

    public function detail($id)
    {
        $jenisBaju = JenisBaju::find($id);
 
        return view('jenisBaju.detail')->with(['jenisBaju'=>$jenisBaju]);
    }

    public function edit($id)
    {
        $jenisBaju = JenisBaju::find($id);

        return view('jenisBaju.update')->with(['jenisBaju'=>$jenisBaju]);
    }

    public function update($id, Request $request)
    {
        $jenisBaju = JenisBaju::find($id);
        if($jenisBaju){
            $data['jenis'] = $request['jenis'];
            $data['ukuran'] = $request['ukuran'];
            $data['type'] = $request['type'];
            $data['userId'] = \Auth::user()->id;

            $updateJenisBaju = JenisBaju::where('id',$id)->update($data);
        }
        return redirect('jenisBaju');
    }

    public function delete(Request $request)
    {
        $find = JenisBaju::find($request['id']);
        if ($find) {
            JenisBaju::where('id', $request['id'])->delete();
        }
                
        return redirect('jenisBaju');
    }
}
