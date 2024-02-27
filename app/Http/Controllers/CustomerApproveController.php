<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Company;
use App\Models\Provinsi;
use App\Models\Education;
use App\Models\Religion;
use App\Models\Employee; 
use App\Models\Customer;
use App\Models\CustomerCompany;
//use App\Models\CustomerIncome;
use App\Models\CustomerMaritial;
//use App\Models\CustomerConnection;
use App\Models\CustomerFamily;
use App\Models\CustomerDocument;
use App\Models\CustomerSubmission;
use App\Models\CustomerApprove;
use Carbon\Carbon;
use Validator;
use DB;
use Auth;

class CustomerApproveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index()
	{
		//$roles = User::whereHas(
		//	'roles', function($q){
		//		$q->where('name', 'superadmin');
		//	}
		//)->get();
		
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
			}
		}
		if(Auth::user()->hasRole('superadmin','pengawas')) 
		{
			$customers = Customer::where('status','approve waiting')->get();
		}else{
			$customers = Customer::where('status','approve waiting')->where('branch',$companyID)->get();
		}
		
		return view('customer.approve.index',compact('customers'));
	}
	
	public function store(Request $request)
	{
		// aturan Validasi //
        $validation = Validator::make($request->all(), [
            'customer_id' => 'required|string|max:255',   
			'approve' => 'required|string|max:255',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			$customer = new CustomerApprove();
			$customer->customer_id = $request->customer_id;
			$customer->reg_number = $request->reg_number;
			$customer->approve_amount = str_replace('.', '', $request->approve_amount);
			$customer->approve_by = auth()->user()->name;
			$customer->interest_rate = $request->interest_rate;
			$customer->time_period = $request->time_period;	
			$customer->installment =	str_replace('.', '', $request->installment);
			$customer->approve = $request->approve;
			$customer->reason = $request->reason;
			$customer->m_savings= str_replace('.', '', $request->m_saving);
            $customer->save();
			Customer::where('id', '=', $customer->customer_id)->update(['status' => 'approve', 'approve' => 1 ]);
            return redirect('/customer/list')->with('success', 'Data updated successfully');
		}
	}
	
	public function view($id)
	{
		$companies = Company::all();
		$provinsis = Provinsi::all();
		$educations = Education::all();
		$religions = Religion::all();
		$customers = Customer::where('id',$id)->get();
		//$Crcompanies = CustomerCompany::where('customer_id',$id)->first();
		//$incomes = CustomerIncome::where('customer_id',$id)->first();
		$maritials = CustomerMaritial::where('customer_id',$id)->first();
		//$connections = CustomerConnection::where('customer_id',$id)->first();
		$submissions = CustomerSubmission::where('customer_id',$id)->first();
		$familis = CustomerFamily::where('customer_id',$id)->first();		
		$documents = CustomerDocument::where('customer_id',$id)->get();
		$approves = CustomerApprove::where('customer_id',$id)->first();
		return view('customer.approve.view',compact('companies','educations','religions','customers','maritials','familis','documents','submissions','approves','provinsis'));
	}
}
