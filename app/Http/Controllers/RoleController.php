<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\Role;
use App\Models\User;
use Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');;
    }

    public function index()
    {
		$getRole = User::whereHas(
			'roles', function($q){
				$q->where('name', 'superadmin');
			}
		)->get();
        $roles = Role::where('name', '<>', 'superadmin')->paginate(10);
        //$users = User::paginate(10);
        return view('role.index', compact('roles'));
    }

    public function create()
    {        
        return view('role.create');
    }

    public function store(Request $request)
    {
        // aturan Validasi //
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',         
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
        
            $data = new Role();            
            $data->name = $request->name;
            //$data->slug = str_slug($request->input('name'));
			$data->slug = Str::slug($request->input('name'));
			$data->description = $request->description;
            $data->save();
            //$user->sendVerificationEmail();            
            //$this->notify(new UserRegistrationNotification($data));

            // Attach roles
            //$data->roles()->attach($request['roles']);
            //return view('front.user.profile');
            return redirect('/role')->with('message', 'Role Add successfully');
        }        
        
    }

    public function edit($id)
    {
        $roles = Role::where('id',$id)->get();
        //$roles = Role::all();
        return view('role.edit',compact('roles'));
    }

    public function update(Request $request, $id)
    {
        $data =Role::where('id',$id)->first();
        $data->name = $request->name;
        //$data->slug = str_slug($request->input('name'));
		$data->slug = Str::slug($request->input('name'));
		$data->description = $request->description;
        $data->save();
		//$data->roles()->attach($request['roles']);
        return redirect('/role')->with('message', 'Role Add successfully');
    }

    

    public function delete($id)
    {
      Role::where('id',$id)->delete();
      return redirect()->back()->with("success","Berhasil di hapus !");
    }

    public function print($id)
    {
        $role = Role::where('id',$id)->get();
 
        $pdf = PDF::loadview('/role/role_pdf',['role'=>$role]);
        return $pdf->download('role-pdf');
    }
}
