<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use DB;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $users = User::all();
        return view('user.index', ['users' => $users]);
    }

    public function create()
    {
        $roles = Role::all();
        return view('user.create', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $user = new User;
        $user->nip            = $request['nip'];
        $user->nama           = $request['nama'];
        $user->passwordAsli   = $request['password'];
        $user->password       =  Hash::make($request['password']);
        $user->roleId         = $request['roleId'];

        $user->save();
      
        return redirect('user');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();

        return view('user.update', ['user' => $user, 'roles' => $roles]);
    }

    public function update($id, Request $request)
    {
        $user = User::find($id);
        if($user){
            $data['nip']          = $request['nip'];
            $data['nama']         = $request['nama'];
            $data['password']     = Hash::make($request['password']);
            $data['passwordAsli'] = $request['password'];
            $data['roleId']       = $request['roleId'];

            $updateuser = User::where('id',$id)->update($data);
        }
        return redirect('user');
    }

    public function detail($id)
    {
        $user = User::find($id);

        return view('user.detail', ['user' => $user]);
    }

    public function delete(Request $request)
    {
        $find = User::find($request['id']);
        if ($find) {
            User::where('id', $request['id'])->delete();
        }
                
        return redirect('user');
    }
}
