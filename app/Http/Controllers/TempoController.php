<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Tempo;
use App\Models\Loan;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Helper\Terbilang;
use App\Models\AccountGroup;
use App\Models\BalanceAccount;
use App\Models\Installment;
use App\Models\Transaction;
use Carbon\Carbon;
use DPDF;
use TPDF;
use Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TempoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index(Request $request)
	{
		
		$customer = DB::table('customer')->get();
		// join $tempo ke installment dan customer
		if ($request->ajax()){
			$customer = DB::table('customer')->get();
			$tempo = DB::table('tempos')->select('tempos.id','tempos.member_number','customer.name','tempo_date','loans.loan_number','tempos.keterangan',DB::raw('SUM(tempos.amount) as amount'),'tempos.created_by','keterangan')->
			where('tempos.status','=','waiting')->groupBy('tempos.member_number')->
			join('loans','tempos.member_number','=','loans.member_number')->
			join('customer','tempos.member_number','=','customer.member_number')->get();
			return DataTables::of($tempo)->make(true);
		}
		return view('tempo.pengajuan',compact('customer'));
	}
	
	public function create()
	{		
		return view('tempo.create');
	}
	
	public function kesepakatan(Request $request)
	{
		if ($request->ajax()){
			$tempo = DB::table('tempos')->select('tempos.id','tempos.member_number','customer.name','tempo_date','loans.loan_number','tempos.keterangan',DB::raw('SUM(tempos.amount) as amount'),'tempos.created_by','keterangan')->
			where('tempos.status','=','waiting')->where('is_paid',false)->groupBy('tempos.member_number')->
			join('loans','tempos.member_number','=','loans.member_number')->
			join('customer','tempos.member_number','=','customer.member_number')->get();
			return DataTables::of($tempo)->make(true);
		}
		return view ('tempo.kesepakatan');
	}
	public function berjalan(Request $request)
	{
		if ($request->ajax()){
			$tempo = DB::table('tempos')->select('tempos.id','tempos.member_number','customer.name','tempo_date','loans.loan_number','tempos.keterangan',DB::raw('SUM(tempos.amount) as amount'),DB::raw('SUM(tempos.rate_count) as rate_count'),'tempos.created_by',DB::raw('SUM(tempos.total_amount) as total_amount'),'keterangan','inst_to')->
			where('tempos.status','=','confirm')->where('is_paid',false)->groupBy('tempos.member_number')->
			join('loans','tempos.member_number','=','loans.member_number')->
			join('customer','tempos.member_number','=','customer.member_number')->get();
			return DataTables::of($tempo)->make(true);
		}
		return view ('tempo.berjalan');
	}
	
	public function store(Request $request)
	{	
		$users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;
		$tempo_date = Carbon::now()->format('Y-m-d');
		$amount = str_replace('.', '', $request->amount);
		$rates_count = $amount * ($request->bunga/100); 
		$total_amount = $amount + $rates_count;
		$status = "waiting";
		$findCustomer = Customer::where('member_number', $request->customer)->first();
		$findInstallment = Installment::where('member_number',$request->customer)->where('status','UNPAID')->first();
		$findLoan = Loan::where('member_number',$request->customer)->first();
		$payDate = explode(',', $findLoan->pay_date);
		$countPayDate = count($payDate);
		$keterangan = "Pencairan pinjaman tempo A/N ".$findCustomer->name;
		DB::beginTransaction();

		try {
				$tempos = Tempo::create([ 
					'cust_id' => $findCustomer->id,
					'tempo_date' => $tempo_date,
					'inst_to' => $findInstallment->inst_to,
					'member_number' => $request->customer,
					'branch' => $companyID,
					'amount' => $amount,
					'rates' => $request->bunga,
					'rate_count' => $rates_count,
					'total_amount' => $total_amount,
					'status' => $status,
					'created_by' => auth()->user()->name,
					'keterangan' => $keterangan,
					'is_paid' => false,
				]);
			DB::commit();
			return redirect()->back()->with('success', 'created tempo successfully');	
		}catch(\Exception $e){
			DB::rollback();
			return redirect()->back()->with('errors', $e->getMessage());
		}
		
	}
	
	public function update(Request $request, $id)
    {
		try{

			$findTempo = Tempo::where('member_number', $id)->where('status','waiting')->where('is_paid',false)->get();
			$loansUpdate = Loan::where('member_number',$id)->first();
				foreach($findTempo as $findTemp) {
					$findTemp->status = "confirm";
					$findTemp->save();
					$loansUpdate->loan_remaining = $loansUpdate->loan_remaining + $findTemp->total_amount;
					$loansUpdate->save();
				}
            if (count($findTempo) > 0) {
                $installment = Installment::where('member_number', $id)
                    ->where('pay_date', null)
                    ->where('status', 'UNPAID')
                    ->where('inst_to', $findTempo->first()->inst_to)
                    ->first();

                if ($installment) {
                    $installment->is_tempo = 1;
                    $installment->save();
                }
            }

            $this->tempoHistoryTrx($id);
				DB::commit();
				return redirect()->back()->with('success', 'confirm tempo successfully');	
		}catch(\Exception $e){
			DB::rollback();
			return redirect()->back()->with('errors', $e->getMessage());
		}
		return redirect('/transaction/tempo')->with('success', 'Update successfully');
    }
	public function reject(Request $request, $id)
    {
		$findTempo = Tempo::where('id', $id)->first();
		$findTempo->status = "reject";
		$findTempo->save();

		return redirect('/transaction/tempo/kesepakatan')->with('success', 'reject successfully');
    }

    public function delete($id)
    {
		return redirect()->back()->with('success', 'Deleted successfully');
    }
	
	public function tempoHistoryTrx($id)
    {
		$users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;
		DB::beginTransaction();
		$data = Tempo::where('member_number',$id)->where('status','confirm')->where('is_paid',false)->first();
		$amountTotal = Tempo::where('member_number',$id)->where('status','confirm')->where('is_paid',false)->sum('total_amount');
		$customer = Customer::where('member_number', $data->member_number)->first();
		$loanNumber = Loan::where('member_number', $data->member_number)->first();

		$trxnumber = Transaction::max('id');
		$trxnumber = $trxnumber + 1;

		$transactions = [[
			'trx_no' => 'TRX'.now()->format('Ymd').($trxnumber),
			'date_trx' => now()->format('Y-m-d'),
			'account' => '140-01',
			'branch' => $companyID,
			'amount' => $amountTotal,
			'description' => 'Pencairan Pinjaman Tempo '.$loanNumber->loan_number.'( '.$customer->name.' )',
			'status' => 'd',
			'jenis' => "TMP",
			'acc_by' => auth()->user()->name,
		],[
			'trx_no' => 'TRX'.now()->format('Ymd').($trxnumber+1),
			'date_trx' => now()->format('Y-m-d'),
			'account' => '100-01',
			'branch' => $companyID,
			'amount' => $amountTotal,
			'description' => 'Pencairan Pinjaman Tempo '.$loanNumber->loan_number.'( '.$customer->name.' )',
			'status' => 'k',
			'jenis' => "TMP",
			'acc_by' => auth()->user()->name,
		],
	];
	try {
		Transaction::insert($transactions);
		for ($i = 0; $i<=(count($transactions)-1);$i++){
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
					$accountBalance->end_balance = (int) $accountBalance->end_balance - $transactions[$i]['amount'];
					$accountBalance->updated_at = now();
					$accountBalance->save();
				}
			}
		}
		DB::commit();
		}catch(\Exception $e){
			DB::rollback();
			return $e->getMessage();
		}
	}
}
