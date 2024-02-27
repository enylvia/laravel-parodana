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
use Carbon\Carbon;
use Validator;
use DB;

class LoanController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
	
    public function form()
	{
		//DB::statement("SET @count = 0;");
        //DB::statement("UPDATE customer SET `id` = @count:= @count + 1;");
        //DB::statement("ALTER TABLE `customer` AUTO_INCREMENT = 1;");
		
		//DB::statement("UPDATE customer_company SET `id` = @count:= @count + 1;");
        //DB::statement("ALTER TABLE `customer_company` AUTO_INCREMENT = 1;");
		
		//DB::statement("UPDATE customer_family SET `id` = @count:= @count + 1;");
        //DB::statement("ALTER TABLE `customer_family` AUTO_INCREMENT = 1;");
		
		//DB::statement("UPDATE customer_maritial SET `id` = @count:= @count + 1;");
        //DB::statement("ALTER TABLE `customer_maritial` AUTO_INCREMENT = 1;");
		
		//DB::statement("UPDATE customer_connection SET `id` = @count:= @count + 1;");
        //DB::statement("ALTER TABLE `customer_connection` AUTO_INCREMENT = 1;");
		
		//DB::statement("UPDATE customer_document SET `id` = @count:= @count + 1;");
        //DB::statement("ALTER TABLE `customer_document` AUTO_INCREMENT = 1;");
		$companies = Company::all();
		$cards = CardType::all();
		$educations = Education::all();
		$maritials = Maritial::all();
		$religions = Religion::all();
		return view('loan.form',compact('companies','cards','educations','maritials','religions'));
	}
	
	public function form_store(Request $request)
	{
		// aturan Validasi //
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',         
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			$customer = new Customer();
			$customer->name = $request->name;
			$customer->mobile_phone = $request->mobile_phone;
			$customer->email = $request->email;
			//$customer->avatar = $request->avatar;
			$customer->address = $request->address;			
			$customer->branch = $request->branch;
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
			$customer->slug = Str::slug($request->input('name'));
			//$customer->card_type = $request->card_type;
			$customer->family_card_number = $request->family_card_number;
			$customer->card_number = $request->card_number;			
			$customer->religion = $request->religion;
			$customer->education = $request->education;
			$customer->nationality = $request->nationality;
            $customer->zip_code = $request->zip_code;
			$customer->provinsi = $request->provinsi;
			$customer->kabupaten = $request->kabupaten;
			$customer->kecamatan = $request->kecamatan;
			$customer->kelurahan = $request->kelurahan;
			//$customer->maritial = $request->maritial;
			//$customer->company_name = $request->company_name;
			//$customer->department = $request->department;
			//$customer->part = $request->part;
			//$customer->kpk_number = $request->kpk_number;
			//$customer->personalia_name = $request->personalia_name;
			//$customer->net_salary = $request->net_salary;
			//$customer->gross_salary = $request->gross_salary;
			//$customer->payday_date = $request->payday_date;
			//$customer->bank_name = $request->bank_name;
			//$customer->bank_pin = $request->bank_pin;
			//$customer->husband_wife = $request->husband_wife;
			//$customer->alias_husband_wife = $request->alias_husband_wife;
			//$customer->husband_wife_profession = $request->husband_wife_profession;
			//$customer->husband_wife_income = $request->husband_wife_income;
			//$customer->husband_wife_phone = $request->husband_wife_phone;
			//$customer->husband_wife_provinsi = $request->husband_wife_provinsi;
			//$customer->husband_wife_kabupaten = $request->husband_wife_kabupaten;
			//$customer->husband_wife_kecamatan = $request->husband_wife_kecamatan;
			//$customer->husband_wife_kelurahan = $request->husband_wife_kelurahan;
			//$customer->husband_wife_address = $request->husband_wife_address;
			//$customer->husband_wife_home_status = $request->husband_wife_home_status;
			//$customer->family_father = $request->family_father;
			//$customer->family_mother = $request->family_mother;
			//$customer->family_provinsi = $request->family_provinsi;
			//$customer->family_kabupaten = $request->family_kabupaten;
			//$customer->family_kecamatan = $request->family_kecamatan;
			//$customer->family_kelurahan = $request->family_kelurahan;
			//$customer->family_address = $request->family_address;
			//$customer->in_law_father = $request->in_law_father;
			//$customer->in_law_mother = $request->in_law_mother;
			//$customer->in_law_phone = $request->in_law_phone;
			//$customer->in_law_provinsi = $request->in_law_provinsi;
			//$customer->in_law_kabupaten = $request->in_law_kabupaten;
			//$customer->in_law_kecamatan = $request->in_law_kecamatan;
			//$customer->in_law_kelurahan = $request->in_law_kelurahan;
			//$customer->in_law_address = $request->in_law_address;
			//$customer->connection_name = $request->connection_name;
			//$customer->connection_alias_name = $request->connection_alias_name;
			//$customer->connection_phone = $request->connection_phone;
			//$customer->connection_provinsi = $request->connection_provinsi;
			//$customer->connection_kabupaten = $request->connection_kabupaten;
			//$customer->connection_kecamatan = $request->connection_kecamatan;
			//$customer->connection_kelurahan = $request->connection_kelurahan;
			//$customer->connection_address = $request->connection_address;
			//$customer->family_connection = $request->family_connection;
			$customer->created_by = auth()->user()->name;
            $customer->save();
			//DB::table('customer_company')->insert(['customer_id' => $customer->id]);
			//DB::table('customer_family')->insert(['customer_id' => $customer->id]);
			//DB::table('customer_maritial')->insert(['customer_id' => $customer->id]);
			//DB::table('customer_connection')->insert(['customer_id' => $customer->id]);
			//DB::table('customer_document')->insert(['customer_id' => $customer->id]);
			//return redirect('/loan/form')->with('success', 'Load Form Add successfully');
			//return redirect()->back()->withInput(['tab'=>'company_data'])->with('success', 'Load Form Add successfully');
			//return redirect()->route('pickupfinish', ['id' => $customer->id]);
			return back()->withInput(['tab'=>'company_data','customer_id' => $customer->id]);
		}
	}
}
