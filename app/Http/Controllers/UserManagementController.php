<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\SideBarMenu;
use App\Models\RoleSideBarMenu;
use App\Models\User;
use App\Models\Role;
use DB;
use Validator;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function user_role()
	{		
		$users = User::where('id','<>',1)->where('id','<>',2)->get();
		$roles = Role::where('id','<>',1)->get();
		//$items = DB::table('users_roles')->select('users_roles.*')->get();
		
		return view('management.userrole.userrole',compact('users','roles'));
	}

	public function store(Request $request)
	{
		DB::table('users_roles')->insert([
			'user_id' => $request->user,
			'role_id' => $request->role
		]);
		
		return redirect()->back()->with('success', 'User Role Add successfully');
	}
	
	public function update(Request $request)
	{
		//DB::table('users_roles')
        //      ->where('user_id', 1)->where('role_id',1)
        //      ->update(['user_id' => 1, 'role_id' => 1 ]);
		//
		//return redirect()->back()->with('success', 'User Role Update successfully');
		//$where = array('id' => $request->user);
        //$user  = User::where($where)->first();
		//$user->roles()->sync($request['role']);
		//$user->roles()->sync([$request->input('role')]);
		
        //return response()->json($user);
		//return redirect()->back()->with('success', 'User Role Update successfully');
		
		if ($request->ajax()) 
		{
			$id = $request->user;
			$user  = User::where('id',$id)->first();
			$user->roles()->sync($request->role);
			
			return Response::json($id);
		}
	}
	
	public function delete(Request $request)
	{
		//DB::table('users_roles')->where('user_id', $request->user)->where('role_id', $request->role)->delete();
		//return redirect()->back()->with('success', 'User Role Delete successfully');
		
		$where = array('id' => $request->user);
        $user  = User::where($where)->first();
		$user->roles()->detach($request['role']);
		//$user->roles()->detach($request->user);
		
        //return response()->json($user);
		return redirect()->back()->with('success', 'User Role Delete successfully');
	}
	
}
