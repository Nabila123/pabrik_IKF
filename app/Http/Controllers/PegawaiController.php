<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use DB;


class PegawaiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $pegawais = pegawai::select('*', DB::raw('count(*) as jumlah'))->orderBy('kodeBagian', 'asc')->groupBy('kodeBagian')->get();
        return view('pegawai.index', ['pegawais' => $pegawais]);
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        $pegawai = new Pegawai;
        $pegawai->nip              = $request['nip'];
        $pegawai->nama             = $request['nama'];
        $pegawai->kodeBagian       = $request['kodeBagian'];
        $pegawai->jenisKelamin     = $request['jenisKelamin'];
        $pegawai->userId           = \Auth::user()->id;

        $pegawai->save();
      
        return redirect('pegawai');
    }

    public function edit($id)
    {
        $pegawai = Pegawai::find($id);

        return view('pegawai.update', ['pegawai' => $pegawai]);
    }

    public function update($id, Request $request)
    {
        $pegawai = Pegawai::find($id);
        if($pegawai){
            $data['nip'] = $request['nip'];
            $data['nama'] = $request['nama'];
            $data['kodeBagian'] = $request['kodeBagian'];
            $data['jenisKelamin'] = $request['jenisKelamin'];
            $data['userId'] = \Auth::user()->id;

            $updatePegawai = Pegawai::where('id',$id)->update($data);
        }
        return redirect('pegawai');
    }

    public function detail($kodeBagian)
    {
        $pegawais = pegawai::where('kodeBagian', $kodeBagian)->orderBy('nama', 'asc')->get();
        return view('pegawai.detail', ['pegawais' => $pegawais]);

    }

    public function delete($kodeBagian, $id)
    {
        $find = pegawai::find($id);
        if ($find) {
            pegawai::where('id', $id)->delete();
        }
                
        return redirect('pegawai/detail/'.$kodeBagian);
    }
}
