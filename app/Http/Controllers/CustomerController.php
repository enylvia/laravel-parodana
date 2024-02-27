<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerSurvey;
use App\Models\CustomerContract;
use App\Models\CustomerDocument;
use App\Models\CustomerApprove;
use App\Models\CustomerMaritial;
use App\Models\CustomerFamily;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\CardType;
use App\Models\Education;
use App\Models\Maritial;
use App\Models\Religion;
use App\Helper\Terbilang;
use Carbon\Carbon;
use Validator;
use DB;
use DPDF;
use TPDF;
use Notification;
use App\Notifications\SurveyPlan;
use Auth;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index(Request $request)
{        
    $users = User::with('companies')->where('id',auth()->user()->id)->first();
    $companyID = $users->companies[0]->id;

    if ($request->ajax()) {
        $customer = DB::table('customer')
            ->select('*')
            ->where('name','!=',"")
            // ->whereNotNull('mobile_phone')
            // ->whereNotNull('gender')
            // ->where('customer.branch', $companyID)
            // ->orderBy('id', 'asc')
            ->get();

        return Datatables::of($customer)->make(true);
    }

    return view('customer.list.index');
}

	
    public function form()
	{
		$companies = Company::all();
		$cards = CardType::all();
		$educations = Education::all();
		$maritials = Maritial::all();
		$religions = Religion::all();
		return view('customer.form.form',compact('companies','cards','educations','maritials','religions'));
	}
	
	public function form_store(Request $request)
	{
		// aturan Validasi //
        $validation = Validator::make($request->all(), [
			'name' => 'required|string|max:255', 
			'loan_amount' => 'required|string|max:255',
			'loan_to' => 'required',
			'time_period' => 'required|string|max:255',
			'maritial' => 'required',
			'birth_place' => 'required',
			'date_birth' => 'required',
			'card_number' => 'required|unique:customer,card_number',
		]);
		
		
		$reg = "REG";
		$date = date("Y-m-d");
		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$hari = substr($date, 8, 2);
			
        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			$customer = new Customer();
			$customer->name = $request->name;
			$customer->reg_number = $reg .$this->reg_number(8) .$bulan .$tahun ;
			$customer->user_id = auth()->user()->id;
			$customer->mobile_phone = $request->mobile_phone;
			//$customer->email = $request->email;
			//$customer->avatar = $request->avatar;
			$customer->address = $request->address;			
			
			$users = User::with('companies')->where('id',auth()->user()->id)->get();
			foreach($users as $user)
			{
				foreach($user->companies as $company)
				{
					$companyID = $company->id;
					$customer->branch = $companyID;
				}
			}
			
			$customer->date_of_birth = $request->date_birth;
			$customer->birth_place = $request->birth_place;
			if ($request->gender === '1')
			{
				$customer->gender = "Lelaki";
			}
			else
			{
				$customer->gender = "Perempuan";
			}
			$customer->mother_maiden_name = $request->mother_maiden_name;
			$customer->slug = Str::slug($request->input('name')) .'-' .date("Y-m-d H:i:s");
			$customer->family_card_number = $request->family_card_number;
			$customer->card_number = $request->card_number;			
			$customer->religion = $request->religion;
			$customer->education = $request->education;
            $customer->zip_code = $request->zip_code;
			$customer->provinsi = $request->provinsi;
			$customer->kabupaten = $request->kabupaten;
			$customer->kecamatan = $request->kecamatan;
			$customer->kelurahan = $request->kelurahan;	

			$customer->company_name = $request->company_name;
			$customer->department = $request->department;
			$customer->part = $request->part;
			$customer->kpk_number = $request->kpk_number;
			$customer->personalia_name = $request->personalia_name;
			if (!empty($request->input('net_salary'))){
				$customer->net_salary = str_replace('.', '', $request->net_salary);
			} else {
				$customer->net_salary = 0;
			}
			if (!empty($request->input('gross_salary'))){
				$customer->gross_salary = str_replace('.', '', $request->gross_salary);
			} else {
				$customer->gross_salary = 0;
			}
			$customer->payday_date = $request->payday_date;
			$customer->bank_name = $request->bank_name;
			$customer->bank_pin = $request->bank_pin;
			
			$customer->maritial = $request->maritial;			
			$customer->husband_wife = $request->husband_wife;
			$customer->alias_husband_wife = $request->alias_husband_wife;
			$customer->husband_wife_profession = $request->husband_wife_profession;
			//$customer->husband_wife_income = str_replace('.', '', $request->husband_wife_income);
			if (!empty($request->input('husband_wife_income'))){
				$customer->husband_wife_income = str_replace('.', '', $request->husband_wife_income);
			} else {
				$customer->husband_wife_income = 0;
			}
			$customer->husband_wife_phone = $request->husband_wife_phone;
			$customer->husband_wife_address = $request->husband_wife_address;
			$customer->husband_wife_home_status = $request->husband_wife_home_status;
			
			$customer->family_father = $request->family_father;
			$customer->family_mother = $request->family_mother;
			$customer->family_address = $request->family_address;
			$customer->in_law_father = $request->in_law_father;
			$customer->in_law_mother = $request->in_law_mother;
			$customer->in_law_phone = $request->in_law_phone;
			$customer->in_law_address = $request->in_law_address;
			$customer->connection_name = $request->connection_name;
			$customer->connection_alias_name = $request->connection_alias_name;
			$customer->connection_phone = $request->connection_phone;
			$customer->connection_address = $request->connection_address;
			$customer->family_connection = $request->family_connection;
			
			if (!empty($request->input('loan_amount'))){
				$customer->loan_amount = str_replace('.', '', $request->loan_amount);
			} else {
				$customer->loan_amount = 0;
			}
			$customer->loan_to = $request->loan_to;
			$customer->time_period = $request->time_period;	
			if (!empty($request->input('installments_month'))){
				$customer->installments_month = str_replace('.', '', $request->installments_month);
			} else {
				$customer->installments_month = 0;
			}
			$customer->necessity_for = $request->necessity_for;
			$customer->survey_plan = $request->survey_plan;
			$customer->surveyor_name = $request->surveyor_name;
			$customer->reason = $request->reason; 
			$customer->bank_number = $request->bank_number;
			$customer->bank_pin = $request->bank_pin;
			
			if ($request->hasFile('avatar')) {
				$dir = 'uploads/photo/';
				$fileName = strtolower($request->file('avatar')->getClientOriginalName());
				$request->file('avatar')->move($dir, $fileName);
				$customer->avatar = $fileName;
			}
			
			$customer->status = 'survey plan';
			$customer->created_by = auth()->user()->name;
            $customer->save();

			$users = User::whereHas('roles', function ($q) {
				$q->whereIn('name', ['superadmin','owner', 'manager']);
			})->get();
			Notification::send($users, new SurveyPlan($customer));
			return redirect('/customer/list')->with('success', 'Loan Form Add successfully');
		}
	}
	
	public function company_store(Request $request)
	{
		// aturan Validasi //
        $validation = Validator::make($request->all(), [
            'net_salary' => 'required|string|max:255',         
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			$customer = new CustomerCompany();	
			$customer->customer_id = $request->customer_id;
			//$customer->maritial = $request->maritial;
			$customer->company_name = $request->company_name;
			$customer->department = $request->department;
			$customer->part = $request->part;
			$customer->kpk_number = $request->kpk_number;
			$customer->personalia_name = $request->personalia_name;
			$customer->net_salary = $request->net_salary;
			$customer->gross_salary = $request->gross_salary;
			$customer->payday_date = $request->payday_date;
			$customer->bank_name = $request->bank_name;
			$customer->bank_pin = $request->bank_pin;
			
            $customer->save();
			return back()->withInput(['tab'=>'maritial_status','customer_id' => $customer->id]);
		}
	}
	
	public function maritial_store(Request $request)
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
			$customer = new CustomerMaritial();	
			$customer->customer_id = $request->customer_id;
			$customer->maritial = $request->maritial;			
			$customer->husband_wife = $request->husband_wife;
			$customer->alias_husband_wife = $request->alias_husband_wife;
			$customer->husband_wife_profession = $request->husband_wife_profession;
			$customer->husband_wife_income = $request->husband_wife_income;
			$customer->husband_wife_phone = $request->husband_wife_phone;
			$customer->husband_wife_provinsi = $request->husband_wife_provinsi;
			$customer->husband_wife_kabupaten = $request->husband_wife_kabupaten;
			$customer->husband_wife_kecamatan = $request->husband_wife_kecamatan;
			$customer->husband_wife_kelurahan = $request->husband_wife_kelurahan;
			$customer->husband_wife_address = $request->husband_wife_address;
			$customer->husband_wife_home_status = $request->husband_wife_home_status;			
            $customer->save();
			return back()->withInput(['tab'=>'family_data','customer_id' => $customer->id]);
		}
	}
	
	public function family_store(Request $request)
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
			$customer = new CustomerFamily();	
			$customer->customer_id = $request->customer_id;
			$customer->family_father = $request->family_father;
			$customer->family_mother = $request->family_mother;
			$customer->family_provinsi = $request->family_provinsi;
			$customer->family_kabupaten = $request->family_kabupaten;
			$customer->family_kecamatan = $request->family_kecamatan;
			$customer->family_kelurahan = $request->family_kelurahan;
			$customer->family_address = $request->family_address;
			$customer->in_law_father = $request->in_law_father;
			$customer->in_law_mother = $request->in_law_mother;
			$customer->in_law_phone = $request->in_law_phone;
			$customer->in_law_provinsi = $request->in_law_provinsi;
			$customer->in_law_kabupaten = $request->in_law_kabupaten;
			$customer->in_law_kecamatan = $request->in_law_kecamatan;
			$customer->in_law_kelurahan = $request->in_law_kelurahan;
			$customer->in_law_address = $request->in_law_address;
			$customer->connection_name = $request->connection_name;
			$customer->connection_alias_name = $request->connection_alias_name;
			$customer->connection_phone = $request->connection_phone;
			$customer->connection_provinsi = $request->connection_provinsi;
			$customer->connection_kabupaten = $request->connection_kabupaten;
			$customer->connection_kecamatan = $request->connection_kecamatan;
			$customer->connection_kelurahan = $request->connection_kelurahan;
			$customer->connection_address = $request->connection_address;
			$customer->family_connection = $request->family_connection;
            $customer->save();
			return back()->withInput(['tab'=>'submission','customer_id' => $customer->id]);
		}
	}
	
	public function edit($id)
	{
		$customers = Customer::where('id',$id)->get();
		$educations = Education::all();
		$religions = Religion::all();
		$maritials = Maritial::all();
		return view('customer.list.edit',compact('customers','educations','religions','maritials'));
	}
	
	public function update(Request $request, $id)
	{
		$customer = Customer::where('id',$id)->first();
		$customer->name = $request->name;
		$customer->mobile_phone = $request->mobile_phone;
		$customer->address = $request->address;			
		
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
				$customer->branch = $companyID;
			}
		}
		
		$customer->date_of_birth = $request->date_birth;
		$customer->birth_place = $request->birth_place;
		if ($request->gender === '1')
		{
			$customer->gender = "Lelaki";
		}
		else
		{
			$customer->gender = "Perempuan";
		}
		$customer->mother_maiden_name = $request->mother_maiden_name;
		$customer->slug = Str::slug($request->input('name')) .'-' .date("Y-m-d H:i:s");
		$customer->family_card_number = $request->family_card_number;
		$customer->card_number = $request->card_number;			
		$customer->religion = $request->religion;
		$customer->education = $request->education;
		$customer->zip_code = $request->zip_code;
		$customer->provinsi = $request->provinsi;
		$customer->kabupaten = $request->kabupaten;
		$customer->kecamatan = $request->kecamatan;
		$customer->kelurahan = $request->kelurahan;	
		$customer->company_name = $request->company_name;
		$customer->department = $request->department;
		$customer->part = $request->part;
		$customer->kpk_number = $request->kpk_number;
		$customer->personalia_name = $request->personalia_name;
		if (!empty($request->input('net_salary'))){
			$customer->net_salary = str_replace('.', '', $request->net_salary);
		} else {
			$customer->net_salary = 0;
		}
		if (!empty($request->input('gross_salary'))){
			$customer->gross_salary = str_replace('.', '', $request->gross_salary);
		} else {
			$customer->gross_salary = 0;
		}
		$customer->payday_date = $request->payday_date;
		$customer->bank_name = $request->bank_name;
		//$customer->bank_pin = $request->bank_pin;		
		$customer->maritial = $request->maritial;			
		$customer->husband_wife = $request->husband_wife;
		$customer->alias_husband_wife = $request->alias_husband_wife;
		$customer->husband_wife_profession = $request->husband_wife_profession;
		if (!empty($request->input('husband_wife_income'))){
			$customer->husband_wife_income = str_replace('.', '', $request->husband_wife_income);
		} else {
			$customer->husband_wife_income = 0;
		}
		$customer->husband_wife_phone = $request->husband_wife_phone;
		$customer->husband_wife_address = $request->husband_wife_address;
		$customer->husband_wife_home_status = $request->husband_wife_home_status;			
		$customer->family_father = $request->family_father;
		$customer->family_mother = $request->family_mother;
		$customer->family_address = $request->family_address;
		$customer->in_law_father = $request->in_law_father;
		$customer->in_law_mother = $request->in_law_mother;
		$customer->in_law_phone = $request->in_law_phone;
		$customer->in_law_address = $request->in_law_address;
		$customer->connection_name = $request->connection_name;
		$customer->connection_alias_name = $request->connection_alias_name;
		$customer->connection_phone = $request->connection_phone;
		$customer->connection_address = $request->connection_address;
		$customer->family_connection = $request->family_connection;
		if (!empty($request->input('loan_amount'))){
			$customer->loan_amount = str_replace('.', '', $request->loan_amount);
		} else {
			$customer->loan_amount = 0;
		}
		$customer->loan_to = $request->loan_to;
		$customer->time_period = $request->time_period;	
		if (!empty($request->input('installments_month'))){
			$customer->installments_month = str_replace('.', '', $request->installments_month);
		} else {
			$customer->installments_month = 0;
		}
		$customer->necessity_for = $request->necessity_for;
		$customer->survey_plan = $request->survey_plan;
		$customer->surveyor_name = $request->surveyor_name;
		$customer->reason = $request->reason;
		
		if ($request->hasFile('avatar')) {               
			$dir = 'uploads/photo/';                
			if ($customer->avatar != '' && File::exists($dir . $customer->avatar))
				File::delete($dir . $customer->avatar);
			$fileName = strtolower($request->file('avatar')->getClientOriginalName());
			$request->file('avatar')->move($dir, $fileName);               
			$customer->avatar = $fileName;                
		} elseif ($request->remove == 1 && File::exists('uploads/photo/' . $customer->avatar)) {
			File::delete('uploads/photo/' . $customer->avatar);
			$customer->avatar = null;
		}
		
		//$customer->status = 'survey plan';
		$customer->created_by = auth()->user()->name;
		$customer->save();
		
		return redirect()->back()->with('success',"Update Successfuly");
	}
	
	public function delete($id)
    {		
		$customer = Customer::where('id',$id)->first();		
		$survey = CustomerSurvey::where('customer_id',$id)->first();
		if(empty($survey)) return redirect()->back();		
		$contract = CustomerContract::where('customer_id',$id)->first();
		if(empty($contract)) return redirect()->back();		
		$document = CustomerDocument::where('customer_id',$id)->first();
		if(empty($document)) return redirect()->back();
				
		$survey->delete();
		$document->delete();
		$contract->delete();
		$customer->delete();
		
		$dir_photo = 'uploads/photo/';
		$dir_document = 'uploads/documents/';                
			if ($customer->avatar != '' && File::exists($dir_photo . $customer->avatar))
				File::delete($dir_photo . $customer->avatar);
			if ($document->document_file != '' && File::exists($dir_document . $document->document_file))
				File::delete($dir_document . $document->document_file);		
      
	  return redirect()->back()->with("success","Berhasil di hapus !");
	}
	
	public function print($id)
    {		        
		$customers = Customer::where('id',$id)->get();
		$pdf = DPDF::loadView('customer.list.print', compact('customers'))->setPaper('a4', 'potrait')->setOptions([
                      'tempDir' => public_path(),
                      'chroot'  => public_path('/img/logo/'),
                  ]);
		//GENERATE PDF-NYA
		return $pdf->stream();
		//return view('customer.list.simulation',compact('customers'));
	}
	
	public function contract()
    {
		$customers = Customer::where('status','=','approve')->get();
        return view('customer.contract.contract',compact('customers'));
	}
	
	public function survey_plan($id)
	{
		$customers = Customer::where('id',$id)->get();
		return view('customer.survey.plan');
	}
	
	public function simulation($id)
    {		        
		$customers = Customer::where('id',$id)->get();
		$pdf = DPDF::loadView('customer.list.simulation', compact('customers'))->setPaper('a4', 'potrait')->setOptions([
                      'tempDir' => public_path(),
                      'chroot'  => public_path('/img/logo/'),
                  ]);
		//GENERATE PDF-NYA
		return $pdf->stream();
		//return view('customer.list.simulation',compact('customers'));
	}
	
	public function statement($id)
    {		        
		$customers = Customer::where('id',$id)->get();
		$pdf = DPDF::loadView('customer.list.statement', compact('customers'))->setPaper('a4', 'potrait')->setOptions([
                      'tempDir' => public_path(),
                      'chroot'  => public_path('/img/logo/'),
                  ]);
		//GENERATE PDF-NYA
		return $pdf->stream();
		//return view('customer.list.simulation',compact('customers'));
	}
	
	public function search(Request $request)
    {		
		
		if($request->ajax())
		{
			$users = User::with('companies')->where('id',auth()->user()->id)->get();
			foreach($users as $user)
			{
				foreach($user->companies as $company)
				{
					$companyID = $company->id;
				}
			}
			
			$output = '';
			$query = $request->get('query');
			$page = $request->get('page');
			if($query != '')
			{
				$data = DB::table('customer')
				->where('branch',$companyID)
				->where('name', 'like', '%'.$query.'%')				
				->orWhere('reg_number', 'like', '%'.$query.'%')
				->orWhere('family_card_number', 'like', '%'.$query.'%')
				->orWhere('card_number', 'like', '%'.$query.'%')
				->orderBy('id', 'asc')
				->paginate($page);
			}
			else
			{				
				$data = Customer::where('branch',$companyID)
				->where('name', 'like', '%'.$query.'%')								
				->paginate($page);
			}
			$total_row = $data->count();
			if($total_row > 0)
			{
				foreach($data as $key => $row)
				{
					$i = $key +1;	
					$imageName =$row->avatar!='' ?'/uploads/photo/'.$row->avatar:'/uploads/photo/noimage.jpg';
					$output .= "
					<tr>
						<td align='center'> 				
							<img src='$imageName' style='height: 60px; width:60px;'>
						</td>
						<td>$row->name</td>
						<td>$row->reg_number</td>
						<td>$row->address</td>
						<td>$row->mobile_phone</td>
						<td>$row->gender</td>
						<td>$row->card_number</td>
						<td>$row->family_card_number</td>			
						<td><span class='label label-default'>$row->status</span></td>			
						<td>
							<a class='btn btn-sm btn-danger' href='/customer/statement/$row->id' target='_blank'>
								<i class='fa fa-file-pdf-o' title='Pernyataan Penangung Jawab'></i>  
							</a>
						</td>
						<td>
							<a class='btn btn-sm btn-danger' href='/customer/simulation/credit/$row->id' target='_blank'>
								<i class='fa fa-file-pdf-o' title='Simulasi Kredit'></i>  
							</a>
						</td>
						<td>									
							<a class='btn btn-sm btn-danger' href='/customer/contract/print/$row->id' target='_blank'>
								<i class='fa fa-file-pdf-o' title='Surat Perjanjian'></i>  
							</a>
						</td>
						<td style='width:2px;' align='center'>
							<a class='btn btn-sm btn-info' href='/customer/list/edit/$row->id'>
								<i class='fa fa-edit' title='Edit Data'></i>  
							</a>
						</td>
						<td style='width:2px;' align='center'>
							<a class='btn btn-sm btn-default' href='/customer/view/$row->id'>
								<i class='fa fa-eye' title='Lihat Data'></i>  
							</a>
						</td>
						<td style='width:2px;' align='center'>
							<a class='btn btn-sm btn-success' href='/customer/list/print/$row->id' target='_blank'>
								<i class='fa fa-print' title='Cetak Formulir'></i>  
							</a>
						</td>
					</tr>
					";
				}
			}
			else
			{
				$output = '
				<tr>
					<td align="center" colspan="13">No Data Found</td>
				</tr>
		   '	;
			}
			$data = array(
				'table_data'  => $output,
				'total_data'  => $total_row
			);

			echo json_encode($data);
		}
    }
	
	public  function reg_number($length) 
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomBayar = '';
        for ($i = 0; $i < $length; $i++) {
            $randomBayar .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomBayar;
    }
	
}
