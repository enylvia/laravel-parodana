<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Customer;
use App\Models\Company;
use App\Models\UserCompany;
use App\Models\DocumentHandover;
use Illuminate\Support\Facades\Validator;
use DB;
use DPDF;
use TPDF;
use Auth;

class HandoverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function index(Request $request)
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
			}
		}

		// if(Auth::user()->hasRole('superadmin','pengawas'))
		// {
		// 	$customers = Customer::all();
		// }else{
		// 	$customers = Customer::where('branch',$companyID)->get();
		// }

		if (Auth::user()->hasRole('superadmin', 'pengawas')) {
        	$customers = Customer::query();
		} else {
			$customers = Customer::where('branch', $companyID);
		}

		/**
		 * Pencarian berdasarkan:
		 * - name
		 * - reg_number
		 * - mobile_phone
		 * - address
		 */
		if ($request->has('search') && !empty($request->search)) {
			$customers->where('name', 'LIKE', '%' . $request->search . '%')
			->orWhere('reg_number', 'LIKE', '%' . $request->search . '%')
			->orWhere('mobile_phone', 'LIKE', '%' . $request->search . '%')
			->orWhere('address', 'LIKE', '%' . $request->search . '%');
		}

		$customers = $customers->get();

		return view('handover.index',compact('customers'));
	}

	public function create($reg_number)
	{
		$customers = Customer::where('reg_number',$reg_number)->get();
		$handovers = DocumentHandover::where('reg_number',$reg_number)->get();
		return view('handover.create',compact('customers','handovers'));
	}

	public function store(Request $request)
	{

        $validation_rules = [
            'berkas' => 'required',
            'berkas.*.id' => 'nullable',
            'berkas.*.reg_number' => 'required',
            'berkas.*.nama' => 'required',
            'berkas.*.status' => ['required', Rule::in(['asli', 'copy'])],
            'berkas.*.keterangan' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_rules);
        $validated = $validator->validate();

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $user_company = UserCompany::where('user_id', auth()->user()->id)->first();

        foreach ($validated['berkas'] as $key => $value) {
            $berkas = new DocumentHandover();
            if (!empty($value['id'])) {
                $berkas = DocumentHandover::where('id', $value['id'])->first();
            } 
            $berkas->reg_number = $value['reg_number'];
            $berkas->berkas = $value['nama'];
            $berkas->status = $value['status'];
            $berkas->keterangan = $value['keterangan'];
            $berkas->company_id = $user_company->company_id;
            $berkas->created_by = auth()->user()->name;
            $berkas->save();
        }

        return redirect()->back()->with('success', 'Save successfully');
	}

	public function edit($reg_number)
	{
		$customers = Customer::where('reg_number',$reg_number)->get();
		$handovers = DocumentHandover::where('reg_number',$reg_number)->get();
		return view('handover.edit',compact('customers','handovers'));
	}

	public function update(Request $request, $id)
	{
		$berkas = DocumentHandover::where('id',$id)->first();
		//$berkas->reg_number = $value;
		$berkas->berkas = $request->berkas;
		$berkas->status = $request->status;
		$berkas->keterangan = $request->keterangan;

		$berkas->save();

		return redirect()->back()->with('success', 'Update successfully');
	}

	public function delete($id)
    {
		DocumentHandover::where('id',$id)->delete();
		return redirect()->back()->with('success', 'Delete successfully');
	}
	public function print($id)
    {
		$reg_number = $id;

		$customers = Customer::where('reg_number',$reg_number)->get();
		$pdf = DPDF::loadView('handover.print', compact('customers'))->setPaper('a4', 'potrait')->setOptions([
                      'tempDir' => public_path(),
                      'chroot'  => public_path('/img/logo/'),
                  ]);
		//GENERATE PDF-NYA
		return $pdf->stream();
		//return view('customer.list.simulation',compact('customers'));
	}

}
