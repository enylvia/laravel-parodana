<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;
use App\Models\Employee;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\CardType;
use App\Models\Education;
use App\Models\Maritial;
use App\Models\Religion;
use Carbon\Carbon;
use DPDF;
use TPDF;
use DB;
use Auth;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');;
    }

    public function index()
    {
		//$students = User::whereHas(
		//	'roles', function($q){
		//		$q->where('name', 'Teacher');
		//	}
		//)->get();
        //$users = User::where('id','<>',1)->where('id','<>',2)->paginate(10);
        $roles = Role::all();
		$users = User::with('companies')->where('id','<>',1)->where('id','<>',2)->paginate(10);
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
				$companyBranch = $company->branch;
				$companyName = $company->name;
			}
		}
		//DB::statement("SET @count = 0;");
        //DB::statement("UPDATE users SET `id` = @count:= @count + 1;");
        //DB::statement("ALTER TABLE `users` AUTO_INCREMENT = 1;");
        return view('employee.index', compact('users','roles','companyBranch','companyName'));
    }

    public function create()
    {        
		//$roles = Role::all();
		$roles = Role::where('name', '<>', 'superadmin')->get();
		$permissions = Permission::all();
		$companies = Company::all();
		$cards = CardType::all();
		$religions = Religion::all();
		$educations = Education::all();
		$maritials = Maritial::all();
        return view('employee.create',compact('cards','religions','educations','maritials','companies','roles','permissions'));
    }

    public function store(Request $request)
    {
        // aturan Validasi //
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
			'population_card' => 'required|string|max:255',
			'family_card' => 'required|string|max:255',
			'address' => 'required|string|max:255',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
        
            $data = new User();            
            $data->name = $request->name;
			$data->mobile_phone = $request->mobile_phone;
			//$data->username = $request->username;
            //$data->slug = str_slug($request->input('name'));
			//$data->slug = Str::slug($request->input('name'));
			$data->email = $request->email;
            $data->password = bcrypt($request->password);
			//$data->password = Hash::make($request->password);
			//$data->avatar = $request->avatar;
			if ($request->hasFile('avatar')) {
				$dir = 'uploads/photo/';
				$fileName = strtolower($request->file('avatar')->getClientOriginalName());
				$request->file('avatar')->move($dir, $fileName);
				$data->avatar = $fileName;
			} 
			$data->last_login_at = Carbon::now()->toDateTimeString();
			$data->last_login_ip = $request->getClientIp();
            $data->save();
            //$user->sendVerificationEmail();            
            //$this->notify(new UserRegistrationNotification($data));

            // Attach employee
            //$data->roles()->attach($request['roles']);
			$data->companies()->attach($request['branch']);
			
			$q = \DB::table('employee')
			  ->select(\DB::raw('max(RIGHT(employee_id, 6)) as kd_max'))
			  ->get();
			$kd = "";
			if($q->count() > 0)
			{
				foreach($q as $k){
					$tmp = $k->kd_max+1;
					$kd = sprintf("%06s", $tmp);
				}
			}else{
				$kd = "000001";
			}
			
			$kop = "PEG";
			$employee = new Employee();
			$employee->user_id = $data->id;
			$employee->employee_id = $kop .$kd;            
			$employee->address = $request->address;			
			$employee->branch = $request->branch;
			$employee->date_of_birth = $request->date_birth;
			$employee->birth_place = $request->birth_place;
			if ($request->gender === '1')
			{
				$employee->gender = "Lelaki";
			}
			else
			{
				$employee->gender = "Perempuan";
			}			
			//$employee->slug = Str::slug($request->input('name'));
			$employee->population_card = $request->population_card;
			$employee->family_card = $request->family_card;
			$employee->maritial = $request->maritial;
			$employee->religion = $request->religion;
			$employee->education = $request->education;
            $employee->zip_code = $request->zip_code;
			//$employee->provinsi = $request->provinsi;
			//$employee->kabupaten = $request->kabupaten;
			//$employee->kecamatan = $request->kecamatan;
			//$employee->kelurahan = $request->kelurahan;
			$employee->mother_name = $request->mother_name;
			$employee->mother_phone = $request->mother_phone;
			$employee->father_name = $request->father_name;
			$employee->father_phone = $request->father_phone;
			$employee->payroll_bank = $request->payroll_bank;
			$employee->account_number = $request->account_number;
			$employee->id_card = $request->id_card;
			$employee->home_status = $request->home_status;
			$employee->created_by = auth()->user()->name;
            $employee->save();
			
            return redirect('/employee')->with('success', 'employee Add successfully');
        }        
        //DB::statement("UPDATE employee, contents SET employee.type = contents.type where employee.content_id = contents.id");
    }

    public function edit($id)
    {
        $users = User::where('id',$id)->get();
        //$roles = Role::all();
		$roles = Role::where('name', '<>', 'superadmin')->get();
		$permissions = Permission::all();
		$companies = Company::all();
		$cards = CardType::all();
		$religions = Religion::all();
		$educations = Education::all();
		$maritials = Maritial::all();		
        return view('employee.edit',compact('companies','cards','religions','educations','maritials','users','roles','permissions'));
    }

    public function update(Request $request, $id)
    {
        $data = User::where('id',$id)->first();
        $data->name = $request->name;
		$data->mobile_phone = $request->mobile_phone;
        //$data->slug = str_slug($request->input('name'));
		$data->email = $request->email;
        //$data->password = bcrypt($request->password);
		//$data->password = Hash::make($request->password);
		//$data->avatar = $request->avatar;
		if ($request->hasFile('avatar')) {               
			$dir = 'uploads/photo/';                
			if ($data->avatar != '' && File::exists($dir . $data->avatar))
				File::delete($dir . $data->avatar);
			//$extension = strtolower($request->file('image')->getClientOriginalExtension());
			//$fileName = str_random() . '.' . $extension;
			$fileName = strtolower($request->file('avatar')->getClientOriginalName());
			$request->file('avatar')->move($dir, $fileName);               
			$data->avatar = $fileName;                
		} elseif ($request->remove == 1 && File::exists('uploads/photo/' . $data->avatar)) {
			File::delete('uploads/photo/' . $data->avatar);
			$data->avatar = null;
		}
		$data->last_login_at = Carbon::now()->toDateTimeString();
		$data->last_login_ip = $request->getClientIp();
        $data->save();
		//$data->roles()->attach($request['roles']);
		//$data->permissions()->attach($request['permissions']);
		$data->companies()->sync($request['branch']);
		
		//$employee = new Employee();
		$employee = Employee::where('user_id',$id)->first();
		$employee->user_id = $id;
		//$employee->employee_id = $kop .$kd;            
		$employee->address = $request->address;			
		$employee->branch = $request->branch;
		$employee->date_of_birth = $request->date_birth;
		$employee->birth_place = $request->birth_place;
		if ($request->gender === '1')
		{
			$employee->gender = "Lelaki";
		}
		else
		{
			$employee->gender = "Perempuan";
		}
		//$employee->slug = Str::slug($request->input('name'));
		$employee->population_card = $request->population_card;
		$employee->family_card = $request->family_card;
		$employee->maritial = $request->maritial;
		$employee->religion = $request->religion;
		$employee->education = $request->education;
		$employee->zip_code = $request->zip_code;
		//$employee->provinsi = $request->provinsi;
		//$employee->kabupaten = $request->kabupaten;
		//$employee->kecamatan = $request->kecamatan;
		//$employee->kelurahan = $request->kelurahan;
		$employee->mother_name = $request->mother_name;
		$employee->mother_phone = $request->mother_phone;
		$employee->father_name = $request->father_name;
		$employee->father_phone = $request->father_phone;
		$employee->payroll_bank = $request->payroll_bank;
		$employee->account_number = $request->account_number;
		$employee->id_card = $request->id_card;
		$employee->home_status = $request->home_status;
		$employee->created_by = auth()->user()->name;
		$employee->save();		
		
        return redirect('/employee')->with('success', 'employee Add successfully');
    }

    public function delete($id)
    {
      $user = User::where('id',$id)->delete();
	  Employee::where('user_id',$id)->delete();
	  $user->roles()->detach($id);
	  $user->companies()->detach($id);
      return redirect()->back()->with("success","Berhasil di hapus !");
    }

    public function print($id)
    {
        //$users = User::where('id',$id)->get();
 
        //$pdf = PDF::loadview('/employee/employee_pdf',['users'=>$users]);
        //return $pdf->download('employee-pdf');
		//$users = User::where('id','<>',1)->get();		
		$users = User::where('id',$id)->get();
		$pdf = DPDF::loadView('employee.print', compact('users'))->setPaper('a4', 'potrait')->setOptions([
                      'tempDir' => public_path(),
                      'chroot'  => public_path('/img/logo/'),
                  ]);
		//GENERATE PDF-NYA
		return $pdf->stream();
    }
	
	public function printAll($id)
    {
		//$users = User::whereHas('roles', function ($q) {
		//		$q->whereIn('name', ['admin', 'warehouse']);
		//	})->get();
			
		$users = User::where('id','<>',1)->get();		
		
		$pdf = DPDF::loadView('employee.view', compact('users'))->setPaper('a4', 'potrait')->setOptions([
                      'tempDir' => public_path(),
                      'chroot'  => public_path('/img/logo/'),
                  ]);
		//GENERATE PDF-NYA
		return $pdf->stream();	
    }
	
	public function profile($id)
    {
		$users = User::where('name',$id)->get();
		return view('employee.profile',compact('users'));
	}
	
	public function changePassword(Request $request){
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("errors","kata sandi sekarang tidak cocok dengan yang telah di buat. Coba lagi.");
        }
        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("errors","Kata sandi baru tidak boleh sama dengan kata sandi yang sekarang. pilihlah kata sandi yang berbeda.");
        }
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6',
			'confirmation_password' => 'string|min:6|confirmed',
        ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        return redirect()->back()->with("success","Password sukses di rubah !");
    }
	
}
