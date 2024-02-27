<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tax;
use DB;
use Validator;

class TaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index()
	{
		DB::statement("SET @count = 0;");
        DB::statement("UPDATE taxes SET `id` = @count:= @count + 1;");
        DB::statement("ALTER TABLE `taxes` AUTO_INCREMENT = 1;");
		$taxes = Tax::all();
		
		return view('tax.index',compact('taxes'));
	}
	
	public function edit($id)
	{
		return view('tax.create');
	}
	
	public function store(Request $request)
	{
		// aturan Validasi //
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255', 
			'tax' => 'required|string|max:255',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			$taxes = new Tax();
			$taxes->name = $request->name;
			$taxes->tax = $request->tax;
			$taxes->save();
		}
		
		return redirect('/setting/tax')->with('success', 'Add successfully');
	}
	
	public function update(Request $request, $id)
	{
		$taxes = Tax::where('id',$id)->first();
		$taxes->name = $request->name;
		$taxes->tax = $request->tax;
		$taxes->save();
		return redirect()->back()->with('success', 'Update successfully');
	}
	
	public function delete($id)
	{
		Tax::where('id',$id)->delete();
		
		return redirect()->back()->with('success', 'Delete successfully');
	}
}
