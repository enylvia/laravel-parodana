<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountGroup;
use App\Models\User;
use App\Models\Company;
use App\Models\TransactionType;
use DB;
use PDF;
use Validator;

class TransactionTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index()
    {
		$transactions = TransactionType::paginate(10);
		$accounts = AccountGroup::all();
		return view('transactiontype.index',compact('transactions','accounts'));
	}
	
	public function create()
    {
		$accounts = AccountGroup::all();
		return view('transactiontype.create',compact('accounts'));
	}
	
	public function store(Request $request)
    {
		// aturan Validasi //
        $validation = Validator::make($request->all(), [			
			'transaction_type' => 'required|string|max:255', 
            'account_number' => 'required|string|max:255',        
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
        
            $data = new TransactionType();
			$data->transaction_type = $request->transaction_type;
            $data->account_number = $request->account_number;
			$data->tipe = $request->tipe;
			$data->description = $request->description;
			//$data->slug = Str::slug($request->account_name);
            $data->save();            				
			
            return redirect('/transaction/type')->with('success', 'Add successfully');
        }
		
	}
	
	public function edit($id)
    {
		$transactions = TransactionType::where('id',$id)->first();
		$accounts = AccountGroup::all();
		return view('transactiontype.edit',compact('accounts'));
	}
	
	public function update(Request $request, $id)
    {
		$data = TransactionType::where('id',$id)->first();
		$data->transaction_type = $request->transaction_type;
		$data->account_number = $request->account_number;
		$data->tipe = $request->tipe;
		$data->description = $request->description;
		//$data->slug = Str::slug($request->account_name);
		$data->save();
		
		return redirect('/transaction/type')->with('success', 'Update successfully');
	}
	
	public function delete($id)
    {
		TransactionType::where('id',$id)->delete();
		return view('transactiontype.delete');
	}
	
}
