<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Loan;
use App\Models\Savings;
use App\Models\Tempo;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Payment;
use App\Models\Installment;
use App\Models\TransactionType;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Journal;
use App\Models\AccountGroup;
use App\Helper\Terbilang;
use Carbon\Carbon;
use Validator;
use DB;
use DPDF;
use TPDF;
use Notification;
use Auth;

class PaymentController extends Controller
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
				
				//$payments = Payment::join('customer', 'customer.trans_code', '=' ,'payments.cust_id')					
                //     ->select('payments.*', 'customer.name') 
                //     ->get();
				$payments = Payment::select('*');
			}else{
				$payments = Payment::where('branch',$companyID)->orderBy("id","asc")->get();
			}					
			
			return datatables()->of($payments)				
				->editColumn('pay_date', function ($tgl) 
				{
					return date('d-m-Y', strtotime($tgl->pay_date) );
				})
				->addColumn('edit', function ($erow) {                    
					if($erow->journal == 1) {
						$btnEdit = '<a href="/transaction/payment/edit/'.$erow->id.'" class="btn btn-xs btn-info" target="_blank" style="display:none;"><i class="fa fa-edit" title="Edit"></i></a>';
						return $btnEdit;
					}elseif($erow->journal == 0){
						$btnEdit = '<a href="/transaction/payment/edit/'.$erow->id.'" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-edit" title="Edit"></i></a>';                    
						return $btnEdit;
					}
				})
				->addColumn('delete', function ($row) { 
					$delete = trans('general.delete');
					if($row->journal == 1) {
						return '<a id="#" data-target="#Delete-'.$row->id.'" data-toggle="modal" class="btn btn-xs btn-danger" style="display:none;"><i class="fa fa-trash" title="'.$delete.'"></i></a>';
					}elseif($row->journal == 0){
						return '<a id="#" data-target="#Delete-'.$row->id.'" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" title="'.$delete.'"></i></a>';
					}
                })
				->addColumn('print', function ($prow) {                    
					$btnPrint = '<a href="/transaction/payment/print/'.$prow->id.'" class="btn btn-xs btn-default" target="_blank"><i class="fa fa-print" title="Print"></i></a>';
                    return $btnPrint;
                })
				->addColumn('journal', function ($prow) {                    
					//$btnJournal = '<a href="/transaction/payment/journal/'.$prow->id.'" class="btn btn-xs btn-warning" target="_blank"><i class="fa fa-briefcase" title="Journal"></i></a>';
                    //return $btnJournal;
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
		return view('payment.index');
	}
	
	public function create()
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
			
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
			}
		}
		
		$customers = Customer::where('branch',$companyID)->get();
		
		$purchases = Purchase::where('paid',0)->get();
		$types = TransactionType::all();
		
		return view('payment.create',compact('customers','types','purchases'));
		
	}
	
	public function loadcustomer(Request $request){
		
		if ($request->ajax()) {
			$users = User::with('companies')->where('id',auth()->user()->id)->get();
			
			foreach($users as $user)
			{
				foreach($user->companies as $company)
				{
					$companyID = $company->id;
				}
			}
			
			$search = $request->customer;

			if($search == ''){
				 $customers = Customer::where('branch',$companyID)->select('id','name')->get();
			}else{
				 $customers = Customer::where('branch',$companyID)->select('id','name')->where('name', 'like', '%' .$search . '%')->get();
			}

			$response = array();
			foreach($customers as $anggota){
				$response[] = array(
					"id"=>$anggota->id,
					"text"=>$anggota->name
				);
			}
		}

      return response()->json($response);
	}
   
	public function loadpayment(Request $request){
		if ($request->ajax()) {
			$users = User::with('companies')->where('id',auth()->user()->id)->get();
			
			foreach($users as $user)
			{
				foreach($user->companies as $company)
				{
					$companyID = $company->id;
				}
			}
			
			$search = $request->customer;
			//$customers = Customer::where('id',$search)->where('branch',$companyID)->select('id','member_number')->first();
			//$memberNumber = $customers->member_number;
			//if($search == ''){
				$angsurans = Loan::where('member_number',$search)->where('company_id',$companyID)->select('id','member_number','loan_amount','pay_principal','pay_interest','pay_month')->first();
				//$savings = Savings::where('member_number',$search)->where('company_id',$companyID)->select('id','rates','rate_count')->get();
				$tempos = Tempo::where('member_number',$search)->where('branch',$companyID)->select('id','rates','rate_count','amount','total_amount')->first();
			//} 
			
			$response = array();
			//foreach($angsurans as $angsuran){
				$response[] = array(
					'jumlah_pinjaman' => !empty($angsurans->loan_amount) ? $angsurans->loan_amount : 0,
					'pokok_cicilan' => !empty($angsurans->pay_principal) ? $angsurans->pay_principal : 0,
					'bunga_cicilan' => !empty($angsurans->pay_interest) ? $angsurans->pay_interest : 0,
					'total_cicilan' => !empty($angsurans->pay_month) ? $angsurans->pay_month : 0,
					'pokok_tempo' => !empty($tempos->amount) ? $tempos->amount : 0,
					'bunga_tempo' => !empty($tempos->rate_count) ? $tempos->rate_count : 0,
					'total_tempo' => !empty($tempos->total_amount) ? $tempos->total_amount : 0
				);
			//}
			//foreach($tempos as $tempo){
			//	$response[] = array(
			//		'pokok_tempo' => $tempo->amount,
			//		'bunga_tempo' => $tempo->rate_count
			//	);
			//}
			
			
		}
		
		return response()->json($response);
		
	}
	
	public function store(Request $request)
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
		// aturan Validasi //
        $validation = Validator::make($request->all(), [
            'amount' => 'required|string|max:255',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			
			// cek kode
			//$query = mysqli_query($conn, "SELECT max(right(kode_transaksi, 4)) AS kode FROM tb_transaksi WHERE DATE(tanggal) = CURDATE()");
			$query = \DB::table('payments')->whereDate('pay_date', $request->pay_date)
				->where('branch',$companyID)
				->select(\DB::raw('SUBSTRING(transaction_code, 4, 4) as kode'))
				->get();			
			//dd($query);
			if ($query->count() > 0) {
				foreach ($query as $q) {
					//$no = ((int)$q['kode'])+1;
					$no = $q->kode+1;
					$kd = sprintf("%04s", $no);
				}
			} else {
				$kd = "0001";
			}
			
			$pay = "PAY";
			$date = date("Y-m-d");
			$tahun = substr($date, 0, 4);
			$bulan = substr($date, 5, 2);
			$hari = substr($date, 8, 2);
			$code = $companyCode;
			
			//$kd = $this->orderUnik(8);
			$payments = new Payment();
			$payments->transaction_code = $pay .$kd .$bulan .$tahun .$code;
			$payments->pay_date = $request->pay_date;
			$payments->cust_id = $request->cust_id;
			$payments->transaction_type = $request->transaction_type;
			//$payments->customer = $request->customer;
			$payments->purchase_no = $request->trans_code;
			$payments->customer_name = $request->customer_name;
			$payments->branch = $companyID;
			$payments->payment_method = $request->payment_method;
			$payments->full_free = $request->full_free;
			$payments->status = $request->status;
			$payments->pay_status = $request->pay_status;
			if (!empty($request->input('amount'))){
				$payments->amount = str_replace('.', '', $request->amount);
			} else {
				$payments->amount = 0;
			}
			
			//$inst = "INST";
			//$noUnik = $inst .$this->nomorUnik(10);
			
			//if ($request->transaction_type == 'installment')
			//{	
			//	$currentMonth = now()->format('Y-m-d');				
			//	$last = Installment::where('member_number', '=', $request->customer)->orderBy('id','desc')->first();
			//	$lastID = $last->id;				
								
			//	Installment::updateOrCreate([
			//		'trans_number' => $inst .$noUnik,
			//		'inst_to' => $lastID + 1,
			//		'member_number' => $request->customer,
			//		'pay_date' => $request->pay_date,
			//		'pay_method' => $request->payment_method,										
			//		'status' => $request->status,
			//		'amount' => !empty($request->amount) ? str_replace('.', '', $request->amount) : 0
			//	]);
				
			//	Installment::where('member_number', '=', $request->customer)->update(['trans_number' => $noUnik, 'pay_status' => $request->full_free]);
			
			//} elseif ($request->transaction_type == 'savings')
			//{
			//	$totalSaldoAwal = Savings::select(DB::raw('IFNULL(SUM(start_balance), 0) as total_awal'))
			//	->where('member_number', $request->customer)
			//	->where('tipe', $request->saving_type)->get();
								
			//	$totalSaldoAkhir = Savings::select(DB::raw('MAX(end_balance) as total_akhir'))->where('member_number', $request->customer)->where('tipe', $request->saving_type)->first();
			//	$saldoAkhir = $totalSaldoAkhir->total_akhir;					
			//	$customers = Customer::where('member_number',$request->customer)->first();			
				
			//	$awal = 0;
			//	$svg = "SVG";
			//	$proofNumber = $svg .$this->nomorUnik(10);
			//	$savings = new Savings();
			//	$savings->proof_number = $proofNumber;
			//	$savings->member_number = $request->customer;
			//	$savings->tr_date = $request->pay_date;
			//	$savings->branch = $customers->branch; 
			//	$savings->tipe = $request->saving_type;
			//	$savings->status = "setor";
			//	$savings->amount = str_replace('.', '', $request->amount);
			//	$setor = str_replace('.', '', $request->amount);
			//	$savings->start_balance = $setor;
			//	$getTarik = Savings::where('branch',$companyID)->where('tipe',$request->saving_type)->where('status','tarik')->where('member_number',$request->customer)->get();
			//	$tarik = $getTarik->sum('amount');
			//	$getSetor = Savings::where('branch',$companyID)->where('tipe',$request->saving_type)->where('status','setor')->where('member_number',$request->customer)->get();
			//	$sSaldo = $getSetor->sum('amount') - $tarik = $getTarik->sum('amount');
			//	$savings->end_balance = $setor + $sSaldo;
			//	$savings->created_by = auth()->user()->name;
			//	$savings->save();
					
			//}  else	{ 
			//	return redirect()->back();
			//}
			
			$payments->save();
			
			Purchase::where('trans_code', '=', $request->trans_code)->update(['paid' => 1]);			
		}
		
		return redirect()->back()->with('success', 'Add successfully');
	}
	
	public function edit($id)
	{
		$payments = Payment::where('id',$id)->get();
		return view('payment.edit',compact('payments'));
	}
	
	public function update(Request $request, $id)
	{
			$payments = Payment::where('id',$id)->first();
			//$payments->transaction_code = $pay .$kd .$bulan .$tahun .$code;
			$payments->pay_date = $request->pay_date;
			$payments->cust_id = $request->cust_id;
			$payments->transaction_type = $request->transaction_type;
			//$payments->customer = $request->customer;
			$payments->purchase_no = $request->trans_code;
			$payments->customer_name = $request->customer_name;
			$payments->branch = $companyID;
			$payments->payment_method = $request->payment_method;
			$payments->full_free = $request->full_free;
			$payments->status = $request->status;
			$payments->pay_status = $request->pay_status;
			if (!empty($request->input('amount'))){
				$payments->amount = str_replace('.', '', $request->amount);
			} else {
				$payments->amount = 0;
			}
			$payments->save();
			
			return redirect()->back()->with('success', 'Update successfully');
	}
	
	public function delete($id)
	{
		Payment::where('id',$id)->delete();
		return redirect()->back()->with('success', 'Delete Successfully');
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
        $pdf::Cell(0, 10, "KWITANSI PEMBAYARAN", 0, 2, 'C');
		$pdf::Ln();
		
		$payment = Payment::where('id',$id)->first();
		
		$pdf::SetFont('Arial', 'B', 12);
		$pdf::MultiCell(40, 10, "Tgl. Bayar", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, date('d-m-Y', strtotime($payment->pay_date)) , 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::Ln();
		$pdf::MultiCell(40, 10, "No. Bukti", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, $payment->transaction_code, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();
		$pdf::MultiCell(40, 10, "Kepada", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, $payment->customer_name, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();

        $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(10, 8, "NO", 1, 0, 'C');
        $pdf::Cell(45, 8, "NO. TRANSAKSI", 1, 0, 'C');
        $pdf::Cell(100, 8, "KETERANGAN", 1, 0, 'C');
        $pdf::Cell(15, 8, "QTY", 1, 0, 'C');
		$pdf::Cell(20, 8, "SATUAN", 1, 0, 'C');
		$pdf::Cell(40, 8, "HARGA", 1, 0, 'C');
		$pdf::Cell(40, 8, "TOTAL", 1, 0, 'C');
        $pdf::Ln();        
		
		$purchases = Purchase::where('trans_code',$payment->purchase_no)->orderBy('id', 'asc')->get();
		//dd($pendaptans);
		foreach($purchases as $key => $item)
		{			
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(10, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(45, 8, $item->trans_code, 1, 0, 'C');
	        //$pdf::Cell(30, 8, date('d-m-Y', strtotime($item->trans_date)), 1, 0, 'C');
	        $pdf::Cell(100, 8, $item->description, 1, 0, 'L');
			$pdf::Cell(15, 8, $item->qty, 1, 0, 'C');
			$pdf::Cell(20, 8, $item->unit, 1, 0, 'C');
	        $pdf::Cell(40, 8, "Rp. ".number_format($item->amount, 0, ',', '.').",-", 1, 0, 'R');
			$pdf::Cell(40, 8, "Rp. ".number_format($item->total, 0, ',', '.').",-", 1, 0, 'R');
	        $pdf::Ln();

    	}
		
		//$in = BalanceAccount::where('customer_id',$id)->where('payment_type', 'IN')->first();
		//$out = BalanceAccount::where('customer_id',$id)->where('payment_type', 'OUT')->first();
		//$total = $in->amount - $out->amount;
		
		$pdf::SetFont('Arial', 'B', 10);
		$pdf::Cell(270, 8, "Rp. ".number_format($payment->amount, 0, ',', '.').",-", 1, 0, 'R');
		$pdf::Ln();
		
        $pdf::SetFont('Arial', 'B', 10);
        $pdf::Cell(270, 8, "TERBILANG :", 1, 0, 'L');
		$pdf::Ln();
		
        $pdf::SetFont('Arial', 'B', 8);
		$pdf::Cell(270, 10, strtoupper(Terbilang::bilang($payment->amount)) . "RUPIAH", 1, 0, 'R');
        $pdf::Ln();

        // Footer
        $pdf::SetY(179);
        $pdf::SetX(165);
        $pdf::SetFont('Arial','I',8);
        $pdf::Cell(0,10,"Dicetak Oleh Akuntan : ". $profileName ." Pada ".date("d-m-Y H:i:s")
        ." WIB", 0, 0, 'C');
		
		ob_end_clean();
		return $pdf::Output('pembayaran.pdf','I');
	}
	
	public function orderUnik($length) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomOrder = '';
        for ($i = 0; $i < $length; $i++) {
            $randomOrder .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomOrder;
    }
	
	public  function nomorUnik($length) 
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomBayar = '';
        for ($i = 0; $i < $length; $i++) {
            $randomBayar .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomBayar;
    }
	
	public function autoComplete(Request $request) 
	{
        $query = $request->get('term','');        
        $payments = payment::where('trans_code','LIKE','%'.$query.'%')->get();
        
        $data=array();
        foreach ($payments as $payment) {
			$data[]=array('value'=>$payment->trans_code,'id'=>$payment->id);
        }
		
        if(count($data))
            return $data;
        else
			return ['value'=>'No Result Found','id'=>''];
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
		
		$payments = Payment::where('id',$id)->where('journal',0)->first();
		//dd($payments);
		$types = TransactionType::where('transaction_type',$payments->transaction_type)->get();
		
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
				$data->proof_number = $payments->transaction_code;
				$data->transaction_date = $payments->pay_date;
				$data->company_id = $companyID;
				$data->description = $type->description;
				$data->beginning_balance = $payments->amount;
				$data->nominal = $payments->amount;
				$data->ending_balance = $payments->amount;
				$data->save();
				//print_r ($data);
			}
		}
		
		Payment::where('transaction_code', '=', $payments->transaction_code)->update(['journal' => 1]);
		Purchase::where('trans_code', '=', $payments->purchase_no)->update(['journal' => 1]);
		return redirect()->back()->with('success', 'Journal Successfully');
	}
	
}
