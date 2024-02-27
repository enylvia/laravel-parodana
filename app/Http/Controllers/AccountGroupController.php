<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\AccountGroup;
use Validator;
use DB;

class AccountGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');;
    }

    public function index()
    {	
		//DB::statement("SET @count = 0;");
        //DB::statement("UPDATE account_group SET `id` = @count:= @count + 1;");
        //DB::statement("ALTER TABLE `account_group` AUTO_INCREMENT = 1;");
		//$items = AccountGroup::with(['children'=>function($q) {
		//	$q->where('visible', 1)->orderBy('order', 'asc')}])->first();
		//$subsCat = AccountGroup::first()->children->pluck('id');
		//dd($subsCat);
		$items = AccountGroup::orderBy('id','asc')->get();
		//$items = AccountGroup::where('id_parent',0)->with('children')->orderBy('id','asc')->paginate(10);
		//dd($items);
        $group = new AccountGroup;
        $group   = $group->getMenuHTML($items);
        $catGroup = AccountGroup::all();
		$cat = AccountGroup::first();
		$accounts = $cat->parent()->paginate(10);
		
        return view('accountgroup.index', compact('items','group','accounts','catGroup'));
    }

    public function create()
    {        
		//$roles = Role::all();
		$catGroup = AccountGroup::all();
		
        return view('accountgroup.create',compact('catGroup'));
    }

    public function store(Request $request)
    {
        // aturan Validasi //
        $validation = Validator::make($request->all(), [
            'account_number' => 'required|string|max:255',
			'account_name' => 'required|string|max:255',         
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
        
            $data = new AccountGroup();            
            $data->id_parent = $request->parents;
			$accounts = AccountGroup::where('id', $request->parents)->first();			
			if ($request->parents > 0 )
			{
				$dash = "-";
				$getNo = $accounts->account_number;
				$getName = $accounts->account_name;
				$data->account_number = $getNo .$dash .$request->account_number;
				$data->group_account = $getName;
			} else {
				$data->account_number = $request->account_number;
			}
			$data->account_name = $request->account_name;
			$data->slug = Str::slug($request->account_name);
            $data->save();            				
			
            return redirect('/account')->with('success', 'Account Add successfully');
        }        
        //DB::statement("UPDATE employee, contents SET employee.type = contents.type where employee.content_id = contents.id");
    }

    public function edit($id)
    {        
		$ids = AccountGroup::where('id', $id)->first();			
		//if ($request->parents > 0 )
		//{
		//	$dash = "-";
		//	$getNo = $accounts->account_number;
		//	$getName = $accounts->account_name;
		//	$accounts->account_number = $getNo .$dash .$request->account_number;
		//	$accounts->group_account = $getName;
		//} else {
		//	$data->account_number = $request->account_number;
		//}
		//$accounts->account_name = $request->account_name;
		//$accounts->slug = Str::slug($request->account_name);
		//$accounts->save();
			
        return view('accountgroup.edit',compact('ids'));
    }

    public function update(Request $request, $id)
    {
        $accounts = AccountGroup::where('id', $id)->first();			
		
		$accounts->account_number = $request->account_number;		
		$accounts->account_name = $request->account_name;
		$accounts->slug = Str::slug($request->account_name);
		$accounts->save();
			
        return redirect('/account')->with('success', 'Edit successfully');
    }

    

    public function delete($id)
    {
      AccountGroup::where('id',$id)->delete();
      return redirect()->back()->with("success","Berhasil di hapus !");
    }
	
	public function myData($userid)
	{
		$data = static::get();
		

		$result = [];
		if(!empty($data)){
			foreach ($data as $key => $value) {
				$result[$value->type.'-'.$value->postid][] = $value;
			}
		}
		

		$paginate = 10;
		$page = Input::get('page', 1);
		

		$offSet = ($page * $paginate) - $paginate;  
		$itemsForCurrentPage = array_slice($result, $offSet, $paginate, true);  
		$result = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($result), $paginate, $page);
		$result = $result->toArray();
		return $result;
	}
}
