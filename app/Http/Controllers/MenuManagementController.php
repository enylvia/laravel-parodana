<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\SideBarMenu;
use App\Models\RoleSideBarMenu;
use App\Models\Role;
use App\Models\User;
//use DB;
use Validator;
use Auth;

class MenuManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
		$user = User::whereHas(
			'roles', function($q){
				$q->where('name', 'superadmin');
			}
		)->get();	
		
		//dapatkan role sesuai user login
		//$getRole = Auth::user()->roles->first()->id
    	//$items = RoleSideBarMenu::all();
		//$items = SideBarMenu::join('roles_sidebar_menu', 'sidebar_menu.id', '=', 'roles_sidebar_menu.sidemenu_id')
		//->orderBy('orders')->paginate(10);
		$items = Role::join('roles_sidebar_menu', 'roles.id', '=', 'roles_sidebar_menu.role_id')
		->join('sidebar_menu','roles_sidebar_menu.sidemenu_id', '=','sidebar_menu.id')->paginate(10);
		//dd($items);        
    	$roles = Role::where('id','<>',1)->get();
		$menus = SideBarMenu::all();
    	return view('menu.index',compact('items','roles','menus'));
    	//return response()
        //    ->view('admin.settings.menu.index', $menu, $parent, 200)
        //    ->header('Content-Type', $type);
    }
	
	public function create()
	{		
		return view('menu.create');
	}
	
    public function store(Request $request)
    {                
		$validation = Validator::make($request->all(), [
            'role' => 'required|string|max:255',
			'sidemenu' => 'required|string|max:255',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        }

        //$menu = new RoleSideBarMenu;
        //$menu->role_id = $request->role;
		//$menu->sidemenu_id = $request->sidemenu;
        //$menu->menu_access = $request->menu_access;
        //$menu->save();
		
		//$menu = RoleSideBarMenu::updateOrCreate(
		//   ['role_id' => $request->role, 'sidemenu_id' => $request->sidemenu, 'menu_access' => $request->menu_access ]
		//);
		$entry = RoleSideBarMenu::where( [ 'role_id' => $request->role, 'sidemenu_id' => $request->sidemenu ] )->first();
	    if ( ! $entry ) {
	        RoleSideBarMenu::create( [
				'role_id' => $request->role, 
				'sidemenu_id' => $request->sidemenu, 
				'menu_access' => $request->menu_access
			]);
		} else {
			RoleSideBarMenu::where('sidemenu_id', $request->sidemenu)->where('role_id', $request->role)->update(['menu_access' => $request->menu_access]);
		}
		
        return redirect()->back();
    }

    public function update($id)
    {         	    	
        $menu = Menu::findOrFail($id);
        $menu->name = Input::get('name');
        //$menu->slug = str_slug(Input::get('name'));
        $menu->id_parent = Input::get('parents');
        $menu->icon = Input::get('icon');
        $menu->url = Input::get('url');
        //$menu->active = Input::get('active');
        $menu->active = Input::get('active') ? 1 : 0;
        $menu->update();

        return redirect('/menu/management');
    }

    public function edit($id)
    {
        //$this->AdminAuthCheck();
        $parent=DB::table('sidebar_menu')
        ->where('id', $id)
        ->first();
        return view('menu.edit',compact('parents'));
    }

    public function delete(Request $request)
    {
    	RoleSideBarMenu::where('sidemenu_id',$request->menu)->where('role_id',$request->role)->delete();
    	return redirect('menu/management')->with('success', 'Role Menu Delete successfully');
    }
	
	public function save(Request $request)
    {   
    	$menu = new SideBarMenu;           
        $menu->changeParentById($menu->parseJsonArray(json_decode($request->getData, true)));        
        //return response()->json(array('result' => 'success'));             
        return response()->json(['result' => 'success']);
    }

    public function togglePublish($id)
    {
    	$menu = new SideBarMenu;
        $menu = SideBarMenu::find($id);
        $menu->status = ($menu->status) ? false : true;
        $menu->save();

        return response()->json(array('result' => 'success', 'changed' => ($menu->status) ? 1 : 0));
    }
	
	public function accessMenu($id)
    {    	
		//$menu = new RoleSideBarMenu;
		//$menu = RoleSideBarMenu::where('role_id',$id)->get();
        //$menu = SideBarMenu::join('roles_sidebar_menu', 'sidebar_menu.id', '=', 'roles_sidebar_menu.sidemenu_id')
		//->where('roles_sidebar_menu.role_id',$id)->get();
		$menu = Role::join('roles_sidebar_menu', 'roles.id', '=', 'roles_sidebar_menu.role_id')
		->join('sidebar_menu','roles_sidebar_menu.sidemenu_id', '=','sidebar_menu.id')
		->where('roles_sidebar_menu.role_id',$id)->get();
        return $menu;
		//$menu->menu_access = ($menu->menu_access) ? false : true;
		//RoleSideBarMenu::where('sidemenu_id', $id)->update(['menu_access' => $menu->menu_access]);
        //$menu->save();
		
		//return response()->json(array('result' => 'success', 'changed' => ($menu->menu_access) ? 1 : 0));
    }
}
