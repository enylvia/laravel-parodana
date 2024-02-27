<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\CardType;
use App\Models\Education;
use App\Models\Maritial;
use App\Models\Religion;
use App\Models\CustomerSurvey;
use App\Models\CustomerDocument;
use Carbon\Carbon;
use Validator;
use DB;
use Notification;
//use App\Notifications\SurveyPlan;
use Auth;

class CustomerSurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function survey($reg_number)
	{
		$customers = Customer::where('reg_number',$reg_number)->where('status','=','approve waiting')->get();
		$surveys = Customer::where('status','=','approve waiting')->get();
		$cards = CardType::all();
		$educations = Education::all();
		$maritials = Maritial::all();
		$religions = Religion::all();
		foreach($customers as $customer)
		{
			$getID = $customer->id;
		}
		$documents = CustomerDocument::where('reg_number',$reg_number)->get();
		
		if (auth()->user()->unreadNotifications) 
        {
			foreach (auth()->user()->unreadNotifications as $notification)
			{
				//$notification->markAsRead();
				$Ids = $notification->id;
				//Notification::where('id',$Ids)->update(['read_at' => now()]);
				auth()->user()->unreadNotifications->where('id', $Ids)->markAsRead();
			}
		}
		
		//$id = auth()->user()->unreadNotifications[0]->id;
		//auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
		
		
		return view('customer.survey.plan',compact('customers','surveys','cards','educations','maritials','religions','documents'));
	}
	
	public function index()
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
			}
		}
		
		if(Auth::user()->hasRole('superadmin','pengawas')) {
			$surveys = Customer::where('status','=','survey plan')->get();
		}else{
			$surveys = Customer::where('status','=','survey plan')->where('branch',$companyID)->get();
		}
		$cards = CardType::all();
		$educations = Education::all();
		$maritials = Maritial::all();
		$religions = Religion::all();
		return view('customer.survey.index',compact('surveys','cards','educations','maritials','religions'));
	}
	
	public function create($id)
	{
		$getID = $id;
		$surveys = CustomerSurvey::where('customer_id',$id)->get();
		$customers = Customer::where('id',$id)->get();
		$cards = CardType::all();
		$educations = Education::all();
		$maritials = Maritial::all();
		$religions = Religion::all();
		return view('customer.survey.create',compact('customers','surveys','getID','cards','educations','maritials','religions'));
	}
	
	public function store(Request $request)
	{
		// aturan Validasi //
        $validation = Validator::make($request->all(), [
            'customer_id' => 'required|string|max:255',  
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			$surveys = new CustomerSurvey();
			$surveys->customer_id = $request->customer_id;
			$surveys->reg_number = $request->reg_number;
			$surveys->environment_condition = $request->environment_condition;
			$surveys->viability = $request->viability;
			//$surveys->other_income = str_replace('.', '', $request->other_income);
			if (!empty($request->input('other_income'))){
				$surveys->other_income = str_replace('.', '', $request->other_income);
			} else {
				$surveys->other_income = 0;
			}
			//$surveys->child_fee = str_replace('.', '', $request->child_fee);
			if (!empty($request->input('child_fee'))){
				$surveys->child_fee = str_replace('.', '', $request->child_fee);
			} else {
				$surveys->child_fee = 0;
			}
			//$surveys->electricity_cost = str_replace('.', '', $request->electricity_cost);
			if (!empty($request->input('electricity_cost'))){
				$surveys->electricity_cost = str_replace('.', '', $request->electricity_cost);
			} else {
				$surveys->electricity_cost = 0;
			}
			//$surveys->water_cost = str_replace('.', '', $request->water_cost);
			if (!empty($request->input('water_cost'))){
				$surveys->water_cost = str_replace('.', '', $request->water_cost);
			} else {
				$surveys->water_cost = 0;
			}
			//$surveys->other_installment = str_replace('.', '', $request->other_installment);
			if (!empty($request->input('other_installment'))){
				$surveys->other_installment = str_replace('.', '', $request->other_installment);
			} else {
				$surveys->other_installment = 0;
			}
			if (!empty($request->input('husband_wife_income'))){
				$surveys->husband_wife_income = str_replace('.', '', $request->husband_wife_income);
			} else {
				$surveys->husband_wife_income = 0;
			}
			if (!empty($request->input('cost_of_living'))){
				$surveys->cost_of_living = str_replace('.', '', $request->cost_of_living);
			} else {
				$surveys->cost_of_living = 0;
			}
			$surveys->note = $request->note;
			$surveys->save();
			Customer::where('id', '=', $surveys->customer_id)->update(['status' => 'approve waiting' ]);
            return redirect('/customer/list')->with('success', 'Data updated successfully');
		}
	}
	
	public function plan_update(Request $request, $id)
	{
		
			$surveys = Customer::where('id',$id)->first();
			//$surveys->customer_id = $request->customer_id;
			//$surveys->environment_condition = $request->environment_condition;
			//$surveys->viability = $request->Viability;
			//$surveys->other_income = $request->other_income;
			//$surveys->child_fee = $request->child_fee;
			//$surveys->electricity_cost = $request->electricity_cost;
			//$surveys->water_cost = $request->water_cost;
			$surveys->status = $request->approve;
			$surveys->reason = $request->reason;
			$surveys->save();
			//Customer::where('id', '=', $surveys->customer_id)->update(['status' => 'approve waiting' ]);
			CustomerSurvey::where('customer_id', '=', $id)->update(['note' => $request->note ]);
			
			return redirect('/customer/list');
		
	}
	
	public function approve()
	{
		$customers = Customer::where('status','=','approve waiting')->get();
		//foreach($customers as $customer)
		//{
		//	$surveys = CustomerSurvey::where('id',$customer->id)->first();
		//	$companies = Company::where('id',$customer->branch)->first();
		//}
		
		return view('customer.survey.approve',compact('customers'));
	}
}
