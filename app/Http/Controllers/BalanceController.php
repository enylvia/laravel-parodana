<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerContract;
use App\Models\User;
use App\Models\BalanceAccount;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\TransactionType;
use App\Models\AccountGroup;
use App\Models\Journal;
use App\Helper\Terbilang;
use Carbon\Carbon;
use Validator;
use DB;
use DPDF;
use TPDF;
use Notification;
use Auth;

class BalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$users = User::with('companies')->where('id',auth()->user()->id)->get();
			foreach($users as $user)
			{
				foreach($user->companies as $company)
				{
					$companyID = $company->id;
				}
			}
			
            if(Auth::user()->hasRole('superadmin','pengawas')) {							
				//$customers = Customer::join('customer_contract', 'customer_contract.customer_id', '=', 'customer.id' )
				//->select('customer.*', 'customer_contract.*')
				//->get();
				//$query = "SELECT customer.*, customer_contract.* FROM customer INNER JOIN customer_contract ON customer_contract.customer_id = customer.id";
				//$customers = DB::select($query)->toArray();
				 $customers = DB::table('customer')
					->join('customer_contract', 'customer_contract.customer_id', '=', 'customer.id')
					->join('account_balance', 'account_balance.customer_id', '=', 'customer.id')
					->select('customer.id','customer.name','customer.member_number','customer.bank_name', 'customer_contract.customer_id','customer_contract.atm_number', 
					'customer_contract.bank_pin','account_balance.*')->get();
			}else{
				//$customers = Customer::join('customer_contract', 'customer_contract.customer_id', '=', 'customer.id' )
				//->select('customer.*', 'customer_contract.*')
				$customers = DB::table('customer')
					->join('customer_contract', 'customer_contract.customer_id', '=', 'customer.id')
					->join('account_balance', 'account_balance.customer_id', '=', 'customer.id')
					->select('customer.*', 'customer_contract.*', 'account_balance.*')
					->where('customer.branch',$companyID)->get();
			}					
			
			return datatables()->of($customers)
				//->editColumn('status',function($data){
				//	if($data->status == 1){
				//		return 'Aktif';
				//	}elseif($data->status == 2){
				//		return 'Tidak Aktif';
				//	}
				//})
				->addColumn('mutation_date', function ($tgl) 
				{
					return date('d-m-Y', strtotime($tgl->mutation_date) );
				})
				//->addColumn('user.image', function ($data) {
				//	$imageName = $data->avatar!='' ? '/uploads/photo/'.$data->avatar:'/uploads/photo/noimage.jpg';
				//	return ' '.$imageName.' ';
					//if($data->avatar == $data->avatar){
					//	return '/uploads/photo/'.$data->avatar;
					//}elseif($data->avatar == ''){
					//	return '/uploads/photo/noimage.jpg';
					//}
				//   })
				//->addColumn('btnMutasiIn', function ($row) {
				//	$in = trans('general.in');
				//	return '<a id="#" data-target="#MutasiIn-'.$row->id.'" data-toggle="modal" class="btn btn-sm btn-default">'.$in.'</a>';
				//})
				//->addColumn('btnMutasiOut', function ($row) {
				//	$out = trans('general.out');
				//	return '<a id="#" data-target="#MutasiOut-'.$row->id.'" data-toggle="modal" class="btn btn-sm btn-default">'.$out.'</a>';
				//})
				->addColumn('edit', function ($prow) {                    
					//$btnEdit = '<a href="/customer/balance/edit/'.$prow->id.'" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-edit" title="Edit"></i></a>';
					$edit = trans('general.edit');
					return '<a id="#" data-target="#Edit-'.$prow->id.'" data-toggle="modal" class="btn btn-xs btn-info"><i class="fa fa-edit" title="'.$edit.'"></i></a>';
                    //return $btnEdit;
                })
				//->addColumn('view', function ($row) {
                //    $view = trans('general.view');
				//	return '<a href="/customer/balance/view/'.$row->id.'" class="btn btn-sm btn-default" target="_blank">'.$view.'</a>';
                //})
				->addColumn('delete', function ($row) {
					$delete = trans('general.delete');
					return '<a id="#" data-target="#Delete-'.$row->id.'" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash-o" title="'.$delete.'"></i></a>';
				})
				//->addColumn('btnHistory', function ($row) {
				//	$history = trans('general.history');
				//	return '<a href="/customer/balance/history/'.$row->id.'" class="btn btn-sm btn-default" target="_blank">'.$history.'</a>';
				//})				
				->addColumn('print', function ($prow) {                    
					$btnPrint = '<a href="/customer/balance/print/'.$prow->id.'" class="btn btn-xs btn-warning" target="_blank"><i class="fa fa-print" title="Print"></i></a>';
                    return $btnPrint;
                })
				->addColumn('journal', function ($prow) {  
					$journal = trans('general.journal');
					if($prow->journal == 1) {						
						return '<a id="#" data-target="#Journal-'.$prow->id.'" data-toggle="modal" class="btn btn-xs btn-warning" style="display:none;"><i class="fa fa-briefcase" title="'.$journal.'"></i></a>';
					}elseif($prow->journal == 0)
					{						
						return '<a id="#" data-target="#Journal-'.$prow->id.'" data-toggle="modal" class="btn btn-xs btn-warning"><i class="fa fa-briefcase" title="'.$journal.'"></i></a>';
					}
                })
				->rawColumns(['edit','delete','print','journal'])
				->make(true);
			
        }
		return view('customer.balance.index');
			
	}
	
	public function create()
	{
		return view('customer.balance.create');
	}
	
	public function store(Request $request)
	{
		
		$validation = Validator::make($request->all(), [            
			//'amount' => 'required',
        ]);			
				
        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			$users = User::with('companies')->where('id',auth()->user()->id)->get();
			foreach($users as $user)
			{
				foreach($user->companies as $company)
				{
					$companyID = $company->id;
				}
			}
			
			//$query = \DB::table('account_balance')->whereDate('mutation_date', $request->date_trans)
			//	->where('branch',$companyID)
			//	->select(\DB::raw('SUBSTRING(transaction_no, 4, 4) as kode'))
			//	->get();			
			//dd($query);
			//if ($query->count() > 0) {
			//	foreach ($query as $q) {
			//		//$no = ((int)$q['kode'])+1;
			//		$no = $q->kode+1;
			//		$kd = sprintf("%04s", $no);
			//	}
			//} else {
			//	$kd = "0001";
			//}
			
			$kode = "MTS";
			$kd = $this->nomorUnik(9); 
			$date = date("Y-m-d");
			$tahun = substr($date, 0, 4);
			$bulan = substr($date, 5, 2);
			$hari = substr($date, 8, 2);
			
			$customers = Customer::where('id',$request->cust_id)->first();
			$types = TransactionType::where('id',$request->transaction_type)->first();
			$balances = new BalanceAccount();
			$balances->transaction_no = $kode .$kd .$companyID .$bulan .$tahun;
			$balances->mutation_date = $request->date_trans;
			$balances->customer_id = $request->cust_id;
			$balances->member_number = $customers->member_number;
			$balances->from_account = $request->acc_number;
			$balances->to_account = $request->acc_to;
			$balances->branch = $companyID;
			$balances->transaction_type = $types->transaction_type;
			$balances->payment_type = $request->method_transaction;
			$balances->payment_method = $request->payment_method;
			if (!empty($request->input('amount'))){
				$balances->amount = str_replace('.', '', $request->amount);
			} else {
				$balances->amount = 0;
			}
			$balances->description = $request->description;
			$balances->save();
			CustomerContract::where('id',$request->cust_id)->update(['atm_number' => $request->acc_number ]);
			//return response()->json(['success'=>'Added new records.']);
			return redirect()->back()->with('success', 'Add successfully');
		}
		
		//return response()->json(['error'=>$validator->errors()]);
	}
	
	public function loaddata(Request $request)
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
			//$page = $request->get('page') ?? 5;
			$page = $request->page;
			if($query != '')
			{
				$customers = Customer::join('customer_contract', 'customer_contract.customer_id', '=', 'customer.id' )
				->select('customer.*', 'customer_contract.*')
				->where('customer.branch',$companyID)
				->where('customer.name', 'like', '%'.$query.'%')				
				//->orWhere('reg_number', 'like', '%'.$query.'%')
				//->orWhere('family_card_number', 'like', '%'.$query.'%')
				//->orWhere('card_number', 'like', '%'. $query.'%')
				->orderBy('customer.id', 'asc')
				->skip(0)->take($page)->get();
			}
			else
			{				
				//$data = Customer::where('branch',$companyID)
				$customers = Customer::join('customer_contract', 'customer_contract.customer_id', '=', 'customer.id' )
				->select('customer.*', 'customer_contract.*')
				->where('customer.branch',$companyID)
				->where('customer.name', 'like', '%'.$query.'%')
				->orderBy('customer.id', 'asc')
				->skip(0)->take($page)->get();
			}
			$total_row = $customers->count();
			//$paging = $customers->links();
			if($total_row > 0)
			{
				foreach($customers as $key => $row)
				{
					$i = $key +1;	
					$imageName =$row->avatar!='' ?'/uploads/photo/'.$row->avatar:'/uploads/photo/noimage.jpg';
					$in = trans('general.in');
					$out = trans('general.out');
					$history = trans('general.history');
					$output .= "
					<tr>
						<td align='center'> 				
							<img src='$imageName' style='height: 60px; width:60px;'>
						</td>
						<td style='display:none;'>$row->id</td>
						<td>$row->name</td>
						<td>$row->member_number</td>
						<td>$row->atm_number</td>
						<td>$row->bank_pin</td>
						<td>$row->bank_name</td>
						<td style='width:2px;' align='center'>
							<a class='btn btn-sm btn-default' id='MutasiIn' data-target='#MutasiIn-$row->id' data-toggle='modal'>
								$in
							</a>
						</td>
						<td style='width:2px;' align='center'>
							<a class='btn btn-sm btn-default' id='MutasiOut' data-target='#MutasiOut-$row->id' data-toggle='modal'>
								$out
							</a>
						</td>
						<td style='width:2px;' align='center'>
							<a class='btn btn-sm btn-default' href='/customer/balance/history/$row->id' target='_blank'>
								$history
							</a>
						</td>
						<!--td style='width:2px;' align='center'>
							<a class='btn btn-sm btn-default' href='/customer/balance/view/$row->id'>
								<i class='fa fa-eye' title='Lihat Data'></i>  
							</a>
						</td>
						<td style='width:2px;' align='center'>
							<a class='btn btn-sm btn-success' href='/customer/list/print/$row->id' target='_blank'>
								<i class='fa fa-print' title='Cetak Formulir'></i>  
							</a>
						</td-->
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
		//return View::make('customer.balance.index', array('data' => $data));
	}
	
	public function edit($id)
	{
		$balances = BalanceAccount::where('id',$id)->get();
		return view('customer.balance.edit',compact('balances'));
	}
	
	public function update(Request $request, $id)
	{
		
		$validation = Validator::make($request->all(), [            
			'amount' => 'required',
        ]);			
				
        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
			return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
		
			$balances = BalanceAccount::where('id',$id)->first();
			//$balances->transaction_no = $this->nomorUnik(12);
			$balances->mutation_date = $request->date_trans;
			//$balances->customer_id = $request->cust_id;
			//$balances->member_number = $request->member_number;
			//$balances->from_account = $request->acc_number;
			//$balances->to_account = $request->acc_to;
			$balances->transaction_type = $request->transaction_type;
			$balances->payment_method = $request->payment_method;
			if (!empty($request->input('amount'))){
				$balances->amount = str_replace('.', '', $request->amount);
			} else {
				$balances->amount = 0;
			}
			$balances->description = $request->description;
			$balances->save();
			CustomerContract::where('id',$request->cust_id)->update(['atm_number' => $request->acc_number ]);
			//return response()->json(['success'=>'Added new records.']);
			return redirect()->back()->with('success', 'Update successfully');
		}
		
		//return response()->json(['error'=>$validator->errors()]);
	}
	
	public function view($id)
	{
		$balances = BalanceAccount::where('customer_id',$id)->get();
		return view('customer.balance.view',compact('balances'));
	}
	
	public function delete($id)
	{
		$balances = BalanceAccount::where('id',$id)->delete();
		return redirect()->back()->with('success','Delete successfully');
	}
	
	public function print($id)
	{
		$pdf = new TPDF;                

		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->company_id;
			}
		}
		
        $profiles = Company::where('company_id',$companyID)->get();
		
		foreach($profiles as $profile)
		{
			$profileName = $profile->name;
			$profileAddress = $profile->address;
			$provinsi = Provinsi::where('id',$profile->provinsi)->first();
			$kabupaten = Kabupaten::where('id',$profile->kabupaten)->first();
			$kecamatan = Kecamatan::where('id',$profile->kecamatan)->first();
			$kelurahan = Kelurahan::where('id',$profile->kelurahan)->first();
		}
        
        $pdf::AddPage('L', 'A4');
        //$pdf::AddPage();
        ob_start();
        // Header        
		$pdf::setJPEGQuality(90);
		$pdf::Image('img/logo/logo-small.png', 10, 10, 25, 0, 'PNG', '');
		$pdf::SetFont('Arial', 'B', 18);
        $pdf::Cell(0, 10, $profileName, 0, 2, 'C');
        $pdf::SetFont('Arial', 'B', 12);
		$pdf::Cell(0, 10, $profileAddress, 0, 1, 'C');
        $pdf::Cell(0, 10, "".$kelurahan->nama." , ".$kecamatan->nama." , ".$kabupaten->nama." , ".$provinsi->nama, 'B', 1,  'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
		
        $pdf::Ln();

        // Neraca Saldo
        $pdf::SetFont('Arial', 'B', 14);
        $pdf::Cell(0, 10, "HISTORI TRANSAKSI", 0, 2, 'C');
		$pdf::Ln();
		
		//$customers = Customer::where('id',$id)->first();
		if(Auth::user()->hasRole('superadmin','pengawas')) 
		{	
			$customers = DB::table('customer')
				->join('customer_contract', 'customer_contract.customer_id', '=', 'customer.id')
				->join('account_balance', 'account_balance.customer_id', '=', 'customer.id')
				->select('customer.id','customer.name','customer.member_number','customer.bank_name', 'customer_contract.customer_id','customer_contract.atm_number', 
				'customer_contract.bank_pin','account_balance.*')
				->where('account_balance.id',$id)->first();
		}else{
			$customers = DB::table('customer')
				->join('customer_contract', 'customer_contract.customer_id', '=', 'customer.id')
				->join('account_balance', 'account_balance.customer_id', '=', 'customer.id')
				->select('customer.*', 'customer_contract.*', 'account_balance.*')
				->where('customer.branch',$companyID)
				->where('account_balance.id',$id)->first();
		}
		
		$pdf::SetFont('Arial', 'B', 12);
		$pdf::MultiCell(40, 10, "No. Anggota", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, $customers->member_number, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::Ln();
		$pdf::MultiCell(40, 10, "Nama", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, $customers->name, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();

        $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(20, 8, "NO", 1, 0, 'C');
        $pdf::Cell(40, 8, "NO. TRANSAKSI", 1, 0, 'C');
        $pdf::Cell(40, 8, "TGL. TRANSAKSI", 1, 0, 'C');
        $pdf::Cell(100, 8, "KETERANGAN", 1, 0, 'C');
        $pdf::Cell(60, 8, "JUMLAH", 1, 0, 'C');
        $pdf::Ln();        
		
		$historis = BalanceAccount::where('account_balance.id',$id)->orderBy('mutation_date', 'asc')->get();
		//dd($pendaptans);
		foreach($historis as $key => $item)
		{			
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(20, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(40, 8, $item->transaction_no, 1, 0, 'C');
	        $pdf::Cell(40, 8, date('d-m-Y', strtotime($item->mutation_date)), 1, 0, 'C');
	        $pdf::Cell(100, 8, $item->description, 1, 0, 'L');
	        $pdf::Cell(60, 8, "Rp. ".number_format($item->amount, 0, ',', '.').",-", 1, 0, 'R');
	        $pdf::Ln();

    	}
		
		$in = BalanceAccount::where('id',$id)->where('payment_type', 'IN')->first();
		$out = BalanceAccount::where('id',$id)->where('payment_type', 'OUT')->first();
		$ins = !empty($in->amount) ? $in->amount : 0;
		$outs = !empty($outs->amount) ? $outs->amount : 0;
		$total = $ins - $outs;
		
		$pdf::SetFont('Arial', 'B', 10);
		$pdf::Cell(260, 8, "Rp. ".number_format($total, 0, ',', '.').",-", 1, 0, 'R');
		$pdf::Ln();
		
        $pdf::SetFont('Arial', 'B', 10);
        $pdf::Cell(260, 8, "TERBILANG :", 1, 0, 'L');
		$pdf::Ln();
		
        $pdf::SetFont('Arial', 'B', 8);
		$pdf::Cell(260, 10, strtoupper(Terbilang::bilang($total)) . "RUPIAH", 1, 0, 'R');
        $pdf::Ln();

        // Footer
        $pdf::SetY(179);
        $pdf::SetX(165);
        $pdf::SetFont('Arial','I',8);
        $pdf::Cell(0,10,"Dicetak Oleh Akuntan : ". $profileName ." Pada ".date("d-m-Y H:i:s")
        ." WIB", 0, 0, 'C');
		
		ob_end_clean();
		return $pdf::Output('rekening_koran.pdf','I');
	}
	
	public function history($id)
	{  
		//if(empty($id)) return redirect('customer/balance');
		
		$pdf = new TPDF;                

		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->company_id;
			}
		}
		
        $profiles = Company::where('company_id',$companyID)->get();
		
		foreach($profiles as $profile)
		{
			$profileName = $profile->name;
			$profileAddress = $profile->address;
			$provinsi = Provinsi::where('id',$profile->provinsi)->first();
			$kabupaten = Kabupaten::where('id',$profile->kabupaten)->first();
			$kecamatan = Kecamatan::where('id',$profile->kecamatan)->first();
			$kelurahan = Kelurahan::where('id',$profile->kelurahan)->first();
		}
        
        $pdf::AddPage('L', 'A4');
        //$pdf::AddPage();
        ob_start();
        // Header        
		$pdf::setJPEGQuality(90);
		$pdf::Image('img/logo/logo-small.png', 10, 10, 25, 0, 'PNG', '');
		$pdf::SetFont('Arial', 'B', 18);
        $pdf::Cell(0, 10, $profileName, 0, 2, 'C');
        $pdf::SetFont('Arial', 'B', 12);
		$pdf::Cell(0, 10, $profileAddress, 0, 1, 'C');
        $pdf::Cell(0, 10, "".$kelurahan->nama." , ".$kecamatan->nama." , ".$kabupaten->nama." , ".$provinsi->nama, 'B', 1,  'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
		
        $pdf::Ln();

        // Neraca Saldo
        $pdf::SetFont('Arial', 'B', 14);
        $pdf::Cell(0, 10, "HISTORI TRANSAKSI", 0, 2, 'C');
		$pdf::Ln();
		
		$customers = Customer::where('id',$id)->first();
		
		$pdf::SetFont('Arial', 'B', 12);
		$pdf::MultiCell(40, 10, "No. Anggota", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, $customers->member_number, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::Ln();
		$pdf::MultiCell(40, 10, "Nama", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, $customers->name, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();

        $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(20, 8, "NO", 1, 0, 'C');
        $pdf::Cell(40, 8, "NO. TRANSAKSI", 1, 0, 'C');
        $pdf::Cell(40, 8, "TGL. TRANSAKSI", 1, 0, 'C');
        $pdf::Cell(100, 8, "KETERANGAN", 1, 0, 'C');
        $pdf::Cell(60, 8, "JUMLAH", 1, 0, 'C');
        $pdf::Ln();        
		
		$historis = BalanceAccount::where('customer_id',$id)->orderBy('mutation_date', 'asc')->get();
		//dd($pendaptans);
		foreach($historis as $key => $item)
		{			
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(20, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(40, 8, $item->transaction_no, 1, 0, 'C');
	        $pdf::Cell(40, 8, date('d-m-Y', strtotime($item->mutation_date)), 1, 0, 'C');
	        $pdf::Cell(100, 8, $item->description, 1, 0, 'L');
	        $pdf::Cell(60, 8, "Rp. ".number_format($item->amount, 0, ',', '.').",-", 1, 0, 'R');
	        $pdf::Ln();

    	}
		
		$in = BalanceAccount::where('customer_id',$id)->where('payment_type', 'IN')->first();
		$out = BalanceAccount::where('customer_id',$id)->where('payment_type', 'OUT')->first();
		$ins = !empty($in->amount) ? $in->amount : 0;
		$outs = !empty($outs->amount) ? $outs->amount : 0;
		$total = $ins - $outs;
		
		$pdf::SetFont('Arial', 'B', 10);
		$pdf::Cell(260, 8, "Rp. ".number_format($total, 0, ',', '.').",-", 1, 0, 'R');
		$pdf::Ln();
		
        $pdf::SetFont('Arial', 'B', 10);
        $pdf::Cell(260, 8, "TERBILANG :", 1, 0, 'L');
		$pdf::Ln();
		
        $pdf::SetFont('Arial', 'B', 8);
		$pdf::Cell(260, 10, strtoupper(Terbilang::bilang($total)) . "RUPIAH", 1, 0, 'R');
        $pdf::Ln();

        // Footer
        $pdf::SetY(179);
        $pdf::SetX(165);
        $pdf::SetFont('Arial','I',8);
        $pdf::Cell(0,10,"Dicetak Oleh Akuntan : ". $profileName ." Pada ".date("d-m-Y H:i:s")
        ." WIB", 0, 0, 'C');
		
		ob_end_clean();
		return $pdf::Output('rekening_koran.pdf','I');
	}
	
	public function paging(Request $request)
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
			$page = $request->get('page') ?? 5;
			if($query != '')
			{
				$customers = Customer::join('customer_contract', 'customer_contract.customer_id', '=', 'customer.id' )
				->select('customer.*', 'customer_contract.*')
				->where('customer.branch',$companyID)
				->where('customer.name', 'like', '%'.$query.'%')				
				//->orWhere('reg_number', 'like', '%'.$query.'%')
				//->orWhere('family_card_number', 'like', '%'.$query.'%')
				//->orWhere('card_number', 'like', '%'. $query.'%')
				->orderBy('customer.id', 'asc')
				->skip(0)->take($page)->get();	
			}
			else
			{				
				//$data = Customer::where('branch',$companyID)
				$customers = Customer::join('customer_contract', 'customer_contract.customer_id', '=', 'customer.id' )
				->select('customer.*', 'customer_contract.*')
				->where('customer.branch',$companyID)
				->where('customer.name', 'like', '%'.$query.'%')
				->orderBy('customer.id', 'asc')
				->skip(0)->take($page)->get();	
			}
			$total_row = $customers->count();
			//$paging = $customers->links();
			if($total_row > 0)
			{
				foreach($customers as $key => $row)
				{
					$i = $key +1;	
					$imageName =$row->avatar!='' ?'/uploads/photo/'.$row->avatar:'/uploads/photo/noimage.jpg';
					$in = trans('general.in');
					$out = trans('general.out');
					$history = trans('general.history');
					$output .= "
					<tr>
						<td align='center'> 				
							<img src='$imageName' style='height: 60px; width:60px;'>
						</td>
						<td style='display:none;'>$row->id</td>
						<td>$row->name</td>
						<td>$row->member_number</td>
						<td>$row->atm_number</td>
						<td>$row->bank_pin</td>
						<td>$row->bank_name</td>
						<td style='width:2px;' align='center'>
							<a class='btn btn-sm btn-default' id='MutasiIn' data-target='#MutasiIn-$row->id' data-toggle='modal'>
								$in
							</a>
						</td>
						<td style='width:2px;' align='center'>
							<a class='btn btn-sm btn-default' id='MutasiOut' data-target='#MutasiOut-$row->id' data-toggle='modal'>
								$out
							</a>
						</td>
						<td style='width:2px;' align='center'>
							<a class='btn btn-sm btn-default' href='/customer/balance/history/$row->id' target='_blank'>
								$history
							</a>
						</td>
						<td style='width:2px;' align='center'>
							<a class='btn btn-sm btn-default' href='/customer/balance/view/$row->id'>
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
		//return View::make('customer.balance.index', array('data' => $data));
	}
	
	public function loadtype(Request $request)
	{
		
		if ($request->ajax()) {
			$users = User::with('companies')->where('id',auth()->user()->id)->get();
			
			foreach($users as $user)
			{
				foreach($user->companies as $company)
				{
					$companyID = $company->id;
				}
			}
			
			$search = $request->transaction_type;

			if($search == '')
			{
				$types = TransactionType::all();
				//$types = TransactionTypeselect("id", "transaction_type")
            	//	->where('transaction_type', $search)
            	//	->get();
			}else{
				//$types = TransactionType::where('transaction_type', $search)->get();
				$types = TransactionTypeselect("id", "transaction_type")
            		->where('transaction_type', 'LIKE', "%$search%")
            		->get();
			} 

			$response = array();
			foreach($types as $tipe){
				$response[] = array(
					"id"=>$tipe->id,
					"text"=>$tipe->transaction_type
				);
			}
		}

      return response()->json($response);
	}
	
	public  function nomorUnik($length) 
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomOrder = '';
        for ($i = 0; $i < $length; $i++) {
            $randomOrder .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomOrder;
    }
	
	public function journal($id)
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->company_id;
			}
		}
		
		$balances = BalanceAccount::where('id',$id)->where('journal',0)->first();
		//dd($payments);
		$types = TransactionType::where('transaction_type',$balances->transaction_type)->get();
		
		foreach($types as $key => $type)
		{	
			$key+1;
			$tipe = $type->tipe;
			$akun = $type->account_number;
			print_r ($tipe);
			$groups = AccountGroup::where('account_number',$type->account_number)->get();
			//dd($groups);
			//$data = Journal::where('transaction_no',$id)->where('id',$akun->id)->first();	
			foreach($groups as $group)
			{
			//$journals = Journal::where('transaction_type',$payments->transaction_type)->get();
				$data = new Journal();
				$data->account_id = $group->id;
				$data->account_number = $group->account_number;	
				$data->tipe = $type->tipe;	
				$data->proof_number = $balances->transaction_no;
				$data->transaction_date = $balances->mutation_date;
				$data->company_id = $companyID;
				$data->description = $type->description;
				$data->beginning_balance = $balances->amount;
				$data->nominal = $balances->amount;
				$data->ending_balance = $balances->amount;
				$data->save();
				//print_r ($data);
			}
		}
		
		BalanceAccount::where('transaction_no', '=', $balances->transaction_no)->update(['journal' => 1]);
		
		return redirect()->back()->with('success', 'Journal Successfully');
	}
	
}
