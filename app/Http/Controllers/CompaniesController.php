<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Validator;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use DB;

class CompaniesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');;
    }

    public function index()
    {
        $companies = Company::paginate(10);
		DB::statement("SET @count = 0;");
        DB::statement("UPDATE companies SET `id` = @count:= @count + 1;");
        DB::statement("ALTER TABLE `companies` AUTO_INCREMENT = 1;");
        $roles = Role::all();
        return view('company.index', compact('companies','roles'));
    }

    public function create()
    {        
		$roles = Role::all();
		$permissions = Permission::all();
        return view('company.create',compact('roles','permissions'));
    }

    public function store(Request $request)
    {
        // aturan Validasi //
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
			'address' => 'required|string|max:255',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			
			$q = \DB::table('companies')
			  ->select(\DB::raw('max(RIGHT(company_id, 3)) as kd_max'))
			  ->get();
			$kd = "";
			if($q->count() > 0)
			{
				foreach($q as $k){
					$tmp = $k->kd_max+1;
					$kd = sprintf("%03s", $tmp);
				}
			}else{
				$kd = "001";
			}
			
			$kop = "P";
			
            $data = new Company();
			$data->company_id = $kop .$kd;
            $data->name = $request->name;
			$data->address = $request->address;
			if ($request->branch === '1')
			{
				$data->branch = "Pusat";
			}
			else
			{
				$data->branch = "Cabang";
			}
			//$data->branch = $request->branch;
			//$data->slug = Str::slug($request->input('name'));
			$data->siup = $request->siup;
            $data->zip_code = $request->zip_code;
			$data->provinsi = $request->provinsi;
			$data->kabupaten = $request->kabupaten;
			$data->kecamatan = $request->kecamatan;
			$data->kelurahan = $request->kelurahan;
			$data->created_by = auth()->user()->name;
            $data->save();
            //$user->sendVerificationEmail();            
            //$this->notify(new UserRegistrationNotification($data));

            // Attach employee
            //$data->companies()->attach($request['companies']);
			
            return redirect('/company')->with('success', 'Company Add successfully');
        }        
        
    }

    public function edit($id)
    {
        $companies = Company::where('id',$id)->get();
        $roles = Role::all();
		$permissions = Permission::all();
        return view('company.edit',compact('companies','roles','permissions'));
    }

    public function update(Request $request, $id)
    {
        $data = Company::where('id',$id)->first();
        //$data->company_id = $kop .$kd;
		$data->name = $request->name;
		$data->address = $request->address;
		//$data->slug = Str::slug($request->input('name'));
		if ($request->branch === '1')
		{
			$data->branch = "Pusat";
		}
		else
		{
			$data->branch = "Cabang";
		}
		$data->siup = $request->siup;
		$data->zip_code = $request->zip_code;
		$data->provinsi = $request->provinsi;
		$data->kabupaten = $request->kabupaten;
		$data->kecamatan = $request->kecamatan;
		$data->kelurahan = $request->kelurahan;
		$data->created_by = auth()->user()->name;
		$data->save();
		//$data->roles()->attach($request['roles']);
		//$data->permissions()->attach($request['permissions']);
        return redirect('/company')->with('success', 'Company Update successfully');
    }

    

    public function delete($id)
    {
      Company::where('id',$id)->delete();
      return redirect()->back()->with("success","Berhasil di hapus !");
    }

    public function print($id)
    {
        $companies = Company::where('id',$id)->get();
 
        $pdf = PDF::loadview('/employee/employee_pdf',['companies'=>$companies]);
        return $pdf->download('employee-pdf');
    }
}
