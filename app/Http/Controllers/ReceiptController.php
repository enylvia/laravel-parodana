<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerApprove;
use App\Models\CustomerContract;
use App\Models\Receipt;
use App\Models\Posting;
use App\Models\AccountGroup;
use App\Models\Employee;
use App\Models\Journal;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Helper\Terbilang;
use Carbon\Carbon;
use Validator;
use DB;
use DPDF;
use TPDF;
use Auth;
use URL;

class ReceiptController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index()
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
			$customers = CustomerContract::all();
			//$customers = CustomerContract::where('customer_id',$customer->id)->get();
			$receipts = Receipt::all();
		}else{
			$customer = Customer::where('branch',$companyID)->first();
			//dd($customer->id);
			$customers = CustomerContract::where('customer_id',$customer->id)->get();
			$receipts = Receipt::where('branch',$companyID)->get();
		}
		//$receipts = Receipt::where('branch',$companyID)->get();
		
		return view('receipt.index',compact('customers','receipts'));
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
		
		if(Auth::user()->hasRole('superadmin','pengawas')) 
		{
			$customers = Customer::all();
		}elseif(Auth::user()->hasRole('manager'))
		{
			$customer = Customer::where('branch',$companyID)->first();			
			$customers = CustomerContract::where('customer_id',$customer->id)->get();
		}else{
			$customer = Customer::where('branch',$companyID)->first();			
			$customers = CustomerContract::where('customer_id',$customer->id)->get();
		}				
		
		return view('receipt.create',compact('customers'));
	}
	
	public function store(Request $request)
	{
		// aturan Validasi //
        $validation = Validator::make($request->all(), [
			'trans_number' => 'trans_number|unique:receipt,trans_number',
            'trans_date' => 'required|string|max:255',
			'contract_number' => 'required|string|max:255',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			$contracts = CustomerContract::where('contract_number',$request->contract_number)->get();
			foreach($contracts as $contract)
			{
				$customerID = $contract->customer_id;
				$contractNumber = $contract->contract_number;
			}
			
			$approves = CustomerApprove::where('customer_id',$customerID)->first();
			$plafon = $approves->approve_amount;
			$rate = $approves->interest_rate;
			$tenor = $approves->time_period;
			
			$customers = Customer::where('id',$customerID)->first();
			
			if ($contractNumber = 0 ) {
				return redirect('/receipt')->with('error', 'Data not Found');
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
				$receipts->trans_number = $rcp .$kd .$bulan .$tahun;
				$receipts->trans_date = $request->trans_date;
				$receipts->reg_number = $customers->reg_number;
				$receipts->contract_number = $request->contract_number;
				$receipts->branch = $customers->branch;				
				$insurance = $request->insurance;
				$receipts->insurance = $request->insurance;
				$asuransi = $plafon * $insurance / 100;
				if (!empty($request->input('stamp'))){
					$receipts->stamp = str_replace('.', '', $request->stamp);
					$materai = str_replace('.', '', $request->stamp);
				} else {
					$receipts->stamp = 0;
				}
				$receipts->provision = $request->provision;
				$provisi = $plafon * $request->provision / 100;
				//if (!empty($request->input('provision'))){
				//	$receipts->provision = str_replace('.', '', $request->provision);
				//	$provisi = str_replace('.', '', $request->provision);
				//} else {
				//	$receipts->provision = 0;
				//}
				$receipts->amount = round($plafon) - round($materai) - round($provisi) - round($asuransi);
				$receipts->save();
			}
		}
		return redirect('/receipt')->with('success', 'Add successfully');
	}
	
	public function edit($id)
	{
		$receipts = Receipt::where('id',$id)->get();
		
		return view('receipt.edit',compact('receipts'));
	}
	
	public function update(Request $request, $id)
	{
		$receipts = Receipt::where('id',$id)->first();
		$contractNumber = $receipts->contract_number;
		//dd($contractNumber);
		$contracts = CustomerContract::where('contract_number',$contractNumber)->get();		
		
		foreach($contracts as $contract)
		{
			$customerID = $contract->customer_id;
			//$contractNumber = $contract->contract_number;
		}
		
		$approves = CustomerApprove::where('customer_id',$customerID)->first();
		$plafon = $approves->approve_amount;
		$rate = $approves->interest_rate;
		$tenor = $approves->time_period;
		
		$customers = Customer::where('id',$customerID)->first();
				
		//$receipts->trans_number = $this->transNumber(12);
		$receipts->trans_date = $request->trans_date;
		//$receipts->reg_number = $customers->reg_number;
		$receipts->contract_number = $request->contract_number;
		//$receipts->branch = $customers->branch;
		$insurance = $request->insurance;
		$receipts->insurance = $request->insurance;
		$asuransi = $plafon * $insurance / 100;
		if (!empty($request->input('stamp'))){
			$receipts->stamp = str_replace('.', '', $request->stamp);
			$materai = str_replace('.', '', $request->stamp);
		} else {
			$receipts->stamp = 0;
		}
		$receipts->provision = $request->provision;
		$provisi = $plafon * $request->provision / 100;
		//if (!empty($request->input('provision'))){
		//	$receipts->provision = str_replace('.', '', $request->provision);
		//	$provisi = str_replace('.', '', $request->provision);
		//} else {
		//	$receipts->provision = 0;
		//}
		$receipts->amount = round($plafon) - round($asuransi) - round($provisi) - round($materai);
		$receipts->save();
		
		return redirect('/receipt')->with('success', 'Update successfully');
	}
	
	public function delete($id)
	{
		Receipt::where('id',$id)->delete();
		return redirect('/receipt')->with('success', 'Delete successfully');
	}
	
	public function posting($id)
	{
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
		
		Posting::create([
			'proof_number' => $receipts->trans_number,
			'trans_date' => $receipts->trans_date,
			'branch' => $receipts->branch,
			'description' => "Pencairan dana pinjaman a.n $customerName sebesar Rp. $jumlahPinjaman dengan no kontrak $receipts->contract_number dikenakan biaya rovisi $receipts->provision %, asuransi $receipts->insurance dan materai $receipts->stamp",
			'nominal' => $plafon,
			'journal' => 0
		]);
		
		Posting::create([
			'proof_number' => $receipts->trans_number,
			'trans_date' => $receipts->trans_date,
			'branch' => $receipts->branch,
			'description' => "Potongan provisi dana pinjaman a.n $customerName dengan no kontrak $receipts->contract_number sebesar Rp. $provisi dari $receipts->provision jumlah pinjaman Rp. $jumlahPinjaman",
			'nominal' => $xprovisi,
			'journal' => 0
		]);
		
		Posting::create([
			'proof_number' => $receipts->trans_number,
			'trans_date' => $receipts->trans_date,
			'branch' => $receipts->branch,
			'description' => "Potongan asuransi dana pinjaman a.n $customerName dengan no kontrak $receipts->contract_number sebesar Rp. $asuransi dari $receipts->insurance jumlah pinjaman Rp. $jumlahPinjaman",
			'nominal' => $xasuransi,
			'journal' => 0
		]);
		
		Posting::create([
			'proof_number' => $receipts->trans_number,
			'trans_date' => $receipts->trans_date,
			'branch' => $receipts->branch,
			'description' => "Potongan materai dana pinjaman a.n $customerName dengan no kontrak $receipts->contract_number sebesar Rp. $receipts->stamp dari jumlah pinjaman Rp. $jumlahPinjaman",
			'nominal' => $receipts->stamp,
			'journal' => 0
		]);
		
		Receipt::where('trans_number', '=', $receipts->trans_number)->update(['posting' => 1 ]);
		
		return redirect('/receipt')->with('success', 'Posting successfully');
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
			$kProvisi->nominal = $provisi;
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
			$dProvisi->nominal = $provisi;
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
			$kAsuransi->nominal = $asuransi;
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
			$dAsuransi->nominal = $asuransi;
			$dAsuransi->ending_balance = 0;
			$dAsuransi->save();
			
			Receipt::where('trans_number', '=', $akun->trans_number)->update(['journal' => 1 ]);						
			//return redirect()->back()->with('success', 'Journal Wajib Successfully');								
		}
				
		return redirect($currentURL)->with("success","Journal successfully !");
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
			$plafon = !empty($approve->approve_amount) ? str_replace('.', '', $approve->approve_amount) : 0;
			$rate = !empty($approve->interest_rate) ? $approve->interest_rate : 0;
			$tenor = !empty($approve->time_period) ? $approve->time_period : 0;
			$asuransi = $plafon * $insurance / 100;
			$materai = $receipt->stamp;
			$provisi = $plafon * $tenor * $receipt->provision / 100;
			
			$pdf::SetFont('Arial', 'B', 12);
			//$pdf::MultiCell(30, 8, "Tanggal", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
			//$pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			//$pdf::MultiCell(45, 8, $receipt->trans_date ? date('d-m-Y', strtotime($receipt->trans_date)) : '' , 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
			$pdf::MultiCell(30, 8, "Pinjaman", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');			
			$pdf::MultiCell(65, 8, "Rp. ".number_format($plafon, 0, ',', '.').",-", 0, 'R', 0, 0, '', '', true, 0, false, true, 10, 'M');		
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
        $pdf::Cell(0,10, (!empty($nasabah->name) ? $nasabah->name : NULL));
        $pdf::Ln();
        	
        $pdf::SetY(150);
        $pdf::SetX(150);
        $pdf::SetFont('Arial','I',10);        
        $pdf::Cell(0,10, (auth()->user()->name));
		
		ob_end_clean();
		return $pdf::Output('receipt.pdf','I');

	}
	
	public  function transNumber($length) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomOrder = '';
        for ($i = 0; $i < $length; $i++) {
            $randomOrder .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomOrder;
    }
	
}
