<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Savings;
use App\Models\Posting;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use App\Models\AccountGroup;
use App\Models\BalanceAccount;
use App\Models\Journal;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Provinsi;
use App\Models\Transaction;
use Carbon\Carbon;
use Validator;
use Auth;
use URL;
use DPDF;
use Illuminate\Support\Facades\DB;
use TPDF;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;

class SavingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');;
    }
	
	public function index()
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->first();
		$companyID = $users->companies[0]->id;
		
		$wajib = Savings::where('branch',$companyID)->where('tipe','wajib')->where('status','setor')->sum('amount');
		$pokok = Savings::where('branch',$companyID)->where('tipe','pokok')->where('status','setor')->sum('amount');
		$sukarela = Savings::where('branch',$companyID)->where('tipe','sukarela')->where('status','setor')->sum('amount');

		$tabungan = $wajib + $pokok + $sukarela;
		
		return view('savings.deposit.index',compact('tabungan','wajib','pokok','sukarela'));
	}

	public function indexJson(Request $request){
		$loans = DB::table('customer')
                ->join('loans', 'customer.id', '=', 'loans.customer_id')
                ->select('customer.id', 'customer.name', 'customer.member_number', 'loans.contract_date', 'loans.start_month', 'loans.loan_number', 'loans.loan_amount', 'loans.time_period', 'loans.interest_rate', 'loans.pay_principal', 'loans.pay_interest', 'loans.pay_month', 'loans.loan_remaining', 'loans.company_id')
                ->where('customer.member_number', '!=', " ")
				->where('customer.name', '!=', " ")
				->get();
            return Datatables::of($loans)->make(true);
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
		
		return view('savings.deposit.create',compact('customers','tanggals'));
	}
	
	public function store(Request $request)
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
			}
		}
		
		// aturan Validasi //
        $validation = Validator::make($request->all(), [
			'memberNumber' => 'required|string|max:255',
			'amount' => 'required|string|max:255',
        ]);
		DB::beginTransaction();

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			switch($request->status)
			{
				case('setor'):
					try{
						$totalSaldoAkhir = Savings::where('member_number', $request->memberNumber)
						->where('tipe', $request->tipe)->orderBy('id','desc')
						->first();
						$customers = Customer::where('member_number',$request->memberNumber)->first();			
						
						$awal = 0;
						$svg = "SVG";
						$proofNumber = $svg .$this->TabunganUnik(10);
						$savings = new Savings();
						$savings->proof_number = $proofNumber;
						$savings->member_number = $request->memberNumber;
						$savings->tr_date = now()->format('Y-m-d');
						$savings->branch = $customers->branch; 
						$savings->tipe = $request->tipe;
						$savings->status = $request->status;
						$savings->amount = str_replace('.', '', $request->amount);
						$setor = str_replace('.', '', $request->amount);
						
						if ($totalSaldoAkhir == null){
							$savings->start_balance = $awal;
							$savings->end_balance = $setor;
						} else {
							$savings->start_balance = $totalSaldoAkhir->end_balance;
							$savings->end_balance = $totalSaldoAkhir->end_balance + $setor;
						}
						$savings->description = $request->description;
						
						$savings->created_by = auth()->user()->name;
						$savings->save();
						$this->journal_tabungan($request->memberNumber,$request->tipe);
						DB::commit();
						return redirect()->back()->with('success', 'Setor dana sukses');					
						
						break;
					}catch(\Exception $e){
						DB::rollBack();
						return redirect()->back()->with('error', $e->getMessage());
					}
				case('tarik'):		
					try{
						$cekSaldo = Savings::where('member_number',$request->memberNumber)
						->where('branch',$companyID)->where('tipe',$request->tipe)
						->orderBy('id','desc')->first();

						$saldo = $cekSaldo->end_balance;
						$ambil = str_replace('.', '', $request->amount);
						if ($saldo < $ambil) {							
							return redirect('/deposit')->with('status', 'Tarik dana gagal atau saldo tidak cukup!');
						}else{
							$svg = "SVG";
							$ambilSaldo = $saldo - $ambil;
							$proofNumber = $svg .$this->TabunganUnik(10);
							$savings = new Savings();
							$savings->proof_number = $proofNumber;
							$savings->member_number = $request->memberNumber;
							$savings->branch = $companyID;
							$savings->tr_date = now()->format('Y-m-d');
							$savings->tipe = $request->tipe;
							$savings->start_balance = $saldo;
							$savings->end_balance = $ambilSaldo;
							$savings->amount = $ambil;
							$savings->status = $request->status;
							$savings->description = $request->description;
							$savings->created_by = auth()->user()->name;
							$savings->save();
							$this->journal_penarikan($request->memberNumber,$request->tipe);
							DB::commit();
							return redirect()->route('print.tabungan',$request->memberNumber);
						}
					}catch(\Exception $e){
						DB::rollBack();
						return redirect()->back()->with('error', $e->getMessage());
					}
				
				case('transfer'):
					return redirect()->back()->with('success', 'Fitur belum tersedia!');
				break;
				
				default:
				return redirect()->back()->with('error', 'Transaction Add Unsuccessfully');
				
			}
		}
				
	}
	
	public function journal_tabungan($id,$tipe){
		$users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;
		DB::beginTransaction();
		$data = Savings::where('member_number',$id)->where('status','setor')->orderBy('id','desc')->first();
		$customer = Customer::where('member_number', $id)->first();
		$trxnumber = Transaction::max('id');
		$trxnumber = $trxnumber + 1;
		try {
			if ($tipe == 'sukarela') {
				$transactions = [[
					'trx_no' => 'SVGTRX'.now()->format('Ymd').$trxnumber,
					'date_trx' => $data->tr_date,
					'account' => '310-01',
					'branch' => $companyID,
					'amount' => $data->amount,
					'description' => 'Tab Sukarela Pinjaman '.$customer->name,
					'status' => 'k',
					'acc_by' => auth()->user()->name,
				],[
					'trx_no' => 'SVGTRX'.now()->format('Ymd').($trxnumber+1),
					'date_trx' => $data->tr_date,
					'account' => '100-01',
					'branch' => $companyID,
					'amount' => $data->amount,
					'description' => 'Tab Sukarela Pinjaman '.$customer->name,
					'status' => 'd',
					'acc_by' => auth()->user()->name,
				],
			];
			Transaction::insert($transactions);
			for ($i=0; $i <= (count($transactions)-1); $i++) { 
				$accountBalance = BalanceAccount::where('account_number',$transactions[$i]['account'])->where('branch',$companyID)->first();
				$accountType = AccountGroup::where('account_number',$transactions[$i]['account'])->first();
				if ($transactions[$i]['status'] == 'd'){
					if (is_null($accountBalance)){
						BalanceAccount::create([
							'branch' => $companyID,
							'transaction_type' => $accountType->account_name,
							'account_number' => $transactions[$i]['account'],
							'amount' => $transactions[$i]['amount'],
							'start_balance' => $transactions[$i]['amount'],
							'end_balance' =>$transactions[$i]['amount'],
						]);
					}else{
						$accountBalance->end_balance = $accountBalance->end_balance + $transactions[$i]['amount'];
						$accountBalance->updated_at = now();
						$accountBalance->save();
					}
				}else{
					if (is_null($accountBalance)){
						BalanceAccount::create([
							'branch' => $companyID,
							'transaction_type' => $accountType->account_name,
							'account_number' => $transactions[$i]['account'],
							'amount' => $transactions[$i]['amount'],
							'start_balance' => (0 - $transactions[$i]['amount']),
							'end_balance' =>(0 - $transactions[$i]['amount']),
						]);
					}else{
						$accountBalance->end_balance = (int) $accountBalance->end_balance + $transactions[$i]['amount'];
						$accountBalance->updated_at = now();
						$accountBalance->save();
					}
				}
			}
			}else if ($tipe == 'wajib' ) {
				$transactions = [[
					'trx_no' => 'SVGTRX'.now()->format('Ymd').$trxnumber,
					'date_trx' => $data->tr_date,
					'account' => '310-01',
					'branch' => $companyID,
					'amount' => $data->amount,
					'description' => 'Tab Wajib Pinjaman '.$customer->name,
					'status' => 'k',
					'acc_by' => auth()->user()->name,
				],[
					'trx_no' => 'SVGTRX'.now()->format('Ymd').($trxnumber+1),
					'date_trx' => $data->tr_date,
					'account' => '100-01',
					'branch' => $companyID,
					'amount' => $data->amount,
					'description' => 'Tab Wajib Pinjaman '.$customer->name,
					'status' => 'd',
					'acc_by' => auth()->user()->name,
				],
			];
			Transaction::insert($transactions);
			for ($i=0; $i <= (count($transactions)-1); $i++) { 
				$accountBalance = BalanceAccount::where('account_number',$transactions[$i]['account'])->where('branch',$companyID)->first();
				$accountType = AccountGroup::where('account_number',$transactions[$i]['account'])->first();
				if ($transactions[$i]['status'] == 'd'){
					if (is_null($accountBalance)){
						BalanceAccount::create([
							'branch' => $companyID,
							'transaction_type' => $accountType->account_name,
							'account_number' => $transactions[$i]['account'],
							'amount' => $transactions[$i]['amount'],
							'created_by' => auth()->user()->name,
							'start_balance' => $transactions[$i]['amount'],
							'end_balance' =>$transactions[$i]['amount'],
						]);
					}else{
						$accountBalance->end_balance = $accountBalance->end_balance + $transactions[$i]['amount'];
						$accountBalance->updated_at = now();
						$accountBalance->save();
					}
				}else{
					if (is_null($accountBalance)){
						BalanceAccount::create([
							'branch' => $companyID,
							'transaction_type' => $accountType->account_name,
							'account_number' => $transactions[$i]['account'],
							'amount' => $transactions[$i]['amount'],
							'start_balance' => (0 - $transactions[$i]['amount']),
							'end_balance' =>(0 - $transactions[$i]['amount']),
						]);
					}else{
						$accountBalance->end_balance = (int) $accountBalance->end_balance + $transactions[$i]['amount'];
						$accountBalance->updated_at = now();
						$accountBalance->save();
					}
				}
			}
			}else{
				$transactions = [[
					'trx_no' => 'SVGTRX'.now()->format('Ymd').$trxnumber,
					'date_trx' => $data->tr_date,
					'account' => '310-01',
					'branch' => $companyID,
					'amount' => $data->amount,
					'description' => 'Tab Pokok Pinjaman '.$customer->name,
					'status' => 'k',
					'acc_by' => auth()->user()->name,
				],[
					'trx_no' => 'SVGTRX'.now()->format('Ymd').($trxnumber+1),
					'date_trx' => $data->tr_date,
					'account' => '100-01',
					'branch' => $companyID,
					'amount' => $data->amount,
					'description' => 'Tab Pokok Pinjaman '.$customer->name,
					'status' => 'd',
					'acc_by' => auth()->user()->name,
				],
			];
			Transaction::insert($transactions);
			for ($i=0; $i <= (count($transactions)-1); $i++) { 
				$accountBalance = BalanceAccount::where('account_number',$transactions[$i]['account'])->where('branch',$companyID)->first();
				$accountType = AccountGroup::where('account_number',$transactions[$i]['account'])->first();
				if ($transactions[$i]['status'] == 'd'){
					if (is_null($accountBalance)){
						BalanceAccount::create([
							'branch' => $companyID,
							'transaction_type' => $accountType->account_name,
							'account_number' => $transactions[$i]['account'],
							'amount' => $transactions[$i]['amount'],
							'start_balance' => $transactions[$i]['amount'],
							'end_balance' =>$transactions[$i]['amount'],
						]);
					}else{
						$accountBalance->end_balance = $accountBalance->end_balance + $transactions[$i]['amount'];
						$accountBalance->updated_at = now();
						$accountBalance->save();
					}
				}else{
					if (is_null($accountBalance)){
						BalanceAccount::create([
							'branch' => $companyID,
							'transaction_type' => $accountType->account_name,
							'account_number' => $transactions[$i]['account'],
							'amount' => $transactions[$i]['amount'],
							'start_balance' => (0 - $transactions[$i]['amount']),
							'end_balance' =>(0 - $transactions[$i]['amount']),
						]);
					}else{
						$accountBalance->end_balance = (int) $accountBalance->end_balance + $transactions[$i]['amount'];
						$accountBalance->updated_at = now();
						$accountBalance->save();
					}
				}
			}
		}
		DB::commit();
		}catch(\Exception $e){
			DB::rollBack();
			return redirect()->back()->with('errors', $e->getMessage());
		}
	}

	public function journal_penarikan($id,$tipe){
		$data = Savings::where('member_number',$id)->where('status','tarik')->orderBy('id','desc')->first();
		$users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;
		DB::beginTransaction();
		$customer = Customer::where('member_number', $id)->first();
		$trxnumber = Transaction::max('id');
		$trxnumber = $trxnumber + 1;
		try{
			if ($tipe == 'sukarela') {
				$transactions = [[
					'trx_no' => 'SVGTRX'.now()->format('Ymd').$trxnumber,
					'date_trx' => $data->tr_date,
					'account' => '100-01',
					'branch' => $companyID,
					'amount' => $data->amount,
					'description' => 'Penarikan Tab Sukarela an '.$customer->name,
					'status' => 'k',
					'acc_by' => auth()->user()->name,
				],[
					'trx_no' => 'SVGTRX'.now()->format('Ymd').($trxnumber+1),
					'date_trx' => $data->tr_date,
					'account' => '310-02',
					'branch' => $companyID,
					'amount' => $data->amount,
					'description' => 'Penarikan Tab Sukarela an '.$customer->name,
					'status' => 'd',
					'acc_by' => auth()->user()->name,
				],
			];
			Transaction::insert($transactions);
			for ($i=0; $i <= (count($transactions)-1); $i++) { 
				$accountBalance = BalanceAccount::where('account_number',$transactions[$i]['account'])->where('branch',$companyID)->first();
				$accountType = AccountGroup::where('account_number',$transactions[$i]['account'])->first();
				if ($transactions[$i]['status'] == 'd'){
					if (is_null($accountBalance)){
						BalanceAccount::create([
							'branch' => $companyID,
							'transaction_type' => $accountType->account_name,
							'account_number' => $transactions[$i]['account'],
							'amount' => $transactions[$i]['amount'],
							'created_by' => auth()->user()->name,
							'start_balance' => 0,
							'end_balance' =>$transactions[$i]['amount'],
						]);
					}else{
						$accountBalance->end_balance = $accountBalance->end_balance - $transactions[$i]['amount'];
						$accountBalance->updated_at = now();
						$accountBalance->save();
					}
				}else{
					if (is_null($accountBalance)){
						BalanceAccount::create([
							'branch' => $companyID,
							'transaction_type' => $accountType->account_name,
							'account_number' => $transactions[$i]['account'],
							'amount' => $transactions[$i]['amount'],
							'start_balance' => 0,
							'end_balance' =>(0 - $transactions[$i]['amount']),
						]);
					}else{
						$accountBalance->end_balance = (int) $accountBalance->end_balance - $transactions[$i]['amount'];
						$accountBalance->updated_at = now();
						$accountBalance->save();
					}
				}
			}
			}else if ($tipe == 'wajib' ) {
				$transactions = [[
					'trx_no' => 'SVGTRX'.now()->format('Ymd').$trxnumber,
					'date_trx' => $data->tr_date,
					'account' => '100-01',
					'branch' => $companyID,
					'amount' => $data->amount,
					'description' => 'Penarikan Tab Wajib an '.$customer->name,
					'status' => 'k',
					'acc_by' => auth()->user()->name,
				],[
					'trx_no' => 'SVGTRX'.now()->format('Ymd').($trxnumber+1),
					'date_trx' => $data->tr_date,
					'account' => '310-02',
					'branch' => $companyID,
					'amount' => $data->amount,
					'description' => 'Penarikan Tab Wajib an '.$customer->name,
					'status' => 'd',
					'acc_by' => auth()->user()->name,
				],
			];
			Transaction::insert($transactions);
			for ($i=0; $i <= (count($transactions)-1); $i++) { 
				$accountBalance = BalanceAccount::where('account_number',$transactions[$i]['account'])->where('branch',$companyID)->first();
				$accountType = AccountGroup::where('account_number',$transactions[$i]['account'])->first();
				if ($transactions[$i]['status'] == 'd'){
					if (is_null($accountBalance)){
						BalanceAccount::create([
							'branch' => $companyID,
							'transaction_type' => $accountType->account_name,
							'account_number' => $transactions[$i]['account'],
							'amount' => $transactions[$i]['amount'],
							'start_balance' => 0,
							'end_balance' =>$transactions[$i]['amount'],
						]);
					}else{
						$accountBalance->end_balance = $accountBalance->end_balance - $transactions[$i]['amount'];
						$accountBalance->updated_at = now();
						$accountBalance->save();
					}
				}else{
					if (is_null($accountBalance)){
						BalanceAccount::create([
							'branch' => $companyID,
							'transaction_type' => $accountType->account_name,
							'account_number' => $transactions[$i]['account'],
							'amount' => $transactions[$i]['amount'],
							'start_balance' => 0,
							'end_balance' =>(0 - $transactions[$i]['amount']),
						]);
					}else{
						$accountBalance->end_balance = (int) $accountBalance->end_balance - $transactions[$i]['amount'];
						$accountBalance->updated_at = now();
						$accountBalance->save();
					}
				}
			}
			}else{
				$transactions = [[
					'trx_no' => 'SVGTRX'.now()->format('Ymd').$trxnumber,
					'date_trx' => $data->tr_date,
					'account' => '100-01',
					'branch' => $companyID,
					'amount' => $data->amount,
					'description' => 'Penarikan Tab Pokok Pinjaman an '.$customer->name,
					'status' => 'k',
					'acc_by' => auth()->user()->name,
				],[
					'trx_no' => 'SVGTRX'.now()->format('Ymd').($trxnumber+1),
					'date_trx' => $data->tr_date,
					'account' => '310-01',
					'branch' => $companyID,
					'amount' => $data->amount,
					'description' => 'Penarikan Tab Pokok Pinjaman an '.$customer->name,
					'status' => 'd',
					'acc_by' => auth()->user()->name,
				],
			];
			Transaction::insert($transactions);
			for ($i=0; $i <= (count($transactions)-1); $i++) { 
				$accountBalance = BalanceAccount::where('account_number',$transactions[$i]['account'])->where('branch',$companyID)->first();
				$accountType = AccountGroup::where('account_number',$transactions[$i]['account'])->first();
				if ($transactions[$i]['status'] == 'd'){
					if (is_null($accountBalance)){
						BalanceAccount::create([
							'branch' => $companyID,
							'transaction_type' => $accountType->account_name,
							'account_number' => $transactions[$i]['account'],
							'amount' => $transactions[$i]['amount'],
							'start_balance' => 0,
							'end_balance' =>$transactions[$i]['amount'],
						]);
					}else{
						$accountBalance->end_balance = $accountBalance->end_balance - $transactions[$i]['amount'];
						$accountBalance->updated_at = now();
						$accountBalance->save();
					}
				}else{
					if (is_null($accountBalance)){
						BalanceAccount::create([
							'branch' => $companyID,
							'transaction_type' => $accountType->account_name,
							'account_number' => $transactions[$i]['account'],
							'amount' => $transactions[$i]['amount'],
							'start_balance' => 0,
							'end_balance' =>(0 - $transactions[$i]['amount']),
						]);
					}else{
						$accountBalance->end_balance = (int) $accountBalance->end_balance - $transactions[$i]['amount'];
						$accountBalance->updated_at = now();
						$accountBalance->save();
					}
				}
			}
		}
		DB::commit();
		}catch(\Exception $e){
			DB::rollBack();
			return redirect()->back()->with('errors', $e->getMessage());
		}
	}

	public function printTabungan($id){
		$pdf = new TPDF;

		$users = User::with('companies')->where('id',auth()->user()->id)->first();
		$companyID = $users->companies[0]->id;

        $profiles = Company::where('id',$companyID)->get();
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
        ob_start();
        // Header
		$pdf::setJPEGQuality(90);
		$pdf::Image('img/logo/logo-small.png', 10, 10, 25, 0, 'PNG', '');
		$pdf::SetFont('', 'B', 18);
        $pdf::Cell(0, 10, $profileName, 0, 2, 'C');
        $pdf::SetFont('', 'B', 12);
		$pdf::Cell(0, 10, $profileAddress, 0, 1, 'C');
        $pdf::Cell(0, 10, "".$kelurahan->nama." , ".$kecamatan->nama." , ".$kabupaten->nama." , ".$provinsi->nama, 'B', 1,  'C', 0, 0, '', '', true, 0, false, true, 10, 'M');

        $pdf::Ln();

        $pdf::SetFont('', 'B', 12);
        $pdf::Cell(0, 10, "SLIP PENGAMBILAN TABUNGAN", 0, 2, 'C');
		$pdf::Ln();

		$savings = Savings::where('member_number',$id)->where('status','tarik')->orderBy('id', 'desc')->first();
		$customer = Customer::where('member_number',$id)->first();
		if (empty($savings))
		{
			return redirect('/deposit')->with('error', 'Data tidak ditemukan!');
		}else {
            $pdf::SetFont('', 'B', 12);
            $pdf::MultiCell(40, 8, "Penerima", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(140, 8, $customer->name, 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::Ln();
            $pdf::MultiCell(40, 8, "Jumlah", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(140, 8, "Rp. " . number_format($savings->amount, 0, ',', '.') . ",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::Ln();
			$pdf::MultiCell(40, 8, "Saldo Akhir Tabungan", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(140, 8, "Rp. ".number_format($savings->end_balance, 0, ',', '.').",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::Ln();
			// Footer
			$pdf::SetY(185);
			$pdf::SetX(150);
			$pdf::SetFont('','I',8);
			$pdf::Cell(0,10, $kelurahan->nama .",".date_format($savings->updated_at,"Y-m-d"), 0, 0, 'C');
			$pdf::Ln();

			$pdf::SetY(200);
			$pdf::SetX(20);
			$pdf::SetFont('','I',8);
			$pdf::Cell(0,10, ("Diketahui Bag. Tabungan"));

			$pdf::Ln();

			$pdf::SetY(200);
			$pdf::SetX(95);
			$pdf::SetFont('','I',8);
			$pdf::Cell(0,10, ("Dibayar Kasir"));
			$pdf::Ln();

			$pdf::SetY(200);
			$pdf::SetX(150);
			$pdf::SetFont('','I',8);
			$pdf::Cell(0,10,("Tanda Tangan Penabung"));
			$pdf::Ln();

			$pdf::SetY(250);
			$pdf::SetX(160);

			$html = '<a href= "'.url('/deposit') .'" class="btn btn-xs btn-default">Close</a>';

		$pdf::writeHTML($html, true, false, true, false, '');

		ob_end_clean();
		return $pdf::Output('penarikantabungan.pdf','I');
		}
	}

	public function update(Request $request,$id)
	{
		// aturan Validasi //
        $validation = Validator::make($request->all(), [
            'tr_date' => 'required|string|max:255',
			'member_number' => 'required|string|max:255',
			'amount' => 'required|string|max:255',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			$Savingss = Savings::where('id',$id)->first();
			$Savingss->member_number = $request->member_number;
			$Savingss->tr_date = $request->tr_date;
			$Savingss->tipe = $request->tipe;
			$Savingss->amount = str_replace('.', '', $request->amount);
			$Savingss->created_by = auth()->user()->id;
			$Savingss->save();
		}
		
		return redirect()->back()->with('success', 'Transaction Add successfully');
	}
	
	public function penarikan_post(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'nominal' => 'required|numeric',
            'keterangan' => 'max:200',
        ]);

        $cek_jumlah = Savings::select(DB::raw('SUM(nominal) as total_simpanan'))->where('member_number', $request->member_number)->where('jenis_simpanan_id', 3)->first();
        $saldo = $cek_jumlah->total_simpanan;
        $ambil = $request->nominal;
        if ($saldo < $ambil) {
            return redirect()->route('simpanan.penarikan')->with(['error' => 'Saldo simpanan sukarela Anda tidak cukup.']);
        } else {
            $simpanan = Savings::create([
                'anggota_id' => $request->anggota_id,
                'jenis_simpanan_id' => 3,
                'nominal' => '-' . $request->nominal,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('simpanan.penarikan')->with(['success' => 'Penarikan simpanan berhasil.']);
        }
    }
	
	public function delete($id)
	{
		Savings::where('id',$id)->delete();
		return redirect()->back()->with('success', 'Delete successfully');
	}
	
	public function posting($id)
	{
		$savings = Savings::where('id',$id)->where('journal',0)->first();
		$memberNumber = $savings->member_number;
		$customers = Customer::where('member_number',$memberNumber)->first();
		$jumlah = number_format($savings->amount, 0, ',' , '.');
		
		if ($savings->tipe=='WAJIB')
		{
			Posting::create([
				'proof_number' => $savings->proof_number,
				'trans_date' => $savings->tr_date,
				'branch' => $savings->branch,
				'description' => "Setor tabungan wajib Bapak $customers->name sebesar Rp. $jumlah",
				'nominal' => $savings->amount,
				'journal' => 0
			]);
			
			Savings::where('member_number', '=', $memberNumber)->update(['posting' => 1]);
			
			return redirect()->back()->with('success', 'Posting successfully');
			
		} else {
			return redirect()->back()->with('error', 'Unsuccessfully');
		}
		
		if ($savings->tipe=='POKOK')
		{
			Posting::create([
				'proof_number' => $savings->proof_number,
				'trans_date' => $savings->tr_date,
				'branch' => $savings->branch,
				'description' => "Setor tabungan pokok Bapak $customers->name sebesar Rp. $jumlah",
				'nominal' => $savings->amount,
				'journal' => 0
			]);
			
			Savings::where('member_number', '=', $memberNumber)->update(['posting' => 1]);
			
			return redirect()->back()->with('success', 'Posting successfully');
			
		} else {
			return redirect()->back()->with('error', 'Unsuccessfully');
		}
		
		if ($savings->tipe=='SUKARELA')
		{
			Posting::create([
				'proof_number' => $savings->proof_number,
				'trans_date' => $savings->tr_date,
				'branch' => $savings->branch,
				'description' => "Setor tabungan sukarela Bapak $customers->name sebesar Rp. $jumlah",
				'nominal' => $savings->amount,
				'journal' => 0
			]);
			
			Savings::where('member_number', '=', $memberNumber)->update(['posting' => 1]);
			
			return redirect()->back()->with('success', 'Posting successfully');
			
		} else {
			return redirect()->back()->with('error', 'Unsuccessfully');
		}
		
		return redirect()->back()->with('success', 'Posting successfully');
	}
	
	public function search(Request $request)
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
			//$limit = 10;
			$query = "";
			//if ($request->has('limit')) {
			//	$limit = $request->limit;
			//}
			//if ($request->has('query')) {
			//	$query = $request->get('query');
			//}
			$query = $request->get('query');
			//$query = str_replace(" ", "%", $query);
			
			if($query != '')
			{
				$data = DB::table('savings')
				->where('proof_number', 'like', '%'.$query.'%')				
				->orWhere('member_number', 'LIKE', '%' . $query . '%')
				->orWhere('start_balance', 'LIKE', '%' . $query . '%')
				->orWhere('tipe', 'LIKE', '%' . $query . '%')
				->orWhere('end_balance', 'LIKE', '%' . $query . '%')
				->orWhere('branch',$companyID)
				->orderBy('id', 'desc')
				->paginate(10);         
			}
			else
			{
				$data = DB::table('savings')
				->where('branch',$companyID)
				->orderBy('id', 'desc')
				->paginate(10);
			}
		$total_row = $data->count();
		if($total_row > 0)
		{
			foreach($data as $key => $row)
			{
				$i = $key +1;	
				$postingDisplay = ($row->journal) ? 'display:block;' : 'display:none;';
				$output .='<tr>'.
					'<td align="center">'.$i.'</td>'.
					'<td>'.$row->tr_date.'</td>'.
					'<td>'.$row->member_number.'</td>'.
					'<td></td>'.
					'<td>'.$row->tipe.'</td>'.
					'<td>Rp.'.number_format($row->start_balance, 0, ',' , '.').'</td>'.
					'<td>Rp.'.number_format($row->end_balance, 0, ',' , '.').'</td>'.
					'<td>Rp.'.number_format($row->amount, 0, ',' , '.').'</td>'.
					'<td>'.$row->status.'</td>'.			
					'<td style="width:2px;" align="center">'.
						'<a id="Edit" data-target="#Edit-'.$row->id.'" data-toggle="modal" class="btn btn-sm btn-info">'.
							'<i class="fa fa-save" title="'.trans('general.edit').'"></i>'.
						'</a>'.
					'</td>'.
					'<td style="width:2px;" align="center">'.
						'<a id="Delete" data-target="#Delete-'.$row->id.'" data-toggle="modal" class="btn btn-sm btn-danger">'.
							'<i class="fa fa-trash" title="'.trans('general.delete').'"></i>'.
						'</a>'.
					'</td>'.
					'<td style="width:2px;" align="center">'.
						'<a class="btn btn-sm btn-warning" href='.('/deposit/journal/').''.$row->proof_number.' style="'.$postingDisplay.'">'.
							'<i class="fa fa-columns" title="Posting"></i>'.  
						'</a>'.					
					'</td>'.
					'</tr>';
			}
		}
		else
		{
			$output = '
			<tr>
				<td align="center" colspan="13">No Data Found</td>
			</tr>';
		}
		$data = array(
			'table_data'  => $output,
			'total_data'  => $total_row
		);

		echo json_encode($data);
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
			
		$employees = Employee::where('user_id',auth()->user()->id)->first();				
		$akuns = Savings::where('proof_number',$id)->get();		
		
		foreach($akuns as $key => $akun) 
		{
			//$groups = AccountGroup::where('account_number',$akun->account_number)->get();
			//dd($groups);
			//$data = Journal::where('transaction_no',$id)->where('id',$akun->id)->first();	
			//foreach($groups as $group)
			//{					
			switch($akun->status)
			{
				case('setor'):
					if ($akun->tipe='wajib')
					{
						$aWajib = new Journal();
						$aWajib->account_id = '41';
						$aWajib->account_number = '1-00-01';	
						$aWajib->tipe = 'd';					
						$aWajib->proof_number = $akun->proof_number;
						$aWajib->transaction_date = $akun->tr_date;
						$aWajib->company_id = $companyID;
						$aWajib->description = 'Setoran Tabungan Wajib';
						$aWajib->beginning_balance = $akun->start_balance;
						$aWajib->nominal = $akun->amount;
						$aWajib->ending_balance = $akun->end_balance;
						$aWajib->save();
						
						$bWajib = new Journal();
						$bWajib->account_id = '74';
						$bWajib->account_number = '3-10-02';	
						$bWajib->tipe = 'k';					
						$bWajib->proof_number = $akun->proof_number;
						$bWajib->transaction_date = $akun->tr_date;
						$bWajib->company_id = $companyID;
						$bWajib->description = 'Setoran Tabungan Wajib';
						$bWajib->beginning_balance = $akun->start_balance;
						$bWajib->nominal = $akun->amount;
						$bWajib->ending_balance = $akun->end_balance;
						$bWajib->save();
						
						Savings::where('proof_number', '=', $akun->proof_number)->update(['journal' => 1 ]);						
						//return redirect()->back()->with('success', 'Journal Wajib Successfully');
					}elseif ($akun->tipe='pokok')
					{
						$aPokok = new Journal();
						$aPokok->account_id = '41';
						$aPokok->account_number = '1-00-01';	
						$aPokok->tipe = 'd';					
						$aPokok->proof_number = $akun->proof_number;
						$aPokok->transaction_date = $akun->tr_date;
						$aPokok->company_id = $companyID;
						$aPokok->description = 'Setoran Tabungan Pokok';
						$aPokok->beginning_balance = $akun->start_balance;
						$aPokok->nominal = $akun->amount;
						$aPokok->ending_balance = $akun->end_balance;
						$aPokok->save();
						
						$bPokok = new Journal();
						$bPokok->account_id = '73';
						$bPokok->account_number = '3-10-01';	
						$bPokok->tipe = 'k';					
						$bPokok->proof_number = $akun->proof_number;
						$bPokok->transaction_date = $akun->tr_date;
						$bPokok->company_id = $companyID;
						$bPokok->description = 'Setoran Tabungan Pokok';
						$bPokok->beginning_balance = $akun->start_balance;
						$bPokok->nominal = $akun->amount;
						$bPokok->ending_balance = $akun->end_balance;
						$bPokok->save();
						
						Savings::where('proof_number', '=', $akun->proof_number)->update(['journal' => 1 ]);
						//return redirect()->back()->with('success', 'Journal Pokok Successfully');
					}elseif ($akun->tipe='sukarela')
					{
						$aSukarela = new Journal();
						$aSukarela->account_id = '41';
						$aSukarela->account_number = '1-00-01';	
						$aSukarela->tipe = 'd';					
						$aSukarela->proof_number = $akun->proof_number;
						$aSukarela->transaction_date = $akun->tr_date;
						$aSukarela->company_id = $companyID;
						$aSukarela->description = 'Setoran Tabungan Sukarela';
						$aSukarela->beginning_balance = $akun->start_balance;
						$aSukarela->nominal = $akun->amount;
						$aSukarela->ending_balance = $akun->end_balance;
						$aSukarela->save();
					
						$bSukarela = new Journal();
						$bSukarela->account_id = '75';
						$bSukarela->account_number = '3-10-03';	
						$bSukarela->tipe = 'k';					
						$bSukarela->proof_number = $akun->proof_number;
						$bSukarela->transaction_date = $akun->tr_date;
						$bSukarela->company_id = $companyID;
						$bSukarela->description = 'Setoran Tabungan Sukarela';
						$bSukarela->beginning_balance = $akun->start_balance;
						$bSukarela->nominal = $akun->amount;
						$bSukarela->ending_balance = $akun->end_balance;
						$bSukarela->save();
						
						Savings::where('proof_number', '=', $akun->proof_number)->update(['journal' => 1 ]);
						//return redirect()->back()->with('success', 'Journal Sukarela Successfully');
					}else{
						return redirect()->back()->with('error', 'Data Not Found');
					}
				return redirect()->back()->with('success', 'Journal Add Successfully');
				break;
				
				case('tarik'):
					if ($akun->tipe='wajib')
					{
						$aWajib = new Journal();
						$aWajib->account_id = '41';
						$aWajib->account_number = '1-00-01';	
						$aWajib->tipe = 'k';					
						$aWajib->proof_number = $akun->proof_number;
						$aWajib->transaction_date = $akun->tr_date;
						$aWajib->company_id = $companyID;
						$aWajib->description = 'Penarikan Tabungan Wajib';
						$aWajib->beginning_balance = $akun->start_balance;
						$aWajib->nominal = $akun->amount;
						$aWajib->ending_balance = $akun->end_balance;
						$aWajib->save();
						
						$bWajib = new Journal();
						$bWajib->account_id = '74';
						$bWajib->account_number = '3-10-02';	
						$bWajib->tipe = 'd';					
						$bWajib->proof_number = $akun->proof_number;
						$bWajib->transaction_date = $akun->tr_date;
						$bWajib->company_id = $companyID;
						$bWajib->description = 'Penarikan Tabungan Wajib';
						$bWajib->beginning_balance = $akun->start_balance;
						$bWajib->nominal = $akun->amount;
						$bWajib->ending_balance = $akun->end_balance;
						$bWajib->save();
						
						Savings::where('proof_number', '=', $akun->proof_number)->update(['journal' => 1 ]);						
						//return redirect()->back()->with('success', 'Journal Wajib Successfully');
					}elseif ($akun->tipe='pokok')
					{
						$aPokok = new Journal();
						$aPokok->account_id = '41';
						$aPokok->account_number = '1-00-01';	
						$aPokok->tipe = 'k';					
						$aPokok->proof_number = $akun->proof_number;
						$aPokok->transaction_date = $akun->tr_date;
						$aPokok->company_id = $companyID;
						$aPokok->description = 'Penarikan Tabungan Pokok';
						$aPokok->beginning_balance = $akun->start_balance;
						$aPokok->nominal = $akun->amount;
						$aPokok->ending_balance = $akun->end_balance;
						$aPokok->save();
						
						$bPokok = new Journal();
						$bPokok->account_id = '73';
						$bPokok->account_number = '3-10-01';	
						$bPokok->tipe = 'd';					
						$bPokok->proof_number = $akun->proof_number;
						$bPokok->transaction_date = $akun->tr_date;
						$bPokok->company_id = $companyID;
						$bPokok->description = 'Penarikan Tabungan Pokok';
						$bPokok->beginning_balance = $akun->start_balance;
						$bPokok->nominal = $akun->amount;
						$bPokok->ending_balance = $akun->end_balance;
						$bPokok->save();
						
						Savings::where('proof_number', '=', $akun->proof_number)->update(['journal' => 1 ]);
						//return redirect()->back()->with('success', 'Journal Pokok Successfully');
					}elseif ($akun->tipe='sukarela')
					{
						$aSukarela = new Journal();
						$aSukarela->account_id = '41';
						$aSukarela->account_number = '1-00-01';	
						$aSukarela->tipe = 'k';					
						$aSukarela->proof_number = $akun->proof_number;
						$aSukarela->transaction_date = $akun->tr_date;
						$aSukarela->company_id = $companyID;
						$aSukarela->description = 'Penarikan Tabungan Sukarela';
						$aSukarela->beginning_balance = $akun->start_balance;
						$aSukarela->nominal = $akun->amount;
						$aSukarela->ending_balance = $akun->end_balance;
						$aSukarela->save();
					
						$bSukarela = new Journal();
						$bSukarela->account_id = '75';
						$bSukarela->account_number = '3-10-03';	
						$bSukarela->tipe = 'd';					
						$bSukarela->proof_number = $akun->proof_number;
						$bSukarela->transaction_date = $akun->tr_date;
						$bSukarela->company_id = $companyID;
						$bSukarela->description = 'Penarikan Tabungan Sukarela';
						$bSukarela->beginning_balance = $akun->start_balance;
						$bSukarela->nominal = $akun->amount;
						$bSukarela->ending_balance = $akun->end_balance;
						$bSukarela->save();
						
						Savings::where('proof_number', '=', $akun->proof_number)->update(['journal' => 1 ]);
						//return redirect()->back()->with('success', 'Journal Sukarela Successfully');
					}else{
						return redirect()->back()->with('error', 'Data Not Found');
					}
				return redirect()->back()->with('success', 'Journal Add Successfully');
				break;
				
				default:
				return redirect()->back()->with('error', 'Transaction Add Unsuccessfully');
			}							
			//}					
		}
				
		return redirect($currentURL)->with("success","Journal successfully !");
		//return response()->json(array('result' => 'success', 'changed' => ($data->journal) ? 1 : 0));
	}
	
	public function card($id)
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
			}
		}
		
		$customers = Customer::where('branch',$companyID)->where('status','member')->where('member_number',$id)->get();
		
		return view('savings.deposit.membercard',compact('customers'));
		
		//$pdf = PDF::loadView('savings.deposit.membercard', compact('customers'))->setPaper('a4', 'potrait')->setOptions([
        //              'tempDir' => public_path(),
        //              'chroot'  => public_path('/img/logo/'),
        //          ]);
		//GENERATE PDF-NYA
		//return $pdf->stream();
	}
	
	public function view($id)
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->first();
		$companyID = $users->companies[0]->id;
		
		$customers = Customer::where('branch',$companyID)->where('status','member')->get();
		$setorans = Savings::where('branch',$companyID)->where('member_number',$id)->orderBy('id','desc')->paginate(10);
		
		$wajibdata = Savings::where('branch',$companyID)->where('tipe','wajib')->where('member_number',$id)->where('status','setor')->orderBy('id','desc')->first();
		$pokokdata = Savings::where('branch',$companyID)->where('tipe','pokok')->where('member_number',$id)->where('status','setor')->orderBy('id','desc')->first();
		$sukareladata = Savings::where('branch',$companyID)->where('tipe','sukarela')->where('member_number',$id)->where('status','setor')->orderBy('id','desc')->first();


		if ($wajibdata == null)
		{
			$wajib = 0;
		}else{
			$wajib = $wajibdata->end_balance;
		}
		if ($pokokdata == null)
		{
			$pokok = 0;
		}else{
		$pokok = $pokokdata->end_balance;
		}
		if ($sukareladata == null)
		{
			$sukarela = 0;
		}else{
		$sukarela = $sukareladata->end_balance;
		}
		$tabungan = $wajib + $pokok + $sukarela;				
		
		return view('savings.deposit.view',compact('customers','setorans','wajib','pokok','sukarela','tabungan'));
	}
	
	public function mutation($id)
	{
		//dd($id);
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
			}
		}
		
		$customers = Customer::where('branch',$companyID)->where('status','member')->get();
		$nasabah = Customer::where('branch',$companyID)->where('member_number',$id)->where('status','member')->first();
		$setorans = Savings::where('branch',$companyID)->where('member_number',$id)->orderBy('tr_date','asc')->paginate(10);
		
		$getTarikWajib = Savings::where('branch',$companyID)->where('tipe','wajib')->where('status','tarik')->where('member_number',$id)->get();
		$tarikWajib = $getTarikWajib->sum('start_balance');
		$getWajib = Savings::where('branch',$companyID)->where('tipe','wajib')->where('status','setor')->where('member_number',$id)->get();
		$wajib = $getWajib->sum('start_balance') - $tarikWajib = $getTarikWajib->sum('start_balance');		
		
		$getTarikPokok = Savings::where('branch',$companyID)->where('tipe','pokok')->where('status','tarik')->where('member_number',$id)->get();
		$tarikPokok = $getTarikPokok->sum('start_balance');
		$getPokok = Savings::where('branch',$companyID)->where('tipe','pokok')->where('status','setor')->where('member_number',$id)->get();
		$pokok = $getPokok->sum('start_balance') - $tarikPokok;		
		
		$getTarikSukarela = Savings::where('branch',$companyID)->where('tipe','sukarela')->where('status','tarik')->where('member_number',$id)->get();
		$tarikSukarela = $getTarikSukarela->sum('start_balance');
		$getSukarela = Savings::where('branch',$companyID)->where('tipe','sukarela')->where('status','setor')->where('member_number',$id)->get();
		$sukarela = $getSukarela->sum('start_balance') - $tarikSukarela;
		
		$getTabungan = Savings::where('branch',$companyID)->where('member_number',$id)->get();
		//$tabungan = $getTabungan->sum('start_balance');
		$tabungan = $wajib + $pokok + $sukarela;
		
		$kreditWajib = Savings::where('branch',$companyID)->where('tipe','wajib')->where('status','setor')->where('member_number',$id)->orderBy('tr_date','asc')->first(); 
		$kreditPokok = Savings::where('branch',$companyID)->where('tipe','pokok')->where('status','setor')->where('member_number',$id)->first(); 
		$kreditSukarela = Savings::where('branch',$companyID)->where('tipe','sukarela')->where('status','setor')->where('member_number',$id)->first(); 
		$debetWajib = Savings::where('branch',$companyID)->where('tipe','wajib')->where('status','tarik')->where('member_number',$id)->first(); 
		$debetPokok = Savings::where('branch',$companyID)->where('tipe','pokok')->where('status','tarik')->where('member_number',$id)->first(); 
		$debetSukarela = Savings::where('branch',$companyID)->where('tipe','sukarela')->where('status','tarik')->where('member_number',$id)->first(); 
		//return view('savings.deposit.mutation',compact('customers','setorans','wajib','pokok','sukarela','tabungan'));
		//$awalNabung = DB::select("SELECT tr_date, tipe, @d:=if(status='setor',amount,0) AS debet, @k:=if(status='tarik',amount,0) AS kredit, @s:= @s + @d - @k AS saldo FROM (SELECT @s:= 0) AS dummy CROSS JOIN savings as s WHERE member_number = '".$id."' AND branch = '".$companyID."' ORDER BY tr_date asc limit 1");
		//dd($awalNabung);
		$qs = "SELECT tr_date, tipe, @d:=if(status='setor',amount,0) AS debet, @k:=if(status='tarik',amount,0) AS kredit, @s:= @s + @d - @k AS saldo FROM (SELECT @s:= 0) AS dummy CROSS JOIN savings as s WHERE member_number = '".$id."' AND branch = '".$companyID."' ORDER BY tr_date asc limit 1";
		$awalNabung = DB::select($qs);
		foreach($awalNabung as $xs)
		{
			$dt = $xs->tr_date;
		
			$query = "SELECT tr_date, tipe, @d:=if(status='setor',amount,0) AS debet, @k:=if(status='tarik',amount,0) AS kredit, @s:= @s + @d - @k AS saldo FROM (SELECT @s:= 0) AS dummy CROSS JOIN savings as s WHERE member_number = '".$id."' AND branch = '".$companyID."'";
			$saldo = DB::select($query);
			//dd($saldo);
			
			$pdf = DPDF::loadView('savings.deposit.mutation', compact('customers','setorans','kreditWajib','debetWajib','kreditPokok','debetPokok','kreditSukarela','debetSukarela','nasabah','wajib','pokok','sukarela','tabungan','saldo','dt'))
			->setPaper('a4', 'potrait')->setOptions([
						  'tempDir' => public_path(),
						  'chroot'  => public_path('/img/logo/'),
					  ]);
			//GENERATE PDF-NYA
			return $pdf->stream();
		}
	}
	
	public function saldo($id)
	{
		$memberNumber = $id;
		$getWajib = Savings::where('branch',$companyID)->where('tipe','WAJIB')->where('status','setor')->get();
		$wajib = $getWajib->sum('start_balance');
		$getPokok = Savings::where('branch',$companyID)->where('tipe','POKOK')->where('status','setor')->get();
		$pokok = $getPokok->sum('start_balance');
		$getSukarela = Savings::where('branch',$companyID)->where('tipe','SUKARELA')->where('status','setor')->get();
		$sukarela = $getSukarela->sum('start_balance');
		
		Savings::select(DB::raw('MAX(end_balance)'))
						->where('member_number', '=', $memberNumber)
						->where('tipe','=','WAJIB')
						->update(['end_balance' => $wajib]);
		
		return response()->json(['data' => 'success']);
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

	public function GetTabunganList($limit, $memberNumber)
	{
		$listTabungan = Savings::where('member_number',$memberNumber)->limit($limit)->orderBy('id','desc')->get();
		$pokok = Savings::where('member_number',$memberNumber)->where('tipe','pokok')->orderBy('id','desc')->first();
		$wajib = Savings::where('member_number',$memberNumber)->where('tipe','wajib')->orderBy('id','desc')->first();
		$sukarela = Savings::where('member_number',$memberNumber)->where('tipe','sukarela')->orderBy('id','desc')->first();
		
		if ($pokok != null) {
			$pokok = $pokok->end_balance;
		} else {
			$pokok = 0;
		}
		if ($wajib != null) {
			$wajib = $wajib->end_balance;
		} else {
			$wajib = 0;
		}
		if ($sukarela != null) {
			$sukarela = $sukarela->end_balance;
		} else {
			$sukarela = 0;
		}
		$totalTabungan = $pokok + $wajib + $sukarela;
		$response = "<div class='table-responsive' id='tblSaving'>";
		$response .= "<table class='table table-responsive'>";
		$response .= "<tr>";
		$response .= "<td>Total Tab. Pokok</td> <td align='right'>Rp. ".number_format($pokok, 0, ',' , '.')."</td>";
		$response .= "</tr>";
		$response .= "<tr>"; 
		$response .= "<td>Total Tab. Sukarela</td> <td align='right'>Rp. ".number_format($sukarela, 0, ',' , '.')."</td>";
		$response .= "</tr>"; 
		$response .= "<tr>"; 
		$response .= "<td>Total Tab. Wajib</td> <td align='right'>Rp. ".number_format($wajib, 0, ',' , '.')."</td>";
		$response .= "</tr>"; 
		$response .= "<tr>"; 
		$response .= "<td>Total Tabungan</td> <td align='right'>Rp. ".number_format($totalTabungan, 0, ',' , '.')."</td>";
		$response .= "</tr>";
		$response .= "</table>";
		$response .= "</div>"; 
			$response .= "<div class='table-responsive' id='tblSaving'>";
				$response .= "<table class='table table-responsive table-striped'>";
					$response .= "<thead>";
						$response .= "<tr>";
						$response .= "<th>No</th>";
						$response .= "<th>Waktu</th>";
						$response .= "<th>Tipe</th>";
						$response .= "<th>Debit</th>";
						$response .= "<th>Kredit</th>";
						$response .= "<th>Saldo</th>";
						$response .= "<th>Deskripsi</th>";
						$response .= "</tr>";
					$response .= "</thead>";
					$response .= "<tbody>";
					foreach($listTabungan as $key => $row){
						$response .= "<tr>";
						$response .= "<td>".($key+1)."</td>";
						$response .= "<td>".$row->tr_date."</td>";
						$response .= "<td>".$row->tipe."</td>";
						if ($row->status == 'setor') {
							$response .= "<td>-</td>";
							$response .= "<td> Rp. ".number_format($row->amount, 0, ',' , '.')."</td>";
						} else {
							$response .= "<td>-</td>";
							$response .= "<td> Rp. ".number_format($row->amount, 0, ',' , '.')."</td>";
						}
						$response .= "<td> Rp. ".number_format($row->end_balance, 0, ',' , '.')."</td>";
						$response .= "<td>".$row->description."</td>";
						$response .= "</tr>";
					}
					$response .= "</tbody>";
				$response .= "</table>";
			$response .= "</div>";

		echo $response;

	}
		
}
