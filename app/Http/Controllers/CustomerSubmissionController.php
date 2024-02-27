<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\CustomerSubmission;
use Carbon\Carbon;
use Validator;
use DB;

class CustomerSubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function store(Request $request)
	{
		// aturan Validasi //
        $validation = Validator::make($request->all(), [
            'customer_id' => 'required|string|max:255',   
			'loan_amount' => 'required|string|max:255',
			'loan_to' => 'required',
			'time_period' => 'required|string|max:255',
			'installments_month' => 'required',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			$customer = new CustomerSubmission();
			$customer->customer_id = $request->customer_id;
			$customer->loan_amount = str_replace('.', '', $request->loan_amount);
			$customer->loan_to = $request->loan_to;
			$customer->time_period = $request->time_period;	
			$customer->installments_month =	str_replace('.', '', $request->installments_month);
			$customer->necessity_for = $request->necessity_for;
			$customer->survey_plan = $request->survey_plan;
			$customer->surveyor_name = $request->surveyor_name;
			$customer->reason = $request->reason;
            $customer->save();
			Customer::where('id', '=', $customer->customer_id)->update(['status' => 'submission' ]);
			//return redirect()->back()->with('success', 'Submission Add successfully');
			//return redirect()->back()->withInput(['tab'=>'company_data'])->with('success', 'Load Form Add successfully');
			//return redirect()->route('pickupfinish', ['id' => $customer->id]);
			return back()->withInput(['tab'=>'submission'])->with('success', 'Submission Add successfully');
		}
	}
}
