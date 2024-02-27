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
//use DB;
use Validator;

class MenuListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
    	//$items = SideBarMenu::orderBy('orders')->get();
		$items = SideBarMenu::join('roles_sidebar_menu', 'sidebar_menu.id', '=', 'roles_sidebar_menu.sidemenu_id')
		->orderBy('orders')->get();
		//dd($items);
        $menu = new SideBarMenu;
        $menu   = $menu->getMenuHTML($items);
    	$roles = Role::all();
    	//$items = $this->menu->orderBy('order', 'asc')->get();
        //$menus = $this->menu->getMenuHTML($items);
    	$categories = SideBarMenu::all(); 
    	return view('menu.list.index',compact('menu','items','categories','roles'));
    	//return response()
        //    ->view('admin.settings.menu.index', $menu, $parent, 200)
        //    ->header('Content-Type', $type);
    }	

    public function store(Request $request)
    {                
		$validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        }

        $menu = new SideBarMenu;
        $menu->title = $request->name;
		$menu->display = $request->display;
        $menu->slug = Str::slug($request->input('name'));
        $menu->id_parent = $request->parents;
        $menu->icon = $request->icon;
        $menu->url = $request->url;
		if ($request->status === '1')
		{
		    $menu->status = 1;
		}
		else
		{
		    $menu->status = 0;
		}

        $menu->orders = $menu->getMaxOrder() + 1;
        $menu->save();

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {         	    	
        $menu = SideBarMenu::findOrFail($id);
        $menu->title = $request->name;
		$menu->display = $request->display;
        $menu->slug = Str::slug($request->input('name'));
        $menu->id_parent = $request->parents;
        $menu->icon = $request->icon;
        $menu->url = $request->url;
        $menu->status = $request->status ? 1 : 0;
        $menu->update();

        return redirect('/menu/list')->with('success', 'Update Successfuly');
    }

    public function edit($id)
    {
        //$this->AdminAuthCheck();
        $parent=DB::table('sidebar_menu')
        ->where('id', $id)
        ->first();
        return view('menu.edit',compact('parents'));
    }

    public function delete($id)
    {
    	SideBarMenu::find($id)->delete();
    	return redirect('menu/management');
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
	
	public function accessMenu($id,$role)
    {    	
		//$menu = new RoleSideBarMenu;
		//$menu = RoleSideBarMenu::where('sidemenu_id',$id)->first();
        //$menu = SideBarMenu::join('roles_sidebar_menu', 'sidebar_menu.id', '=', 'roles_sidebar_menu.sidemenu_id')
		//->where('roles_sidebar_menu.sidemenu_id',$id)->first();
        
		///$menu->menu_access = ($menu->menu_access) ? false : true;
		//RoleSideBarMenu::where('sidemenu_id', $id)->update(['menu_access' => $menu->menu_access]);
        //$menu->save();
		$entry = RoleSideBarMenu::where( [ 'role_id' => $role, 'sidemenu_id' => $id ] )->first();
	    $entry->menu_access = ($menu->menu_access) ? false : true;
		if ( ! $entry ) {
	        RoleSideBarMenu::create( [
				'role_id' => role, 
				'sidemenu_id' => $id, 
				'menu_access' => ($menu->menu_access) ? false : true
			]);
		} else {
			RoleSideBarMenu::where('sidemenu_id', $id)->where('role_id', $role)->update(['menu_access' => $menu->menu_access]);
		}
		
		return response()->json(array('result' => 'success', 'changed' => ($menu->menu_access) ? 1 : 0));
    }
}
