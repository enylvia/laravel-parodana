<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerApprove;
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
use App\Models\Receipt;
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

class ReLoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index(Request $request)
	{
		return view('reloan.index');
	}
	
	public function stepOne(Request $request)
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
			}
		}
		
		if(Auth::user()->hasRole('superadmin','pengawas')) 
		{
			$customers = Customer::all();
		}else {
			$customers = Customer::where('branch',$companyID)->get();
		}	
		
		return view('reloan.stepOne',compact('customers'));
	}
	
	public function storeStepOne(Request $request)
    {
		$customers = Loan::where('customer_id',$request->customer)->where('loan_remaining','<>',0)->first();
		$getID = Customer::where('id',$request->customer)->first();
		$id = $getID;
		if ($customers) {
			return redirect()->back()->with('success', 'Masih belum lunas');
		} else {
			return redirect()->route('reloan.StepTwo', ['id' => $id]);
		}
    }
	
	public function stepTwo(Request $request, $id)
	{
		$customers = Customer::where('id',$id)->paginate(10);
		//if(Auth::user()->hasRole('superadmin','pengawas')) {
		//	$customers = Customer::where('approve',1)->where('status','approve')->paginate(10);
		//}else{
		//	$customers = Customer::where('approve',1)->where('status','approve')->where('branch',$companyID)->paginate(10);
		//}
		$years = range(Carbon::now()->year, 2010);						
		$haris = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
		$tanggals = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
		$bulans = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
		$tahuns = $years;
		$tenors = [6,9,12,16,18,20,24,30,34,36,38,40,42,46,48];
		//$customers = Customer::where('id',$id)->where('approve',1)->where('status','approve')->get();
		$employees = User::where('id','<>',1)->where('id','<>',2)->get();
		$contracts = CustomerContract::where('customer_id',$id)->get();
		
		return view('reloan.stepTwo',compact('customers','employees','haris','tanggals','bulans','tahuns','contracts','tenors'));
	}
	
	public function storeStepTwo(Request $request)
    {
		$id = $request->customer_id;
		$approves = new CustomerApprove();
		$approves->customer_id = $request->customer_id;
		$approves->reg_number = $request->reg_number;
		$approves->approve_amount = str_replace('.', '', $request->loan_amount);
		$approves->time_period = $request->time_period;
		$approves->interest_rate = $request->interest_rate;
		$approves->approve_by = auth()->user()->name;
		$approves->approve = 0;
		$approves->save();
		
		return redirect()->route('reloan.StepThree', ['id' => $id]);
	}
	
	public function stepThree(Request $request, $id)
	{
		$customers = Customer::where('id',$id)->paginate(10);
		$years = range(Carbon::now()->year, 2010);						
		$haris = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
		$tanggals = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
		$bulans = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
		$tahuns = $years;
		$tenors = [6,9,12,16,18,20,24,30,34,36,38,40,42,46,48];
		//$customers = Customer::where('id',$id)->where('approve',1)->where('status','approve')->get();
		$employees = User::where('id','<>',1)->where('id','<>',2)->get();
		$contracts = CustomerContract::where('customer_id',$id)->get();
		
		return view('reloan.stepThree',compact('customers','employees','haris','tanggals','bulans','tahuns','contracts','tenors'));
	}
	
	public function storeStepThree(Request $request)
    {
		
	}
	
	public function StepFour(Request $request, $id)
    {
		$customers = Customer::where('id',$id)->get();
		$years = range(Carbon::now()->year, 2010);						
		$haris = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
		$tanggals = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
		$bulans = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
		$tahuns = $years;
		$tenors = [6,9,12,16,18,20,24,30,34,36,38,40,42,46,48];
		//$customers = Customer::where('id',$id)->where('approve',1)->where('status','approve')->get();
		$employees = User::where('id','<>',1)->where('id','<>',2)->get();
		$contracts = CustomerContract::where('customer_id',$id)->get();
		
		return view('reloan.stepFour',compact('customers','employees','haris','tanggals','bulans','tahuns','contracts','tenors'));
	}
	
	public function storeStepFour(Request $request)
    {
		// aturan Validasi //
        $validation = Validator::make($request->all(), [
            'tanggal' => 'required|string|max:255',
			'hari' => 'required|string|max:255',
			'bulan' => 'required|string|max:255',  
			'tahun' => 'required|string|max:255',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			
			$customers = Customer::where('id',$request->customer_id)->get();
			foreach($customers as $customer)
			{
				$custID = $customer->id;
				$branch = $customer->branch;
				$payDate = $customer->payday_date;
				$regNumber = $customer->reg_number;
				//$memberNumber = $customer->member_number;
				$contractDate= $customer->created_at;
			
			
				$approve = CustomerApprove::where('customer_id',$request->customer_id)->where('approve',0)->first(); 
				$companies = Company::where('id',$branch)->get();
				foreach($companies as $company)
				{
					$companyId = $company->company_id;
					$branchId = $company->id;
				}				
				
				$kd = $this->kontrak(8);
				$date = $contractDate->format('Y-m-d');
				$tahun = substr($date, 0, 4);
				$bulan = substr($date, 5, 2);
				$hari = substr($date, 8, 2);
								
				$contracts = new CustomerContract();
				$loanTo = $request->loan_to;
				$contracts->customer_id = $request->customer_id;
				$contracts->reg_number = $regNumber;
				$contracts->contract_number = $companyId .$kd .$bulan .$tahun;
				$contracts->contract_date = $contractDate->format('Y-m-d');
				$contracts->c_day = $request->hari;
				$contracts->c_date = $request->tanggal;
				$contracts->c_month = $request->bulan;
				$contracts->c_year = $request->tahun;
				$contracts->employee_id = $request->employee;
				$contracts->atm_number = $request->atm_number;
				$contracts->bank_pin = $request->bank_pin;
				if (!empty($request->input('m_savings'))){
					$contracts->m_savings = str_replace('.', '', $request->m_savings);
				} else {
					$contracts->m_savings = 0;
				}
				;
				$contracts->insurance = $request->insurance;
				//$contracts->stamp = $request->stamp;
				if (!empty($request->input('stamp'))){
					$contracts->stamp = str_replace('.', '', $request->stamp);
				} else {
					$contracts->stamp = 0;
				}
				$contracts->provision = $request->provision;			
				$contracts->status = 'BELUM LUNAS';				
							
				
				$xdate = date("Y-m-d");
				$xtahun = substr($xdate, 2, 2);
				$xbulan = substr($xdate, 5, 2);
				$xhari = substr($xdate, 8, 2);									
				
				$y = \DB::table('loans')->whereDate('created_at', date('Y-m-d'))			  
					->where('company_id',$branch)
					->select(\DB::raw('max(RIGHT(member_number, 6)) as kode'))
					->get();
				
				//$cd = "";
				
				if($y->count() > 0)
				{
					foreach($y as $k){
						$tmp = $k->kode+1;
						$cd = sprintf("%06s", $tmp);
					}
				}else{
					$cd = "000001";
				}
										
				$memberNumber = $companyId .$xbulan .$xtahun .$cd;
				Customer::where('id', '=', $contracts->customer_id)->update(
				[
					'status' => 'member', 
					//'member_number' => $memberNumber,
					'member' => 1
				]);
				//,										
			
				$start = Carbon::now();
				$tenor = $approve->time_period;
				$sukuBunga = $approve->interest_rate / 12;
				$pokok = str_replace('.', '', $approve->approve_amount) / $tenor;
				$bunga = str_replace('.', '', $approve->approve_amount) * $sukuBunga / 100;				
				$jumlahAngsuran = $pokok + $bunga;
				$payMonth = ceil($jumlahAngsuran / 1000) * 1000;
				$loa = "LOA";
				$num = $this->kontrak(14);
				$totalPrincipal = $pokok * $tenor;
				$totalInterest = $bunga * $tenor;
				
				Loan::create([
					'customer_id' => $request->customer_id,
					'loan_number' => $loa .$num,
					'contract_number' => $contracts->contract_number, 
					'contract_date' => $contracts->contract_date, 
					'start_month' => $start->addMonth(),
					'member_number' => $customer->member_number,
					'loan_amount' => str_replace('.', '', $approve->approve_amount), 
					'time_period' => $approve->time_period, 
					'pay_date' => $payDate,
					'interest_rate' => $sukuBunga,
					'pay_principal' => ceil($pokok / 1000) * 1000,
					'pay_interest' => ceil($bunga),
					'pay_month' => ceil($jumlahAngsuran / 1000) * 1000,
					'company_id' => $branchId,
					'loan_remaining' => $payMonth * $approve->time_period,
					'total_principal' => $totalPrincipal,
					'total_interest' => $totalInterest,
					'status' => 'BELUM LUNAS'
				]);				
				
				$waktu = $approve->time_period;
				
				for ($bulan = 1; $bulan <= $waktu; $bulan++) {
					//$date = Carbon::now('Asia/Jakarta');
					$date = $contractDate;
					//$date->modify('+' . $bulan . ' month');
					$tanggal = $date->modify('+' . $bulan . ' month');
					$Y = $tanggal->format("Y");
					$m = $tanggal->format("m");
					$d = $payDate;
					//'due_date' => $date->format('Y-m-d')
					//'due_date' => $date->setDate($Y, $m, $d)->format('Y-m-d'),
					Installment::create([
						'inst_to' => $bulan,
						'member_number' => $customer->member_number,
						'due_date' => $tanggal->setDate($Y, $m, $d)->format('Y-m-d'),
						'pay_date' => null,
						'pay_method' => null,
						'amount' => 0,
						'late_charge' => null,
						'status' => 'UNPAID'
					]);
				}
								
				$contracts->save();
			}
			
			CustomerApprove::where('customer_id', '=', $custID)->where('approve',0)->update(
			[
				'approve' => 1,
				'm_savings' => str_replace('.', '', $request->m_savings)
			]);
			//return redirect('/customer/reloan/approve')->with('success', 'Contract Add successfully');
			//return redirect()->route('reloan.StepFour', ['id' => $custID]);
			$this->receipt($custID);
			return redirect('/receipt')->with('success', 'Add successfully');
		}
	}
	
	public function receipt($id)
	{			
		$contracts = CustomerContract::where('customer_id',$id)->get();
		foreach($contracts as $contract)
		{
			$customerID = $contract->customer_id;
			$contractNumber = $contract->contract_number;
			$insurance = $contract->insurance;
			$stamp = $contract->stamp;
			$provision = $contract->provision;
		}
		
		$loans = Loan::where('customer_id',$id)->first();
		$plafon = $loans->loan_amount;
		$rate = $loans->interest_rate;
		$tenor = $loans->time_period;
		
		$customers = Customer::where('id',$customerID)->first();

		if (empty($contractNumber)) {
			return redirect()->back()->with('error', 'Data not Found');
		}else{
			$query = \DB::table('receipt')->whereDate('created_at', date('Y-m-d'))			  
			  ->where('branch',$customers->branch)
			  ->select(\DB::raw('SUBSTRING(contract_number, 8, 1) as kode'))
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
				
			$rcp = "RCP";
			$date = date("Y-m-d");
			$tahun = substr($date, 0, 4);
			$bulan = substr($date, 5, 2);
			$hari = substr($date, 8, 2);
			
			$receipts = new Receipt();
			//$receipts->trans_number = $this->transNumber(12);
			$transNumber = $rcp .$kd .$bulan .$tahun;
			$receipts->trans_number = $transNumber;
			$receipts->trans_date = now();
			$receipts->reg_number = $customers->reg_number;
			$receipts->contract_number = $contractNumber;
			$receipts->branch = $customers->branch;				
			//$insurance = $request->insurance;
			$receipts->insurance = $insurance;
			$asuransi = $plafon * $insurance / 100;
			//if (!empty($request->input('stamp'))){
				$receipts->stamp = str_replace('.', '', $stamp);
				$materai = str_replace('.', '', $stamp);
			//} else {
			//	$receipts->stamp = 0;
			//}
			$receipts->provision = $provision;
			$provisi = $plafon * $provision / 100;
			//if (!empty($request->input('provision'))){
			//	$receipts->provision = str_replace('.', '', $request->provision);
			//	$provisi = str_replace('.', '', $request->provision);
			//} else {
			//	$receipts->provision = 0;
			//}
			$receipts->amount = round($plafon) - round($materai) - round($provisi) - round($asuransi);
			$receipts->save();
			$this->journal($transNumber);
			//return redirect()->route('reloan.print', ['id' => $transNumber]);
			//return redirect('/receipt')->with('success', 'Add successfully');
		}								
	
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
		
		$transNumber = $id;
		
		$receipts = Receipt::where('trans_number',$transNumber)->first();
		$customers = Customer::where('reg_number',$receipts->reg_number)->get();
		foreach($customers as $customer)
		{
			$customerName = $customer->name;
			$regNumber = $customer->reg_number;
		}
		$approves = CustomerApprove::where('reg_number',$regNumber)->first();
		
		$plafon = $approves->approve_amount;
		$jumlahPinjaman = number_format($plafon, 0, ',' , '.');
		$rate = $approves->interest_rate;
		$tenor = $approves->time_period;
		$xprovisi = $plafon * $receipts->provision /100;
		$provisi = number_format($xprovisi, 0, ',' , '.');
		$xasuransi = $plafon * $receipts->insurance /100;
		$asuransi = number_format($xasuransi, 0, ',' , '.');
			
		$employees = Employee::where('user_id',auth()->user()->id)->first();				
		$akuns = Receipt::where('trans_number',$id)->get();		
		
		foreach($akuns as $key => $akun) 
		{
			
			$kReceipt = new Journal();
			$kReceipt->account_id = '41';
			$kReceipt->account_number = '1-00-01';	
			$kReceipt->tipe = 'k';					
			$kReceipt->proof_number = $akun->trans_number;
			$kReceipt->transaction_date = $akun->trans_date;
			$kReceipt->company_id = $companyID;
			$kReceipt->description = 'Pencairan Pinjaman';
			$kReceipt->beginning_balance = 0;
			$kReceipt->nominal = $akun->amount;
			$kReceipt->ending_balance = 0;
			$kReceipt->save();
			
			$dReceipt = new Journal();
			$dReceipt->account_id = '46';
			$dReceipt->account_number = '1-40-01';	
			$dReceipt->tipe = 'd';					
			$dReceipt->proof_number = $akun->trans_number;
			$dReceipt->transaction_date = $akun->trans_date;
			$dReceipt->company_id = $companyID;
			$dReceipt->description = 'Pencairan Pinjaman';
			$dReceipt->beginning_balance = 0;
			$dReceipt->nominal = $akun->amount;
			$dReceipt->ending_balance = 0;
			$dReceipt->save();
			
			$kMaterai = new Journal();
			$kMaterai->account_id = '96';
			$kMaterai->account_number = '4-30-02';	
			$kMaterai->tipe = 'k';					
			$kMaterai->proof_number = $akun->trans_number;
			$kMaterai->transaction_date = $akun->trans_date;
			$kMaterai->company_id = $companyID;
			$kMaterai->description = 'Pendapatan dari Materai';
			$kMaterai->beginning_balance = 0;
			$kMaterai->nominal = $akun->stamp;
			$kMaterai->ending_balance = 0;
			$kMaterai->save();
			
			$dMaterai = new Journal();
			$dMaterai->account_id = '60';
			$dMaterai->account_number = '1-90-05';	
			$dMaterai->tipe = 'd';					
			$dMaterai->proof_number = $akun->trans_number;
			$dMaterai->transaction_date = $akun->trans_date;
			$dMaterai->company_id = $companyID;
			$dMaterai->description = 'Pendapatan dari Materai';
			$dMaterai->beginning_balance = 0;
			$dMaterai->nominal = $akun->stamp;
			$dMaterai->ending_balance = 0;
			$dMaterai->save();
			
			$kProvisi = new Journal();
			$kProvisi->account_id = '94';
			$kProvisi->account_number = '4-20-01';	
			$kProvisi->tipe = 'k';					
			$kProvisi->proof_number = $akun->trans_number;
			$kProvisi->transaction_date = $akun->trans_date;
			$kProvisi->company_id = $companyID;
			$kProvisi->description = 'Provisi Pinjaman';
			$kProvisi->beginning_balance = 0;
			$kProvisi->nominal =str_replace('.', '', $provisi);
			$kProvisi->ending_balance = 0;
			$kProvisi->save();
			
			$dProvisi = new Journal();
			$dProvisi->account_id = '41';
			$dProvisi->account_number = '1-00-01';	
			$dProvisi->tipe = 'd';					
			$dProvisi->proof_number = $akun->trans_number;
			$dProvisi->transaction_date = $akun->trans_date;
			$dProvisi->company_id = $companyID;
			$dProvisi->description = 'Provisi Pinjaman';
			$dProvisi->beginning_balance = 0;
			$dProvisi->nominal = str_replace('.', '', $provisi);
			$dProvisi->ending_balance = 0;
			$dProvisi->save();
			
			$kAsuransi = new Journal();
			$kAsuransi->account_id = '66';
			$kAsuransi->account_number = '2-00-05';	
			$kAsuransi->tipe = 'k';					
			$kAsuransi->proof_number = $akun->trans_number;
			$kAsuransi->transaction_date = $akun->trans_date;
			$kAsuransi->company_id = $companyID;
			$kAsuransi->description = 'Asuransi Pinjaman';
			$kAsuransi->beginning_balance = 0;
			$kAsuransi->nominal = str_replace('.', '', $asuransi);
			$kAsuransi->ending_balance = 0;
			$kAsuransi->save();
			
			$dAsuransi = new Journal();
			$dAsuransi->account_id = '41';
			$dAsuransi->account_number = '1-00-01';	
			$dAsuransi->tipe = 'd';					
			$dAsuransi->proof_number = $akun->trans_number;
			$dAsuransi->transaction_date = $akun->trans_date;
			$dAsuransi->company_id = $companyID;
			$dAsuransi->description = 'Asuransi Pinjaman';
			$dAsuransi->beginning_balance = 0;
			$dAsuransi->nominal = str_replace('.', '', $asuransi);
			$dAsuransi->ending_balance = 0;
			$dAsuransi->save();
			
			Receipt::where('trans_number', '=', $akun->trans_number)->update(['journal' => 1 ]);						
			//return redirect()->back()->with('success', 'Journal Wajib Successfully');								
		}
				
		//return redirect($currentURL)->with("success","Journal successfully !");
		//return redirect()->route('reloan.print', ['id' => $transNumber]);
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
        $pdf::Cell(0, 10, "KWITANSI", 0, 2, 'C');
		$pdf::Ln();		
		
		$receipts = Receipt::where('trans_number',$id)->get();
		foreach($receipts as $receipt)
		{
			$nasabah = Customer::where('reg_number',$receipt->reg_number)->first();
			$approve = CustomerApprove::where('reg_number',$receipt->reg_number)->first();
			$insurance = $receipt->insurance;
			$plafon = $approve->approve_amount;
			$rate = $approve->interest_rate;
			$tenor = $approve->time_period;
			$asuransi = $plafon * $insurance / 100;
			$materai = $receipt->stamp;
			$provisi = $plafon * $tenor * $receipt->provision / 100;
			
			$pdf::SetFont('Arial', 'B', 12);
			//$pdf::MultiCell(30, 8, "Tanggal", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
			//$pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			//$pdf::MultiCell(45, 8, $receipt->trans_date ? date('d-m-Y', strtotime($receipt->trans_date)) : '' , 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
			$pdf::MultiCell(30, 8, "Pinjaman", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');			
			$pdf::MultiCell(65, 8, "Rp. ".number_format($approve->approve_amount, 0, ',', '.').",-", 0, 'R', 0, 0, '', '', true, 0, false, true, 10, 'M');		
			$pdf::Ln();
			$pdf::MultiCell(30, 8, "Provisi", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');			
			$pdf::MultiCell(65, 8, "Rp. ".number_format($provisi, 0, ',', '.').",-", 0, 'R', 0, 0, '', '', true, 0, false, true, 10, 'M');		
			$pdf::Ln();
			$pdf::MultiCell(30, 8, "Asuransi", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');			
			$pdf::MultiCell(65, 8, "Rp. ".number_format($asuransi, 0, ',', '.').",-", 0, 'R', 0, 0, '', '', true, 0, false, true, 10, 'M');		
			$pdf::Ln();
			$pdf::MultiCell(30, 8, "Materai", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');			
			$pdf::MultiCell(65, 8, "Rp. ".number_format($materai, 0, ',', '.').",-", 0, 'R', 0, 0, '', '', true, 0, false, true, 10, 'M');		
			$pdf::Ln();			
			$pdf::MultiCell(30, 8, "Terima Bersih", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(65, 8, "", 0, 'R', 0, 0, '', '', true, 0, false, true, 10, 'M');		
			$pdf::Ln();
		}
        // Footer
        $pdf::SetY(120);
        $pdf::SetX(30);
        $pdf::SetFont('Arial','I',10);        
        $pdf::Cell(0,10, $kelurahan->nama .",".date("d-m-Y"), 0, 0, 'C');
        $pdf::Ln();

        $pdf::SetY(150);
        $pdf::SetX(20);
        $pdf::SetFont('Arial','I',10);        
        $pdf::Cell(0,10, ($nasabah->name));
        $pdf::Ln();
        	
        $pdf::SetY(150);
        $pdf::SetX(150);
        $pdf::SetFont('Arial','I',10);        
        $pdf::Cell(0,10, (auth()->user()->name));
		
		ob_end_clean();
		return $pdf::Output('receipt.pdf','I');		
	}
	
	public function fetch(Request $request)
    {
		$customers = Customer::where('id',$request->customer)->get();
		//return customers;
		echo json_encode($customers);
	}
	
	public  function kontrak($length) 
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
