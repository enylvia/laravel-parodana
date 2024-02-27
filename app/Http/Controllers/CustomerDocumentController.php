<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerDocument;
use Carbon\Carbon;
use Validator;
use DB;
use Auth;

class CustomerDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function survey()
	{
		$surveys = Customer::where('status','=','survey')->get();
		
		return view('loan.survey',compact('surveys'));
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
		
		if ( Auth::user()->role !=='superadmin') {
			$documents = Customer::query();
		}else{
			$documents = Customer::where('created_by','=',auth()->user()->name);
		}

		/**
		 * Pencarian berdasarkan:
		 * - name
		 * - reg_number
		 * - mobile_phone
		 * - address
		 */
		if ($request->has('search') && !empty($request->search)) {
			$documents->where('name', 'LIKE', '%' . $request->search . '%')
			->orWhere('reg_number', 'LIKE', '%' . $request->search . '%')
			->orWhere('mobile_phone', 'LIKE', '%' . $request->search . '%')
			->orWhere('address', 'LIKE', '%' . $request->search . '%');
		}

		$documents = $documents->get();

		return view('customer.document.index',compact('documents'));
	}
	
	public function create($id)
	{
		$getID = $id;
		$documents = Customer::where('id',$id)->get();
		foreach($documents as $document)
		{
			$regNumber = $document->reg_number;
		}
		
		return view('customer.document.create',compact('documents','getID','regNumber'));
	}
	
	public function store(Request $request)
	{
		$validation = Validator::make($request->all(), [
            'customer_id' => 'required|string|max:255', 
			'document_name' => 'required|string|max:255',
			'document_category' => 'required',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {			
			
			foreach($request->avatars as $image)
			{
					$dir = 'uploads/documents/';
					$ext = '.'.$image->getClientOriginalExtension();
					$fileName = str_replace($ext, date('d-m-Y-H-i') . $ext, $image->getClientOriginalName());
					$image->move($dir, $fileName);               

					$documents = new CustomerDocument();	
					$documents->customer_id = $request->customer_id;
					$documents->reg_number = $request->reg_number;
					$documents->document_name = $request->document_name;
					$documents->document_category = $request->document_category;
					$documents->document_status = $request->document_status;										
					$documents->document_file = str_replace('','_',$fileName);
					$documents->document_file = $fileName;

					$documents->save();
				}
			return redirect('/customer/document')->with('success', 'Upload Document successfully');
		}
	}
	
	public function edit($id)
	{
		$getID = $id;
		$documents = CustomerDocument::where('customer_id',$id)->get();
		
		return view('customer.document.edit',compact('documents','getID'));
	}
	
	public function update(Request $request, $id)
	{
		
	}
	
	public function destroy($id){
   
		$data = CustomerDocument::where('id',$id)->first();
		$data->delete();
		$dir = 'uploads/photo/';                
			if ($data->document_file != '' && File::exists($dir . $data->document_file))
				File::delete($dir . $data->document_file);		
		
		return response()->json([
			'success' => 'Record deleted successfully!'
		]);
		
		//return response()->json($data);
		//return redirect()->back()->with("success","Berhasil di hapus !");
	}
	
	function fetch()
    {
     $images = \File::allFiles(public_path('uploads/documents'));
     $output = '<div class="row">';
     foreach($images as $image)
     {
      $output .= '
      <div class="col-md-2" style="margin-bottom:16px;" align="center">
                <img src="'.asset('uploads/documents/' . $image->getFilename()).'" class="img-thumbnail" width="175" height="175" style="height:175px;" />
                <button type="button" class="btn btn-link remove_image" id="'.$image->getFilename().'">Remove</button>
            </div>
      ';
     }
     $output .= '</div>';
     echo $output;
    }	
	
}
