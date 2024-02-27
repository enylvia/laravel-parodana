<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Validator;
use App\Models\permission;
use App\Models\User;
use App\Models\Role;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
		//$user = $request->user();
		//dd($user->hasRole('admin'));
        $permissions = Permission::paginate(10);
        $roles = Role::all();
        return view('permission.index', compact('permissions','roles'));
    }

    public function create()
    {   $roles = Role::all();
		//if ($request->user()->can('permission.create')) {
        
		return view('permission.create',compact('roles'));
		//}
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
        
            $data = new Permission();            
            $data->name = $request->name;
            //$data->slug = str_slug($request->input('name'));
			$data->slug = Str::slug($request->input('name'));
            $data->save();
            //$user->sendVerificationEmail();            
            //$this->notify(new UserRegistrationNotification($data));

            // Attach permissions
            $data->roles()->attach($request['roles']);
            //return view('front.user.profile');
            return redirect('/permission')->with('message', 'permission Add successfully');
        }        
        
    }

    public function edit($id)
    {
        $permissions = Permission::where('id',$id)->get();
        //$permissions = permission::all();
		$roles = Role::all();
        return view('permission.edit',compact('permissions','roles'));
    }

    public function update(Request $request, $id)
    {
        $data = Permission::where('id',$id)->first();
        $data->name = $request->name;
        $data->slug = str_slug($request->input('name'));
        $data->save();
		$data->roles()->detach($request['roles']);
		$data->roles()->attach($request['roles']);
		//$data->users()->attach($request[Auth()->user()->id]);
        return redirect('/permission')->with('message', 'permission Add successfully');
    }

    

    public function delete($id)
    {
      Permission::where('id',$id)->delete();
      return redirect()->back()->with("success","Berhasil di hapus !");
    }

    public function print($id)
    {
        $permission = Permission::where('id',$id)->get();
 
        $pdf = PDF::loadview('/permission/permission_pdf',['permission'=>$permission]);
        return $pdf->download('permission-pdf');
    }
}
