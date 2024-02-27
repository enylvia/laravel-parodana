<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerContract;
use App\Models\User;
use App\Models\Company;
use App\Models\Loan;
use App\Models\Installment;
use App\Models\Savings;
use App\Models\Posting;
use App\Models\AccountGroup;
use App\Models\Employee;
use App\Models\Journal;
use App\Models\Tempo;
use App\Models\BalanceAccount;
use App\Models\TransactionType;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Helper\Terbilang;
use Carbon\Carbon;
use Validator;
use Auth;
use URL;
use TPDF;
use Yajra\Datatables\Datatables;
use Redirect;

class InstallmentController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');;
        //view()->share('controller', 'InstallmentController.php');
        //view()->share('title', $this->getTitle('installment'));
        //view()->share('description', $this->getDescription('installment'));
    }
	
	public function index(Request $request)
	{
		//$users = User::with('companies')->where('id',auth()->user()->id)->get();
		//foreach($users as $user)
		//{
		//	foreach($user->companies as $company)
		//	{
		//		$companyID = $company->id;
		//	}
		//}
		
		//if(Auth::user()->hasRole('superadmin','pengawas')) 
		//{
		//	$customers = Customer::all();
		//	$loans = Loan::paginate(10);
		//}else{
		//	$customers = Customer::where('branch',$companyID)->get();
		//	$loans = Loan::where('company_id',$companyID)->paginate(10);
		//}
				
		//$angsurans = Installment::all();
		
		//return view('loan.installment.index',compact('customers','angsurans','loans'));
		//$astaga = Customer::join('loans', 'customer.id', '=' ,'loans.customer_id')					
        //             ->select('loans.*', 'customer.*') 
        //             ->get();
		//foreach($astaga as $lupa)
		//{
		//	$hakan1 = $lupa->id;
		//	$hakan2 = $lupa->member_number;
		//	$hakan3 = $lupa->pay_principal * $lupa->time_period;
		//	$hakan4 = $lupa->pay_interest * $lupa->time_period;
		//	Loan::where('member_number', '=', $hakan2)->update(['customer_id' => $hakan1, 'total_principal' => $hakan3, 'total_interest' => $hakan4 ]);
		//}
		if ($request->ajax()) {
			$users = User::with('companies')->where('id',auth()->user()->id)->get();
			foreach($users as $user)
			{
				foreach($user->companies as $company)
				{
					$companyID = $company->id;
				}
			}
			//{{URL::to('/receipt/print/' .$receipt->id)}}
            //$tempos = Tempo::all();
			if(Auth::user()->hasRole('superadmin','pengawas')) 
		    {
				//$query = "SELECT customer.*, loans.* FROM customer INNER JOIN loans ON loans.customer_id = customer.id";
				//$loans = DB::select($query);
				$loans = Customer::join('loans', 'customer.id', '=', 'loans.customer_id')
				->get(['customer.id','customer.name','customer.member_number', 'loans.*']);
			   
				//$loans = Customer::join('loans', 'customer.id', '=' ,'loans.customer_id')					
                //     ->select('customer.*', 'loans.*')
                //     ->get();
				
			}else{
				$loans = Customer::join('loans', 'customer.id', '=', 'loans.customer_id')
				->where('loans.company_id',$companyID)
				->get(['customer.id','customer.name','customer.member_number', 'loans.*']);
				
				//$loans = Loan::join('customer', 'customer.id', '=' ,'loans.customer_id')
				//	->where('loans.company_id',$companyID)
                //     ->select('customer.id','customer.name','loans.*');
                     //->get();
			}
			return Datatables::of($loans)
            //return datatables()->of($loans)
				//->filter(function ($instance) use ($request) {					
				//	if (!empty($request->get('search'))) {
				//		 $instance->where(function($w) use($request){
				//			$search = $request->get('search');
				//			$w->orWhere('name', 'LIKE', "%$search%")
				//			->orWhere('member_number', 'LIKE', "%$search%");
				//		});
				//	}
				//})
				//->editColumn('id', function ($row) {
				//	return $row->id ? $row->id : "";
				//})
            	//->addIndexColumn()
				->editColumn('details_url', function($row) {
					return url('/installment/getDetail/'.$row->member_number);
					//$angsuran = Installment::where('member_number',$row->member_number)->get();
					//if(!empty($angsuran->member_number)){
					//	return 'data not found';
					//}else{
						//return url('/installment/getDetail/'.$row->loan_id);
					//	return '<a id="free" data-target="#free" data-id="$row->id" data-toggle="modal" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i></a>';
					//}
					//return '<i class="fa fa-plus" data-id='.$row->member_number.' onclick="controlDetail(this);"></i>';
					//return $row->member_number;
				})
				->editColumn('customer.name', function ($model) {
					return $model->name;
				})
				->editColumn('contract_date', function ($user) 
				{
					return date('d-m-Y', strtotime($user->contract_date) );
				})
				->editColumn('start_month', function ($user) 
				{
					return date('d-m-Y', strtotime($user->start_month) );
				})
				->editColumn('btnView', function ($row) {                    
                    return '<a href="/installment/view/'.$row->member_number.'" class="btn btn-xs btn-warning" target="_self"><i class="fa fa-eye" title="Lihat"></i></a>';
                })
				->editColumn('btnEdit', function ($row) {                    
                    return '<a href="/installment/edit/'.$row->member_number.'" class="btn btn-xs btn-warning" target="_self"><i class="fa fa-edit" title="Edit"></i></a>';
                })
                ->editColumn('btnPay', function ($row) {
					$angsuran = Installment::where('member_number',$row->member_number)->first();
					if(!empty($angsuran->member_number)){						
						return '<a href="#" class="btn btn-xs btn-success" target="_self" style="display:none;"><i class="fa fa-plus" title="Buat Table"></i></a>';
					 }else{
						return '<a href="/installment/create/pay/'.$row->member_number.'" class="btn btn-xs btn-success" target="_self"><i class="fa fa-plus" title="Buat Table"></i></a>';
					}                   
                })
				
				//->addColumn('actions', function ($row) {
				//	$html = '<a href="/installment/view/'.$row->member_number.'" class="btn btn-xs btn-warning" target="_blank"><i class="fa fa-eye" title="Lihat"></i></a>';
				//	$html .= '<a href="/installment/edit/'.$row->member_number.'" class="btn btn-xs btn-warning" target="_self"><i class="fa fa-edit" title="Edit"></i></a>';
				//	$html .= '<a href="/installment/create/pay/'.$row->member_number.'" class="btn btn-xs btn-success" target="_blank"><i class="fa fa-plus" title="Bayar"></i></a>';
				//	return $html;
				//})
				->rawColumns(['details_url','btnView','btnEdit','btnPay'])
				//->rawColumns(['actions'])
				->make(true);
				//->toJson();
        }
		return view('loan.installment.index');
	}
	
	public function create()
	{
		$start_date = Carbon::now();
		$end_date = Carbon::now()->addMonths(12);
		$tanggals = [];

		for($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
			$tanggals[] = $date->format('d-m-Y');
		}

		//$tanggals = $period->toArray();
		$customers = Customer::all();
		$loans = Loan::all();
		
		return view('loan.installment.create',compact('customers','tanggals'));
	}
	
	public function store(Request $request)
	{
		// aturan Validasi //
        $validation = Validator::make($request->all(), [
            'amount' => 'required|string|max:255',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			$loans = Loan::where('member_number',$request->member_number)->get();
			foreach($loans as $loan)
			{
				$plafon = $loan->loan_amount;
				$tenor = $loan->time_period;
				$kembang = $loan->interest_rate;
			}
			$sukuBunga = $kembang / 100;
			$pokok = $plafon / $tenor;
			$bunga = $plafon * $sukuBunga / $tenor;
			$sisaPinjaman = $plafon;
			$jumlahAngsuran = $pokok + $bunga;
			
			$installments = new Installment();
			$installments->member_number = $request->member_number;
			$installments->pay_date = $request->pay_date;
			$installments->pokok = round($pokok);
			$installments->bunga = round($bunga);
			$installments->total_angsuran = round($jumlahAngsuran);						 
			$installments->pay_method = $request->payment_method;
			$installments->status = $request->status;
			//$installments->amount = str_replace('.', '', $request->amount);
			if (!empty($request->input('amount'))){
				$installments->amount = str_replace('.', '', $request->amount);
			} else {
				$installments->amount = 0;
			}
			//$sisaPinjaman = $plafon - str_replace('.', '', $request->amount);
			//$installments->sisa = round($sisaPinjaman);
			$installments->save();
						
		}
			
			//Loan::where('member_number', '=', $request->member_number)->update(['loan_remaining' => $request-amount ]);
			
		return redirect()->back()->with('success', 'Transaction Add successfully');
	}
	
	public function edit($member_number)
	{
		$customers = Customer::where('member_number',$member_number)->get();
		$loans = Loan::where('member_number',$member_number)->get();
		return view('loan.installment.edit',compact('loans','customers'));
	}

	public function view($member_number)
	{		
		$memberNumber = $member_number;
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
			}
		}
		//$time = strtotime('10/16/2003');
		//$newformat = date('d',$time);
		//dd($newformat);
		$customers = Customer::where('member_number',$member_number)->get();
		$loans = Loan::where('member_number',$member_number)->get();
		//$loans = Customer::join('loans', 'customer.id', '=', 'loans.customer_id')
		//		->where('loans.company_id',$companyID)
		//		->where('customer.member_number',$memberNumber)
		//		->get(['customer.id','customer.name','customer.member_number','customer.address', 'loans.*']);
		return view('loan.installment.view',compact('loans','customers','memberNumber'));
		//return view('loan.installment.view')->with('loans','memberNumber', json_decode($loans,$customers,$memberNumber, true));
	}

	public function loan_update(Request $request, $member_number)
	{		
		$loan = Loan::where('member_number',$member_number)->first();
		//dd($request->contract_date);
		//$loan->member_number = $member_number;
		$loan->contract_date = $request->contract_date;
		$loan->start_month = $request->start_month;
		$loan->pay_date = $request->pay_date;
		$loan->loan_amount = str_replace('.', '',$request->loan_amount);
		$loan->time_period = $request->time_period;
		$loan->interest_rate = $request->interest_rate;
		$loan->pay_principal = str_replace('.', '',$request->pay_principal);
		$loan->pay_interest = str_replace('.', '',$request->pay_interest);
		$loan->pay_month = str_replace('.', '',$request->pay_month);
		$loan->total_principal = str_replace('.', '',$request->total_principal);
		$loan->total_interest = str_replace('.', '',$request->total_interest);
		$loan->loan_remaining = str_replace('.', '',$request->loan_remaining);		
		$loan->save();
		
		CustomerContract::where('customer_id', '=', $loan->customer_id)
		->where('contract_date',$loan->contract_date)
		->update(['contract_date' => $loan->contract_date, 'm_savings' => $request->wajib]);
		
		return redirect()->back()->with('success', 'Update successfully');
	}

	public function pay_create($member_number)
	{		
		$memberNumber = $member_number;		
		//$month = date('m');
		//$year = date('Y');
		//$today = date('Y-m-d');
		//$funds = BalanceAccount::where('member_number',$memberNumber)
		//->where('payment_type', '=', 'IN')
		//->where('journal',0)->first();
		//dd($funds);
		//$tempos = Tempo::where('member_number',$memberNumber)->first();
		//if(empty($tempos)) return redirect('/installment');		
		//if(empty($funds))
		//{
		//	$inFunds = 0;
		//} else {
		//	$inFunds = $funds->amount;
		//}
		
		//if(empty($tempos))
		//{
		//	$instTempo = 0;
		//} else {
		//	$instTempo = $tempos->total_amount;
		//}
		
		//$totalBayar = $inFunds + $instTempo;
		$customers = Customer::where('member_number',$memberNumber)->first();
		$getCustId = $customers->id;
		$payDate = date('d-m-Y', strtotime($customers->payday_date));
		$loans = Loan::where('member_number',$memberNumber)->first();
		//$installments = Installment::where('member_number',$memberNumber)->get();
		
		//$tanggals = $period->toArray();
		//$customers = Customer::all();
		//$loans = Loan::all();

		$waktu = $loans->time_period;							
		$contract = CustomerContract::where('customer_id',$getCustId)->first();
		for ($bulan = 1; $bulan <= $waktu; $bulan++) {
			//$date = Carbon::now('Asia/Jakarta');
			$date = $contract->created_at;
		//	//$date->modify('+' . $bulan . ' month');
			$tanggal = $date->modify('+' . $bulan . ' month');
			$Y = $tanggal->format("Y");
			$m = $tanggal->format("m");
			$d = $loans->pay_date;
		//	//'due_date' => $date->format('Y-m-d')
		//	//'due_date' => $date->setDate($Y, $m, $d)->format('Y-m-d'),
			Installment::create([
				'inst_to' => $bulan,
				'member_number' => $memberNumber,
				'due_date' => $tanggal->setDate($Y, $m, $d)->format('Y-m-d'),
				'pay_date' => null,
				'pay_method' => null,
				'amount' => 0,
				'late_charge' => null,
				'status' => 'UNPAID'
			]);
		}
				
		//return view('loan.installment.pay',compact('loans','customers','instTempo','inFunds'));
		//return view('loan.installment.pay',compact('installments','memberNumber'));
		//return redirect()->back()->with('success', 'Table Add successfully');
		return redirect('/installment/view/'.$memberNumber)->with("success","Table Add Successfully !");
	}

	public function full_store(Request $request, $id)
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
				$companyCode = $company->company_id;
			}
		}
		$last = Installment::where('member_number',$request->memberNumber)->orderBy('id','desc')->first();
		if (!empty($last->inst_to))
		{
			$lastID = $last->inst_to;
		}else {
			$lastID = 0;
		}
		$inst = "INST";
		$date = date("Y-m-d");
		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$hari = substr($date, 8, 2);
		$transNumber = $inst .$this->BuktiUnik(10);

		$loans = Loan::where('member_number',$request->memberNumber)->first();
		//$cek = Installment::where('member_number',$request->memberNumber)->where('status','PARTIAL')->first();
		$tempos = Tempo::where('member_number',$request->memberNumber)->where('status','=','UNPAID')->first();
		$contractNo = $loans->contract_number;
		$contracts = CustomerContract::where('contract_number',$contractNo)->first();
		
		if(empty($tempos))
		{
			$pokokTempo = 0;
			$bungaTempo = 0;
			$totalTempo = 0;
		} else {
			$pokokTempo = $tempos->amount;
			$bungaTempo = $tempos->rate_count;
			$totalTempo = $tempos->total_amount;
		}
		
		$bungaCicilan = $loans->pay_interest;
		$pokokCicilan = $loans->pay_principal;
		$totalCicilan = $pokokCicilan + $bungaCicilan;
		$totalBayar =  $totalCicilan + $totalTempo;
		
		$start = now();
		$tanggal = $start->addMonth();
		$Y = $tanggal->format("Y");
		$m = $tanggal->format("m");
		$d = $tanggal->format("d");
		
		//$sisaMonth = Installment::whereMonth(
		//	'pay_date', '=', Carbon::now()->subMonth()->month
		//)->where('member_number',$request->memberNumber)
		//->where('reminder', '>', 0)->first();
		
		//if(empty($sisaMonth))
		//{
		//	$sisanya = 0;
		//} else {
		//	$sisanya = $sisaMonth->reminder;
		//	Installment::whereMonth(
		//		'pay_date', '=', Carbon::now()->subMonth()->month
		//	)->where('member_number',$request->memberNumber)
		//	->where('reminder', '>', 0)->update(['reminder' => $sisaMonth->reminder - $sisanya]);
		//}
		
		$installments = Installment::where('id',$id)->first();
		//$installments = new Installment();
		//$installments->inst_to = $lastID + 1;
		$installments->trans_number = $transNumber;
		//$installments->member_number = $request->memberNumber;
		$installments->pay_date = $request->pay_date;
		//$installments->due_date = $tanggal->setDate($Y, $m, $d)->format('Y-m-d');
		$installments->pay_method = $request->payment_method;
		//$installments->amount = str_replace('.', '', $request->amount);			
		$payMonth = $loans->pay_month;
		$totalPrincipal = $loans->total_principal;
		$totalInterest = $loans->total_interest;
		$amount = str_replace('.', '', $request->amount);
		$sisaPokok = $loans->total_principal - $pokokCicilan;
		$sisaBunga = $loans->total_interest - $bungaCicilan;
		//$sisaCicilan = $payMonth - $amount;
		$sisaHutang = $loans->loan_remaining - $totalBayar;
		$installments->reminder = 0;
		$installments->pay_status = 'FULL';
		$installments->status = 'PAID';						
		$installments->transfer_in = str_replace('.', '', $request->transfer_in);
		//$installments->saving = str_replace('.', '', $contracts->m_savings);
		$installments->saving = str_replace('.', '', $request->wajib);
		$installments->tempo = str_replace('.', '', $totalTempo);
		//$installments->amount = $totalBayar + $sisanya;
		$installments->pay_principal = $pokokCicilan;
		$installments->pay_rates = $bungaCicilan;
		$installments->amount = $request->tagihan;		
				
		$installments->save();
		
		Loan::where('member_number', '=', $request->memberNumber)->update(['loan_remaining' => $sisaHutang, 'total_principal' => $sisaPokok,'total_interest' => $sisaBunga]);
		Tempo::where('member_number',$request->memberNumber)->update(['status' => 'PAID']);
		CustomerContract::where('contract_number',$contractNo)->update(['m_savings' => $request->wajib]);
		
		$kode = "MTS";
		$kd = $this->buktiUnik(9);
		$date = date("Y-m-d");
		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$hari = substr($date, 8, 2);
		
		$customers = Customer::where('member_number',$request->memberNumber)->first();
		$types = TransactionType::where('id',$request->transaction_type)->first();
		$balances = new BalanceAccount();
		$balances->transaction_no = $kode .$kd .$companyID .$bulan .$tahun;
		$balances->mutation_date = $request->pay_date;
		$balances->customer_id = $customers->id;
		$balances->member_number = $customers->member_number;
		$balances->from_account = '';
		$balances->to_account = '';
		$balances->branch = $companyID;
		$balances->transaction_type = 'CASH-01';
		$balances->payment_type = 'IN';
		$balances->payment_method = 'TRANSFER';		
		if (!empty($request->input('transfer_in'))){
			$balances->amount = str_replace('.', '', $request->transfer_in);
		} else {
			$balances->amount = 0;
		}
		$balances->description = 'Transfer Dana Ke Kas Kantor';
		$balances->save();
		
		Installment::where('id',$id)->update(['status' => 'PAID']);		
		
		//$updateSisa = Installment::whereMonth(
		//	'pay_date', '=', Carbon::now()->subMonth()->month
		//)->where('member_number',$request->memberNumber)
		//->where('reminder', '>', 0)->update(['reminder' => $sisaMonth->reminder - $sisanya]);
		Installment::where('member_number',$request->memberNumber)->where('inst_to',$lastID)->update(['reminder' => 0]);
		
		$contractNo = $loans->contract_number;
		$contracts = CustomerContract::where('contract_number',$contractNo)->first();
		
		$awal = 0;
		$svg = "SVG";
		$proofNumber = $svg .$this->TabunganUnik(10);
		$savings = new Savings();
		$savings->proof_number = $proofNumber;
		$savings->member_number = $request->memberNumber;
		$savings->tr_date = $request->pay_date;
		$savings->branch = $companyID; 
		$savings->tipe = 'wajib';
		$savings->status = 'setor';
		if (!empty($contracts->m_savings)){
			$savings->amount = str_replace('.', '', $contracts->m_savings);
		} else {
			$savings->amount = 0;
		}
		//$savings->amount = $contracts->m_savings ? 0 : str_replace('.', '', $contracts->m_savings);									
		$savings->created_by = auth()->user()->name;
		$savings->save();
		
		$aWajib = new Journal();
		$aWajib->account_id = '41';
		$aWajib->account_number = '1-00-01';	
		$aWajib->tipe = 'd';					
		$aWajib->proof_number = $proofNumber;
		$aWajib->transaction_date = $request->pay_date;
		$aWajib->company_id = $companyCode;
		$aWajib->description = 'Setoran Tabungan Wajib';
		$aWajib->beginning_balance = str_replace('.', '', $contracts->m_savings);
		$aWajib->nominal = str_replace('.', '', $contracts->m_savings);
		$aWajib->ending_balance = str_replace('.', '', $contracts->m_savings);
		$aWajib->save();
		
		$bWajib = new Journal();
		$bWajib->account_id = '74';
		$bWajib->account_number = '3-10-02';	
		$bWajib->tipe = 'k';					
		$bWajib->proof_number = $proofNumber;
		$bWajib->transaction_date = $request->pay_date;
		$bWajib->company_id = $companyCode;
		$bWajib->description = 'Setoran Tabungan Wajib';
		$bWajib->beginning_balance = str_replace('.', '', $contracts->m_savings);
		$bWajib->nominal = str_replace('.', '', $contracts->m_savings);
		$bWajib->ending_balance = str_replace('.', '', $contracts->m_savings);
		$bWajib->save();
		
		Savings::where('proof_number', '=', $proofNumber)->update(['journal' => 1 ]);
		
		$this->journal_full($transNumber);
		
		//return redirect()->back()->with('success', 'Angsuran Berhasil');
		//return redirect()->route('installment.print', ['id' => $id]);
		$url = URL::to('/installment/print/'.$id);
		session()->flash('url', $url);
    	return redirect()->back();
		//return response()->json(['success'=>'Angsuran Berhasil']);
		//return response()->json(
	    //  	['success' => true, 'message' => 'Angsuran Berhasil']
	    //);
	}
	
	public function free_store(Request $request, $id)
	{
		//$id = $request->free_id;
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
				$companyCode = $company->company_id;
			}
		}
		
		$inst = "INST";
		$date = date("Y-m-d");
		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$hari = substr($date, 8, 2);
		$transNumber = $inst .$this->BuktiUnik(10);
		
		$start = now();
		$tanggal = $start->addMonth();		
		$Y = $tanggal->format("Y");
		$m = $tanggal->format("m");
		$d = $tanggal->format("d");

		$loans = Loan::where('member_number',$request->memberNumber)->first();
		$contractNo = $loans->customer_id;
		$contracts = CustomerContract::where('customer_id',$contractNo)->first();

		$tempos = Tempo::where('member_number',$request->memberNumber)->where('status','=','UNPAID')->first();
		if(empty($tempos))
		{
			$pokokTempo = 0;
			$bungaTempo = 0;
			$totalTempo = 0;
		} else {
			$pokokTempo = $tempos->amount;
			$bungaTempo = $tempos->rate_count;
			$totalTempo = $tempos->total_amount;
		}
		
		$masuk = str_replace('.', '', $request->amount);
		$angsuran = $loans->pay_month + $totalTempo;		
		$bunga = $loans->pay_interest + $bungaTempo;		
		$pokok = $loans->pay_principal + $pokokTempo;
		$wajib = str_replace('.', '', $contracts->m_savings);
		$totalTagihan = $angsuran + $wajib;
		$sisa = $totalTagihan - $masuk;		
		$sisaUang = $totalTagihan - $sisa; //jumlah sama dengan uang masuk		
		$sisaUang1 = $sisaUang - $bunga; //uang masuk di kurang bunga
		$byrBunga = $sisaUang - $sisaUang1;	
		$sisaUang2 = abs($sisaUang1 - $pokok); //sisa uang di kurang pokok	
		$byrPokok = $sisaUang1 - $sisaUang2;
		$total = $sisaUang1 + $byrBunga;
		$sisaTagihan = abs($sisaUang - $total);		
		
		//dd($sisaUang,$byrBunga,$byrPokok,$wajib,$sisa, $sisaUang1,$sisaUang2,$sisaTagihan, $totalTagihan);
		
		$lieur = Installment::where('member_number',$request->memberNumber)->where('reminder', '>', 0)->first();
		if ($lieur)
		{
			$sesa = $lieur->reminder;
			$sBunga = $loans->pay_interest - $lieur->pay_rates;		
			$sPokok = $loans->pay_principal - $lieur->pay_principal;
			$installments = Installment::where('id',$id)->first();
			$installments->trans_number = $transNumber;
			$installments->member_number = $request->memberNumber;
			$installments->pay_date = $request->pay_date;
			//$installments->due_date = $tanggal->setDate($Y, $m, $d)->format('Y-m-d');
			$installments->transfer_in = str_replace('.', '', $request->transfer_in);
			$installments->pay_method = $request->payment_method;
			$installments->saving = str_replace('.', '', $contracts->m_savings);
			$installments->tempo = str_replace('.', '', $totalTempo);	
			//if ($sisa > 0)
			//{			
			//	$installments->reminder = $sisaTagihan;
			//}else{
				$installments->reminder = $sesa - $masuk;
			//}
			$installments->pay_status = 'FREE';
			$installments->status = 'PAID';
			$installments->pay_principal = $sPokok;
			$installments->pay_rates = $sBunga;
			$installments->amount = str_replace('.', '', $request->amount);	
			$installments->save();
			
			$totalBunga = $loans->total_interest - $sBunga;
			$totalPokok = $loans->total_principal - $sPokok;
			$totalBayar = $totalPokok + $totalBunga;
			$sisaBayar = $loans->loan_remaining - $masuk;
			
			Loan::where('member_number', '=', $request->memberNumber)->update(['loan_remaining' => $sisaBayar, 'total_principal' => $totalPokok,'total_interest' => $totalBunga]);		
			Tempo::where('member_number', '=', $request->memberNumber)->update(['status' => 'PAID']);		
			
			//$totalSaldoAwal = Savings::select(DB::raw('IFNULL(SUM(start_balance), 0) as total_awal'))
			//->where('member_number', $request->memberNumber)
			//->where('tipe', '=', 'wajib')->where('status', '=', 'setor')->get();
								
			//$totalSaldoAkhir = Savings::select(DB::raw('MAX(end_balance) as total_akhir'))->where('member_number', $request->memberNumber)->where('tipe', '=', 'wajib')->where('status', '=', 'setor')->first();
			//$saldoAkhir = $totalSaldoAkhir->total_akhir;					
			$customers = Customer::where('member_number',$request->memberNumber)->first();		
						
			$dPokok = new Journal();
			$dPokok->account_id = '41';
			$dPokok->account_number = '1-00-01';	
			$dPokok->tipe = 'd';					
			$dPokok->proof_number = $transNumber;
			$dPokok->transaction_date = $request->pay_date;
			$dPokok->company_id = $companyCode;
			$dPokok->description = 'Cicilan Pokok';
			$dPokok->beginning_balance = 0;
			$dPokok->nominal = $sPokok;				
			$dPokok->ending_balance = 0;
			$dPokok->save();
			
			$kPokok = new Journal();
			$kPokok->account_id = '46';
			$kPokok->account_number = '1-40-01';	
			$kPokok->tipe = 'k';					
			$kPokok->proof_number = $transNumber;
			$kPokok->transaction_date = $request->pay_date;
			$kPokok->company_id = $companyCode;
			$kPokok->description = 'Cicilan Pokok';
			$kPokok->beginning_balance = 0;
			$kPokok->nominal = $sPokok;								
			$kPokok->ending_balance = 0;
			$kPokok->save();
			
			$dBunga = new Journal();
			$dBunga->account_id = '41';
			$dBunga->account_number = '1-00-01';	
			$dBunga->tipe = 'd';					
			$dBunga->proof_number = $transNumber;
			$dBunga->transaction_date = $request->pay_date;
			$dBunga->company_id = $companyCode;
			$dBunga->description = 'Bunga Pinjaman';
			$dBunga->beginning_balance = 0;	
			$dBunga->nominal = $sBunga;
			$dBunga->ending_balance = 0;
			$dBunga->save();
			
			$kBunga = new Journal();
			$kBunga->account_id = '91';
			$kBunga->account_number = '4-10-05';	
			$kBunga->tipe = 'k';					
			$kBunga->proof_number = $transNumber;
			$kBunga->transaction_date = $request->pay_date;
			$kBunga->company_id = $companyCode;
			$kBunga->description = 'Bunga Pinjaman';
			$kBunga->beginning_balance = 0;
			$kBunga->nominal = $sBunga;
			$kBunga->ending_balance = 0;
			$kBunga->save();					
			
			//Savings::where('proof_number', '=', $proofNumber)->update(['journal' => 1 ]);					
			
			$duitSisa = $sesa - $masuk;
			Installment::where('id',$lieur->id)->update(['reminder' => $duitSisa]);		
			Installment::where('id',$request->free_id)->update(['reminder' => 0,'journal' => 1]);		
								
		} else {
		
			$install = Installment::where('id',$id)->first();
			$a = $install->amount;
			$b = $install->reminder;
			$c = $totalTagihan - $a - $b;			
			
			if ($masuk < $c)
			{	
				$installments = Installment::where('id',$id)->first();
				$installments->trans_number = $transNumber;
				$installments->member_number = $request->memberNumber;
				$installments->pay_date = $request->pay_date;
				//$installments->due_date = $tanggal->setDate($Y, $m, $d)->format('Y-m-d');
				$installments->transfer_in = str_replace('.', '', $request->transfer_in);
				$installments->pay_method = $request->payment_method;
				$installments->saving = str_replace('.', '', $contracts->m_savings);
				$installments->tempo = str_replace('.', '', $totalTempo);	
				//if ($sisa > 0)
				//{			
				//	$installments->reminder = $sisaTagihan;
				//}else{
					$installments->reminder = $sisaUang2;
				//}
				$installments->pay_status = 'FREE';
				$installments->status = 'PAID';
				$installments->pay_principal = $byrPokok;
				$installments->pay_rates = $byrBunga;
				$installments->amount = str_replace('.', '', $request->amount);	
				$installments->save();
				
				$totalBunga = $loans->total_interest - $bunga;
				$totalPokok = $loans->total_principal - $sisaUang1;
				$totalBayar = $totalPokok + $totalBunga;
				$sisaBayar = $loans->loan_remaining - $masuk;				
				
				$installments = Installment::where('id',$id)->first();
				$last = Installment::where('member_number',$request->memberNumber)->orderBy('id','desc')->first();
				$lastID = $last->inst_to;
				$ids = $last->id;
				$curDate = Carbon::now();
				$daysToAdd = 5;
				$curDate = $curDate->addDays($daysToAdd);
				Installment::create([
					//'inst_to' => $lastID+1,
					'inst_to' => $request->inst_to,
					'member_number' => $request->memberNumber,
					//'due_date' => $payDate->setDate($Y, $m, $d)->format('Y-m-d'),
					'due_date' => $installments->due_date,
					'pay_date' => null,
					'pay_method' => null,
					'amount' => 0,
					'reminder' => 0,
					'late_charge' => null,
					'status' => 'PARTIAL',
					'journal' => 0
				]);
				
				Loan::where('member_number', '=', $request->memberNumber)->update(['loan_remaining' => $sisaBayar, 'total_principal' => $totalPokok,'total_interest' => $totalBunga]);		
				Tempo::where('member_number', '=', $request->memberNumber)->update(['status' => 'PAID']);		
				
				//$totalSaldoAwal = Savings::select(DB::raw('IFNULL(SUM(start_balance), 0) as total_awal'))
				//->where('member_number', $request->memberNumber)
				//->where('tipe', '=', 'wajib')->where('status', '=', 'setor')->get();
									
				//$totalSaldoAkhir = Savings::select(DB::raw('MAX(end_balance) as total_akhir'))->where('member_number', $request->memberNumber)->where('tipe', '=', 'wajib')->where('status', '=', 'setor')->first();
				//$saldoAkhir = $totalSaldoAkhir->total_akhir;					
				$customers = Customer::where('member_number',$request->memberNumber)->first();		
				
				$awal = 0;
				$svg = "SVG";
				$proofNumber = $svg .$this->TabunganUnik(10);
				$savings = new Savings();
				$savings->proof_number = $proofNumber;
				$savings->member_number = $request->memberNumber;
				$savings->tr_date = $request->pay_date;
				$savings->branch = $customers->branch; 
				$savings->tipe = 'wajib';
				$savings->status = 'setor';
				$savings->amount = str_replace('.', '', $contracts->m_savings);									
				$savings->created_by = auth()->user()->name;
				$savings->save();		
				
				$dPokok = new Journal();
				$dPokok->account_id = '41';
				$dPokok->account_number = '1-00-01';	
				$dPokok->tipe = 'd';					
				$dPokok->proof_number = $transNumber;
				$dPokok->transaction_date = $request->pay_date;
				$dPokok->company_id = $companyCode;
				$dPokok->description = 'Cicilan Pokok';
				$dPokok->beginning_balance = 0;
				$dPokok->nominal = $byrPokok;				
				$dPokok->ending_balance = 0;
				$dPokok->save();
				
				$kPokok = new Journal();
				$kPokok->account_id = '46';
				$kPokok->account_number = '1-40-01';	
				$kPokok->tipe = 'k';					
				$kPokok->proof_number = $transNumber;
				$kPokok->transaction_date = $request->pay_date;
				$kPokok->company_id = $companyCode;
				$kPokok->description = 'Cicilan Pokok';
				$kPokok->beginning_balance = 0;
				$kPokok->nominal = $byrPokok;								
				$kPokok->ending_balance = 0;
				$kPokok->save();
				
				$dBunga = new Journal();
				$dBunga->account_id = '41';
				$dBunga->account_number = '1-00-01';	
				$dBunga->tipe = 'd';					
				$dBunga->proof_number = $transNumber;
				$dBunga->transaction_date = $request->pay_date;
				$dBunga->company_id = $companyCode;
				$dBunga->description = 'Bunga Pinjaman';
				$dBunga->beginning_balance = 0;	
				$dBunga->nominal = $bunga;
				$dBunga->ending_balance = 0;
				$dBunga->save();
				
				$kBunga = new Journal();
				$kBunga->account_id = '91';
				$kBunga->account_number = '4-10-05';	
				$kBunga->tipe = 'k';					
				$kBunga->proof_number = $transNumber;
				$kBunga->transaction_date = $request->pay_date;
				$kBunga->company_id = $companyCode;
				$kBunga->description = 'Bunga Pinjaman';
				$kBunga->beginning_balance = 0;
				$kBunga->nominal = $bunga;
				$kBunga->ending_balance = 0;
				$kBunga->save();		
				
				$aWajib = new Journal();
				$aWajib->account_id = '41';
				$aWajib->account_number = '1-00-01';	
				$aWajib->tipe = 'd';					
				$aWajib->proof_number = $proofNumber;
				$aWajib->transaction_date = $request->pay_date;
				$aWajib->company_id = $companyCode;
				$aWajib->description = 'Setoran Tabungan Wajib';
				$aWajib->beginning_balance = str_replace('.', '', $contracts->m_savings);
				$aWajib->nominal = str_replace('.', '', $contracts->m_savings);
				$aWajib->ending_balance = str_replace('.', '', $contracts->m_savings);
				$aWajib->save();
				
				$bWajib = new Journal();
				$bWajib->account_id = '74';
				$bWajib->account_number = '3-10-02';	
				$bWajib->tipe = 'k';					
				$bWajib->proof_number = $proofNumber;
				$bWajib->transaction_date = $request->pay_date;
				$bWajib->company_id = $companyCode;
				$bWajib->description = 'Setoran Tabungan Wajib';
				$bWajib->beginning_balance = str_replace('.', '', $contracts->m_savings);
				$bWajib->nominal = str_replace('.', '', $contracts->m_savings);
				$bWajib->ending_balance = str_replace('.', '', $contracts->m_savings);
				$bWajib->save();
				
				Savings::where('proof_number', '=', $proofNumber)->update(['journal' => 1 ]);		
				
				$kode = "MTS";
				$kd = $this->buktiUnik(9);
				$date = date("Y-m-d");
				$tahun = substr($date, 0, 4);
				$bulan = substr($date, 5, 2);
				$hari = substr($date, 8, 2);
				
				$customers = Customer::where('member_number',$request->memberNumber)->first();
				$types = TransactionType::where('id',$request->transaction_type)->first();
				$balances = new BalanceAccount();
				$balances->transaction_no = $kode .$kd .$companyID .$bulan .$tahun;
				$balances->mutation_date = $request->pay_date;
				$balances->customer_id = $customers->id;
				$balances->member_number = $customers->member_number;
				$balances->from_account = '';
				$balances->to_account = '';
				$balances->branch = $companyID;
				$balances->transaction_type = 'CASH-01';
				$balances->payment_type = 'IN';
				$balances->payment_method = 'TRANSFER';
				if (!empty($request->input('transfer_in'))){
					$balances->amount = str_replace('.', '', $request->transfer_in);
				} else {
					$balances->amount = 0;
				}
				$balances->description = 'Transfer Dana Ke Kas Kantor';
				$balances->save();
				
				Installment::where('id',$request->free_id)->update(['journal' => 1]);
			}				
		}			
		
		//$reminds = Installment::where('member_number',$request->memberNumber)
		//->where('reminder', '>', 0)->max('id');
		//->where('reminder', '>', 0)->first();
		//$ids = $reminds;
		//if($ids <> $id){
		//	Installment::where('id',$reminds)->update(['reminder' => 0,'journal' => 1]);		
		//}else{
		//	Installment::where('id',$id)->update(['reminder' => $sisa, 'journal' => 1]);			
		//}
		
		//$sisa1 = $masuk - $bunga;		
		//$sisa2 = $sisa1 - $pokok;
		//$totPokok = $pokok - $sisa2;		
		
		//return redirect()->back();
		return redirect()->route('installment.print', ['id' => $id]);
		//return response()->json(['success'=>'Installment saved successfully!']);
	}

	public function update(Request $request, $id)
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
			}
		}
		
		$query = \DB::table('installment')->whereDate('due_date', date('Y-m-d'))
				  ->select(\DB::raw('SUBSTRING(trans_number, 8, 1) as kode'))
				  ->get();			

		if ($query->count() > 0) {
		 foreach ($query as $q) {
		  //$no = ((int)$q['kode'])+1;
		  $no = $q->kode+1;
		  $kd = sprintf("%04s", $no);
		 }
		} else {
		 $kd = "0001";
		}
		
		$getMember = Installment::where('id',$id)->first();
		$member_number = $getMember->member_number;
		
		$inst = "INST";
		$date = date("Y-m-d");
		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$hari = substr($date, 8, 2);
		$transNumber = $inst .$this->BuktiUnik(10);
		//$customers = Customer::where('member_number',$member_number)->get();
		$loans = Loan::where('member_number',$member_number)->first();
		$installments = Installment::where('id',$id)->where('status','UNPAID')->first();
		$installments->trans_number = $transNumber;
		$installments->member_number = $member_number;
		$installments->pay_date = $request->pay_date;
		$installments->pay_method = $request->payment_method;		
		$installments->pay_status = $request->pay_status;
		$installments->full_free = $request->full_free;
		$installments->status = $request->status;
		//$installments->pay_principal = str_replace('.', '', $request->pay_principal);
		//$installments->pay_rates = str_replace('.', '', $request->pay_rates);
		if (!empty($request->input('amount'))){
			$installments->amount = str_replace('.', '', $request->amount);
		} else {
			$installments->amount = 0;
		}		
		$installments->created_by = auth()->user()->name;
		
		$installments->save();		
		
		$bayar = str_replace('.', '', $request->amount);
		//$saldo = $loans->saldo + $sisa;
		$sisaHutang = $loans->loan_remaining - $bayar;
		Loan::where('member_number', '=', $member_number)->update(['loan_remaining' => $sisaHutang]);
		
		//$tempo = Carbon::parse($loan->jatuh_tempo);
        //$today = Carbon::now('Asia/Jakarta');

        //if ($tempo < $today) {
        //    $selisih = $tempo->diffInDays($today);
        //    $telat_hari = $selisih;
        //    $denda = 1000 * $selisih;
        //} else {
        //    $telat_hari = 0;
        //    $denda = 0;
        //}
		
		$last = Installment::orderBy('id','desc')->first();
		$lastID = $last->id;
		$dSetors = Installment::where('trans_number',$transNumber)->groupBy('trans_number')->first();
		$totalPartial = $dSetors->sum('amount');
		$sisa = $loans->pay_month - $dSetors->amount;
		if ($bayar >= $sisa )
		{
			//$xSavings = $dSetors->replicate();
			//$xSavings->inst_to = $lastID + 1;
			//$xSavings->pay_method = null;
			//$xSavings->pay_date = null;
			//$xSavings->status = 'UNPAID';
			//$xSavings->amount = $sisa;
			//$xSavings->save();
			return redirect()->back();
		}else{
			Installment::where('trans_number','=',$transNumber)->updateOrCreate([
				'inst_to' => $lastID + 1,
				'trans_number' => $transNumber,
				'member_number' => $dSetors->member_number,				
				'pay_date' => null,
				'pay_method' => null,
				'late_charge' => null,
				'due_date' => $dSetors->due_date,
				'amount' => $sisa,
				'status' => 'UNPAID',
				'journal' => 0,
				'created_by' => null				
			]);
		}
		
		return redirect()->back()->with('success', 'Transaction Add successfully');
	}
	
	public function posting(Request $request, $id)
	{
		$transNumber = $id;
		$installments = Installment::where('trans_number',$transNumber)->get();
		foreach($installments as $installment)
		{
			$proof = $installment->trans_number;
			$instNo = $installment->inst_to;
			$transDate = $installment->pay_date;
			$memberNumber = $installment->member_number;
			$amount = $installment->amount;
		}
		
		$customers = Customer::where('member_number',$memberNumber)->first();
		$loans = Loan::where('member_number',$memberNumber)->first();
		$jumlahAngsuran = number_format($amount, 0, ',' , '.');
		$jumlahPinjaman = number_format($loans->loan_amount, 0, ',' , '.');
		Posting::create([
			'proof_number' => $proof,
			'trans_date' => $transDate,
			'branch' => $loans->company_id,
			'description' => "Bayar angsuran pinjaman ke#$instNo Bapak $customers->name sebesar Rp. $jumlahAngsuran dengan no kontrak $loans->contract_number jumlah pinjaman Rp. $jumlahPinjaman",
			'nominal' => $amount,
			'journal' => 0
		]);
		
		Posting::create([
			'proof_number' => $proof,
			'trans_date' => $transDate,
			'branch' => $loans->company_id,
			'description' => "Bayar angsuran pokok Bapak $customers->name sebesar Rp. $loans->pay_pricipal dengan no kontrak $loans->contract_number jumlah pinjaman Rp. $jumlahPinjaman",
			'nominal' => $loans->pay_principal,
			'journal' => 0
		]);
		
		Posting::create([
			'proof_number' => $proof,
			'trans_date' => $transDate,
			'branch' => $loans->company_id,
			'description' => "Bayar angsuran bunga Bapak $customers->name sebesar Rp. $loans->pay_interest dengan no kontrak $loans->contract_number jumlah pinjaman Rp. $jumlahPinjaman",
			'nominal' => $loans->pay_interest,
			'journal' => 0
		]);
		
		Installment::where('trans_number', '=', $transNumber)->update(['posting' => 1 ]);
				
		return redirect()->back()->with('success', 'Posting successfully');
		
	}
	
	public function journal_full($id)
	{
		$currentURL = URL::previous();
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->company_id;
			}
		}
			
		$employees = Employee::where('user_id',auth()->user()->id)->first();				
		$akuns = Installment::where('trans_number',$id)->get();				
		
		foreach($akuns as $key => $akun) 
		{
			$loans = Loan::where('member_number',$akun->member_number)->get();
			//dd($loans);
			foreach($loans as $loan)
			{
				$installments = Installment::where('member_number',$loan->member_number)->first();
				$tempos = Tempo::where('member_number',$loan->member_number)->where('status','=','UNPAID')->first();
				if(empty($tempos))
				{
					$pokokTempo = 0;
					$bungaTempo = 0;
				} else {
					$pokokTempo = $tempos->amount;
					$bungaTempo = $tempos->rate_count;					
				}
				$pokok = $loan->pay_principal + $pokokTempo;
				$bunga = $loan->pay_interest + $bungaTempo;
				
				//$sisaMonth = Installment::whereMonth(
				//	'pay_date', '=', Carbon::now()->subMonth()->month
				//)->where('member_number',$loan->member_number)
				//->where('reminder', '>', 0)->first();
				
				//if(empty($sisaMonth))
				//{
				//	$sisanya = 0;
				//} else {
				//	$sisanya = $sisaMonth->reminder;
				//}
				
				$dPokok = new Journal();
				$dPokok->account_id = '41';
				$dPokok->account_number = '1-00-01';	
				$dPokok->tipe = 'd';					
				$dPokok->proof_number = $akun->trans_number;
				$dPokok->transaction_date = $akun->pay_date;
				$dPokok->company_id = $companyID;
				$dPokok->description = 'Cicilan Pokok';
				$dPokok->beginning_balance = 0;
				//$dPokok->nominal = $pokok + $sisanya;
				$dPokok->nominal = $pokok;
				$dPokok->ending_balance = 0;
				$dPokok->save();
				
				$kPokok = new Journal();
				$kPokok->account_id = '46';
				$kPokok->account_number = '1-40-01';	
				$kPokok->tipe = 'k';					
				$kPokok->proof_number = $akun->trans_number;
				$kPokok->transaction_date = $akun->pay_date;
				$kPokok->company_id = $companyID;
				$kPokok->description = 'Cicilan Pokok';
				$kPokok->beginning_balance = 0;
				//$kPokok->nominal = $pokok + $sisanya;
				$kPokok->nominal = $pokok;
				$kPokok->ending_balance = 0;
				$kPokok->save();

				$dBunga = new Journal();
				$dBunga->account_id = '41';
				$dBunga->account_number = '1-00-01';	
				$dBunga->tipe = 'd';					
				$dBunga->proof_number = $akun->trans_number;
				$dBunga->transaction_date = $akun->pay_date;
				$dBunga->company_id = $companyID;
				$dBunga->description = 'Bunga Pinjaman';
				$dBunga->beginning_balance = 0;
				$dBunga->nominal = $bunga;
				$dBunga->ending_balance = 0;
				$dBunga->save();
				
				$kBunga = new Journal();
				$kBunga->account_id = '91';
				$kBunga->account_number = '4-10-05';	
				$kBunga->tipe = 'k';					
				$kBunga->proof_number = $akun->trans_number;
				$kBunga->transaction_date = $akun->pay_date;
				$kBunga->company_id = $companyID;
				$kBunga->description = 'Bunga Pinjaman';
				$kBunga->beginning_balance = 0;
				$kBunga->nominal = $bunga;
				$kBunga->ending_balance = 0;
				$kBunga->save();
				
			}
			Installment::where('trans_number', '=', $akun->trans_number)->update(['journal' => 1 ]);			
			//return redirect()->back()->with('success', 'Journal Wajib Successfully');								
		}
				
		return redirect($currentURL)->with("success","Journal successfully !");
	}
	
	public function journal_free($id)
	{
		$currentURL = URL::previous();
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->company_id;
			}
		}
			
		$employees = Employee::where('user_id',auth()->user()->id)->first();				
		$akuns = Installment::where('trans_number',$id)->get();		
		
		foreach($akuns as $key => $akun) 
		{
			$loans = Loan::where('member_number',$akun->member_number)->get();
			//dd($loans);
			foreach($loans as $loan)
			{
				$installments = Installment::where('member_number',$loan->member_number)->first();				
				$tempos = Tempo::where('member_number',$loan->member_number)->where('status','=','UNPAID')->first();
				
				if(empty($tempos))
				{
					$pokokTempo = 0;
					$bungaTempo = 0;
				} else {
					$pokokTempo = $tempos->amount;
					$bungaTempo = $tempos->rate_count;
				}
				
				$masuk = $installments->amount;
				$angsuran = $loan->pay_month;
				$bunga = $loan->pay_interest + $bungaTempo;
				$pokok = $loan->pay_principal + $pokokTempo;					
				$sisa1 = $masuk - $bunga;				
				$sisa2 = $pokok - $sisa1;
				$bayarPokok = $pokok - $sisa2;
				
				$dPokok = new Journal();
				$dPokok->account_id = '41';
				$dPokok->account_number = '1-00-01';	
				$dPokok->tipe = 'd';					
				$dPokok->proof_number = $akun->trans_number;
				$dPokok->transaction_date = $akun->pay_date;
				$dPokok->company_id = $companyID;
				$dPokok->description = 'Cicilan Pokok';
				$dPokok->beginning_balance = 0;
				$dPokok->nominal = $sisa1;				
				$dPokok->ending_balance = 0;
				$dPokok->save();
				
				$kPokok = new Journal();
				$kPokok->account_id = '46';
				$kPokok->account_number = '1-40-01';	
				$kPokok->tipe = 'k';					
				$kPokok->proof_number = $akun->trans_number;
				$kPokok->transaction_date = $akun->pay_date;
				$kPokok->company_id = $companyID;
				$kPokok->description = 'Cicilan Pokok';
				$kPokok->beginning_balance = 0;
				$kPokok->nominal = $sisa1;								
				$kPokok->ending_balance = 0;
				$kPokok->save();
				
				$dBunga = new Journal();
				$dBunga->account_id = '41';
				$dBunga->account_number = '1-00-01';	
				$dBunga->tipe = 'd';					
				$dBunga->proof_number = $akun->trans_number;
				$dBunga->transaction_date = $akun->pay_date;
				$dBunga->company_id = $companyID;
				$dBunga->description = 'Bunga Pinjaman';
				$dBunga->beginning_balance = 0;	
				$dBunga->nominal = $bunga;
				$dBunga->ending_balance = 0;
				$dBunga->save();
				
				$kBunga = new Journal();
				$kBunga->account_id = '91';
				$kBunga->account_number = '4-10-05';	
				$kBunga->tipe = 'k';					
				$kBunga->proof_number = $akun->trans_number;
				$kBunga->transaction_date = $akun->pay_date;
				$kBunga->company_id = $companyID;
				$kBunga->description = 'Bunga Pinjaman';
				$kBunga->beginning_balance = 0;
				$kBunga->nominal = $bunga;
				$kBunga->ending_balance = 0;
				$kBunga->save();
				
			}
			
			Installment::where('trans_number', '=', $akun->trans_number)->update(['journal' => 1 ]);
			Tempo::where('member_number',$loan->member_number)->update(['status' => 'PAID']);
			//return redirect()->back()->with('success', 'Journal Wajib Successfully');								
		}
				
		return redirect($currentURL)->with("success","Journal successfully !");
	}
	
	public function journal($id)
	{
		$currentURL = URL::previous();
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->company_id;
			}
		}
			
		$employees = Employee::where('user_id',auth()->user()->id)->first();				
		$cekStatus = Installment::where('trans_number',$id)->first();
		if ($cekStatus->pay_status  === 'FULL')
		{
			echo "Bayar Full";
		} else if($cekStatus->pay_status === 'FREE') 
		{
			echo "Bayar FREE";
		} else {
			echo "Data tidak di temukan";
		}
		$akuns = Installment::where('trans_number',$id)->get();
	}
	
	public function search(Request $request)
	{
		//if(empty($request->search)) return redirect('installment');
		
		$customers = Customer::where([
		   ['name', '!=', Null],
		   [function ($query) use ($request) {
			 if(($search = $request->search)) {
				$query->orWhere('name', 'LIKE', '%' . $search . '%')
				->orWhere('reg_number', 'LIKE', '%' . $search . '%')
				//->orWhere('member_id', 'LIKE', '%' . $search . '%')
				->get();
			 }
		   }]
		])->orderBy("id","desc")->paginate(10);
		
		$angsurans = Installment::all();
		if(empty($request->search)) return redirect('installment')->with('success', "data tidak ditemukan");
		
		return view('loan.installment.index',compact('angsurans','customers'));
	}
	
	public function printAll(Request $request, $memberNumber)
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
        $pdf::Cell(0, 10, "KWITANSI ANGSURAN", 0, 2, 'C');
		$pdf::Ln();	
		
		$customer = Customer::where('member_number',$memberNumber)->orderBy('id', 'asc')->first();
		
		$pdf::SetFont('Arial', 'B', 12);
		$pdf::MultiCell(40, 10, "NO. NASABAH", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, $memberNumber , 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::Ln();
		$pdf::MultiCell(40, 10, "NAMA NASABAH", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, $customer->name, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();
		
        $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(10, 8, "NO", 1, 0, 'C');
        $pdf::Cell(40, 8, "NO. TRANSAKSI", 1, 0, 'C');
		$pdf::Cell(30, 8, "TGL. BAYAR", 1, 0, 'C');
		$pdf::Cell(40, 8, "TFR MASUK", 1, 0, 'C');
		$pdf::Cell(40, 8, "TEMPO", 1, 0, 'C');
		$pdf::Cell(40, 8, "TABUNGAN", 1, 0, 'C');
		$pdf::Cell(40, 8, "ANGSURAN", 1, 0, 'C');
        $pdf::Cell(40, 8, "KETERANGAN", 1, 0, 'C');
        $pdf::Ln();
		
		//$installments = Installment::where('member_number',$memberNumber)->first();								
		$installments = Installment::where('member_number',$memberNumber)->orderBy('pay_date','desc')->get();
		//$total = $installments->sum('amount');
		
		foreach($installments as $key => $item)
		{			
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(10, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(40, 8, $item->trans_number, 1, 0, 'C');
	        $pdf::Cell(30, 8, $item->pay_date ? date('d-m-Y', strtotime($item->pay_date)) : '', 1, 0, 'C');
			$pdf::Cell(40, 8, "Rp. ".number_format($item->transfer_in, 0, ',', '.').",-", 1, 0, 'R');
			$pdf::Cell(40, 8, "Rp. ".number_format($item->tempo, 0, ',', '.').",-", 1, 0, 'R');
			$pdf::Cell(40, 8, "Rp. ".number_format($item->saving, 0, ',', '.').",-", 1, 0, 'R');
			$pdf::Cell(40, 8, "Rp. ".number_format($item->amount, 0, ',', '.').",-", 1, 0, 'R');
	        $pdf::Cell(40, 8, $item->status, 1, 0, 'L');
	        $pdf::Ln();    	
			$jumlah = $item->amount;
		}
		
		$total = Installment::where('member_number',$memberNumber)->get()->sum('amount');
		$pdf::SetFont('Arial', 'B', 10);
		$pdf::Cell(280, 8, "Rp. ".number_format($total, 0, ',', '.').",-", 1, 0, 'R');
		$pdf::Ln();
			
		$pdf::SetFont('Arial', 'B', 10);
		$pdf::Cell(280, 8, "TERBILANG :", 1, 0, 'L');
		$pdf::Ln();
		
		$pdf::SetFont('Arial', 'B', 8);
		$pdf::Cell(280, 10, strtoupper(Terbilang::bilang($total)) . "RUPIAH", 1, 0, 'R');
		$pdf::Ln();
		
        // Footer
        $pdf::SetY(179);
        $pdf::SetX(165);
        $pdf::SetFont('Arial','I',8);
        $pdf::Cell(0,10,"Dicetak Oleh Akuntan : ". $profileName ." Pada ".date("d-m-Y H:i:s")
        ." WIB", 0, 0, 'C');
		
		ob_end_clean();
		return $pdf::Output('pembelian.pdf','I');
	}
	
	public function print($id)
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
        
        $pdf::AddPage('P', 'A4');
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
        $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(0, 10, "KWITANSI ANGSURAN", 0, 2, 'C');
		$pdf::Ln();		
		
		$installment = Installment::where('id',$id)->orderBy('id', 'asc')->first();
		if (empty($installment))
		{
			return redirect('/installment');
		}else{
		$member = $installment->member_number;
		$loans = Loan::where('member_number',$member)->first();
		$nasabah = Customer::where('member_number',$member)->orderBy('id', 'asc')->first();
		
		$pdf::SetFont('Arial', 'B', 12);
		$pdf::MultiCell(30, 8, "Tanggal", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(45, 8, $installment->pay_date ? date('d-m-Y', strtotime($installment->pay_date)) : '' , 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
		$pdf::MultiCell(30, 8, "Nasabah", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(65, 8, $nasabah->name, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();
		$pdf::MultiCell(30, 8, "Tenor", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(45, 8, $nasabah->time_period, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');				
		$pdf::MultiCell(30, 8, "Perusahaan", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(65, 8, $nasabah->company_name, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
        $pdf::Ln();
		$pdf::MultiCell(40, 8, "Transfer Masuk", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(140, 8, "Rp. ".number_format($installment->transfer_in, 0, ',', '.').",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();
		$pdf::MultiCell(40, 8, "Tempo", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(140, 8, "Rp. ".number_format($installment->tempo, 0, ',', '.').",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();
		$pdf::MultiCell(40, 8, "Angsuran", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(140, 8, "Rp. ".number_format($loans->pay_month, 0, ',', '.').",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();		
		$pdf::MultiCell(40, 8, "Tabungan", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(140, 8, "Rp. ".number_format($installment->saving, 0, ',', '.').",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();
		//$byrTempo = number_format($installment->tempo, 0, ',', '.');
		//$byrLoan = number_format($installment->amount, 0, ',', '.');
		//$byrSaving = number_format($installment->saving, 0, ',', '.');
		$byrTotal = $installment->tempo + $loans->pay_month + $installment->saving;
		//$byrTotal = $installment->tempo +  $installment->amount + $installment->saving;
		
		$pdf::MultiCell(40, 8, "Total", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(140, 8, "Rp. ".number_format($byrTotal, 0, ',', '.').",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();
				
		$totalJendral = $installment->transfer_in - $byrTotal;
		//$totalJendral = $installment->tempo +  $installment->amount + $installment->saving;
		
		$pdf::MultiCell(40, 8, "Sisa", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(140, 8, "Rp. ".number_format($totalJendral, 0, ',', '.').",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();
		$pdf::MultiCell(40, 8, "Angsuran Ke", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(140, 8, $installment->inst_to, 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();
		
		$pdf::SetFont('Arial', 'B', 10);
		$pdf::Cell(190, 8, "Rp. ".number_format($totalJendral, 0, ',', '.').",-", 1, 0, 'R');
		$pdf::Ln();
		
		$pdf::SetFont('Arial', 'B', 10);
		$pdf::Cell(190, 8, "TERBILANG :", 1, 0, 'L');
		$pdf::Ln();
		
		$pdf::SetFont('Arial', 'B', 8);
		$pdf::Cell(190, 8, strtoupper(Terbilang::bilang($totalJendral)) . "RUPIAH", 1, 0, 'R');
		$pdf::Ln();
		
		
        // Footer
        $pdf::SetY(179);
        $pdf::SetX(30);
        $pdf::SetFont('Arial','I',8);        
        $pdf::Cell(0,10, $kelurahan->nama .",".date("d-m-Y"), 0, 0, 'C');
        $pdf::Ln();

        $pdf::SetY(190);
        $pdf::SetX(20);
        $pdf::SetFont('Arial','I',8);        
        $pdf::Cell(0,10, ($nasabah->name));
        $pdf::Ln();
        	
        $pdf::SetY(190);
        $pdf::SetX(150);
        $pdf::SetFont('Arial','I',8);        
        $pdf::Cell(0,10, (auth()->user()->name));
		
		ob_end_clean();
		return $pdf::Output('angsuran.pdf','I');
		}
	}
	
	public function metode_flat($jumlahPinjaman, $jangkaWaktu, $sukuBunga) {
		$data = [];
		$sukuBunga = $sukuBunga / 100;
		$pokok = $jumlahPinjaman / $jangkaWaktu;
		$bunga = $jumlahPinjaman * $sukuBunga / $jangkaWaktu;
		$sisaPinjaman = $jumlahPinjaman;
		$jumlahAngsuran = $pokok + $bunga;

		for($i = 0; $i < $jangkaWaktu; $i++) {
			$sisaPinjaman -= $pokok;
			array_push($data, [
				"no"                => $i + 1,
				"pokok"             => round($pokok),
				"bunga"             => round($bunga),
				"jumlahAngsuran"    => round($jumlahAngsuran),
				"sisaPinjaman"      => round($sisaPinjaman)
			]);
		}
		return $data;
	}
	
    public function hitung_anuitas($besar_pinjaman, $jangka, $bunga)
	{
		$bunga_bulan      = ($bunga/12)/100;
		$pembagi          = 1-(1/pow(1+$bunga_bulan,$jangka));
		$hasil            = $besar_pinjaman/($pembagi/$bunga_bulan);
		return $hasil;
	}
	
	public function hitung_flat($besar_pinjaman, $jangka, $bunga)
	{
		$cicilan_bulan    = $besar_pinjaman / $jangka;
		$bunga_bulan      = $bunga/12/100;	  
		$hasil            = $cicilan_bulan + $bunga_bulan;
		return $hasil;
	}	
	
	public  function orderUnik($length) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomOrder = '';
        for ($i = 0; $i < $length; $i++) {
            $randomOrder .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomOrder;
    }
	
	public  function BuktiUnik($length) 
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomOrder = '';
        for ($i = 0; $i < $length; $i++) {
            $randomOrder .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomOrder;
    }
	
	public function TabunganUnik($length) 
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomOrder = '';
        for ($i = 0; $i < $length; $i++) {
            $randomOrder .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomOrder;
    }
	
	public function loadInstallment(Request $request) 
    {
		$loans = App\Models\Loan::where('member_number',$memberNumber)->first();
		$tempos = App\Models\Tempo::where('member_number',$memberNumber)->where('status','=','UNPAID')->first();
		$byrCicilan = $loans->pay_month;
		$getCutsId = $loans->customer_id;
		$last = App\Models\Installment::where('member_number',$memberNumber)->orderBy('id','desc')->first();
		if (!empty($last->inst_to))
		{
			$lastID = $last->inst_to;
		}else {
			$lastID = 0;
		}
		$sisaBayaran = App\Models\Installment::where('member_number',$memberNumber)->where('reminder', '>', 0)->first();
		$sisa = !empty($sisaBayaran->reminder) ? $sisaBayaran->reminder : 0;
		$kontrak = App\Models\CustomerContract::where('customer_id',$getCutsId)->first();
		$wajib = $kontrak->m_savings;
		$byrTempo = !empty($tempos->total_amount) ? $tempos->total_amount : 0;
		$totalBayar = $byrCicilan + $byrTempo + $kontrak->m_savings + $sisa;
		
		//$response = [
		//	'byrCicilan' => $byrCicilan,
		//	'sisa' => $sisa,
		//	'byrTempo' => $byrTempo,
		//	'wajib' => $wajib,
		//	'totalBayar' => $totalBayar
		//];

		//return response()->json($response);
		
		$data = array(
			'byrCicilan' => $byrCicilan,
			'sisa' => $sisa,
			'byrTempo' => $byrTempo,
			'wajib' => $wajib,
			'totalBayar' => $totalBayar
		);

		echo json_encode($data);
		
	}

	public function getDetailsData($member_number)
	{
	    $cicilan = Installment::where('member_number',$member_number)->orderBy('due_date','asc')->orderBy('inst_to','asc')->get();
	    //$cicilan = Installment::where('loan_id',$loan_id)->get();

	    return Datatables::of($cicilan)->make(true);
	    //return datatables()->of($cicilan)->make(true);
	    //$cicilan = DB::table('installment')->select('id', 'member_number', 'inst_to', 'due_date', 'pay_date', 'pay_method','status','amount','reminder')
        //->where('member_number','=',$member_number)
        //->get();
        //return $cicilan;
	}
	
}
