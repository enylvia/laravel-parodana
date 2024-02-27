<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountGroup;
use App\Models\BalanceAccount;
use App\Models\User;
use App\Models\Employee;
use App\Models\Company;
use App\Models\TransactionType;
use App\Models\Operational;
use App\Models\Journal;
use App\Models\Transaction;
use DB;
use PDF;
use Validator;
use Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Yajra\DataTables\Facades\DataTables;
use URL;

class OperationalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index()
    {   
		$trx = TransactionType::groupBy('transaction_type')->get();
		return view('operational.index', compact('trx'));
	}
	
	public function operationalJson(Request $request)
    {
		$users = User::with('companies')->where('id',auth()->user()->id)->first();
		$companyID = $users->companies[0]->id;

		if ($request->ajax()) {
			$operational = Operational::where('branch',$companyID)->where('status','PENDING')->get();
			return Datatables::of($operational)->make(true);
		}

    }
	
	public function delete($id)
    {
		$data = Operational::findorFail($id);
		$data->delete();
		return redirect('/operational')->with('success', 'Delete successfully');
	}
	
	public function store(Request $request)
    {
		$users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;
		try {
			Operational::create([
				'mutation_date' => now()->format('Ymd'),
				'branch' => $companyID,
				'transaction_type' => $request->trxtype,
				'amount' => $request->jumlah,
				'description' => $request->ket,
				'created_by' => auth()->user()->name,
				'status' => 'PENDING'
			]);
		}catch(\Exception $e){
			return redirect('/operational')->with('error', 'Add failed');
		}
    	return redirect('/operational')->with('success', 'Add successfully');
	}

	public function approve($id)
	{
		FacadesDB::beginTransaction();
		$users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;
		$TrxOP = Operational::findorFail($id);	
		$listTRX = TransactionType::where('transaction_type',$TrxOP->transaction_type)->orderBy('tipe','ASC')->get();
		$trxnumber = Transaction::max('id');
		$trxnumber = $trxnumber + 1;
		try {
			foreach ($listTRX as $key => $trx) {
				// if ($trx->tipe == 'DEBET'){
				// 	$status = 'd';
				// 	$accountBalance = BalanceAccount::where('account_number',$trx->account_number)->where('branch',$companyID)->first();
				// 	if (is_null($accountBalance)){
				// 		BalanceAccount::create([
				// 			'mutation_date' => now()->format('Y-m-d'),
				// 			'branch' => $companyID,
				// 			'transaction_type' => $TrxOP->transaction_type,
				// 			'account_number' => $trx->account_number,
				// 			'amount' => $TrxOP->amount,
				// 			'start_balance' => $TrxOP->amount,
				// 			'end_balance' => $TrxOP->amount,
				// 		]);
				// 	}else{
				// 		$accountBalance->end_balance = $accountBalance->end_balance + $TrxOP->amount;
				// 		$accountBalance->updated_at = now();
				// 		$accountBalance->save();
				// 	}
				// 	Transaction::create([
				// 		'trx_no' => 'TRX'.now()->format('Ymd').$trxnumber,
				// 		'date_trx' => now()->format('Y-m-d'),
				// 		'account' => $trx->account_number,
				// 		'branch' => $companyID,
				// 		'amount' => $TrxOP->amount,
				// 		'status' => $status,
				// 		'description' => $TrxOP->description,
				// 		'acc_by' => auth()->user()->name
				// 	]);
				// }else{
				// 	$status = 'k';
				// 	$accountBalance = BalanceAccount::where('account_number',$trx->account_number)->where('branch',$companyID)->first();
				// 	if (is_null($accountBalance)){
				// 		BalanceAccount::create([
				// 			'mutation_date' => now()->format('Y-m-d'),
				// 			'branch' => $companyID,
				// 			'transaction_type' => $TrxOP->transaction_type,
				// 			'account_number' => $trx->account_number,
				// 			'amount' => $TrxOP->amount,
				// 			'start_balance' => (0 - $TrxOP->amount),
				// 			'end_balance' => (0 - $TrxOP->amount),
				// 		]);
				// 	}else{
				// 		$accountBalance->end_balance = ($accountBalance->end_balance - $TrxOP->amount);
				// 		$accountBalance->updated_at = now();
				// 		$accountBalance->save();
				// 	}
				// 	Transaction::create([
				// 		'trx_no' => 'TRX'.now()->format('Ymd').$trxnumber,
				// 		'date_trx' => now()->format('Y-m-d'),
				// 		'account' => $trx->account_number,
				// 		'branch' => $companyID,
				// 		'amount' => $TrxOP->amount,
				// 		'status' => $status,
				// 		'description' => $TrxOP->description,
				// 		'acc_by' => auth()->user()->name
				// 	]);
				// }
				if ($trx->tipe == 'DEBET'){
					$status = 'd';
					$codeAccount = substr($trx->account_number,0,1);
					$accountBalance = BalanceAccount::where('account_number',$trx->account_number)->where('branch',$companyID)->first();
					if ($codeAccount == '4' || $codeAccount == '3' || $codeAccount == '2') {
						if (is_null($accountBalance)){
							BalanceAccount::create([
								'mutation_date' => now()->format('Y-m-d'),
								'branch' => $companyID,
								'transaction_type' => $TrxOP->transaction_type,
								'account_number' => $trx->account_number,
								'amount' => $TrxOP->amount,
								'start_balance' => $TrxOP->amount,
								'end_balance' => $TrxOP->amount,
							]);
						}else{
							$accountBalance->end_balance = $accountBalance->end_balance - $TrxOP->amount;
							$accountBalance->updated_at = now();
							$accountBalance->save();
						}
						Transaction::create([
							'trx_no' => 'TRX'.now()->format('Ymd').$trxnumber,
							'date_trx' => now()->format('Y-m-d'),
							'account' => $trx->account_number,
							'branch' => $companyID,
							'amount' => $TrxOP->amount,
							'status' => $status,
							'description' => $TrxOP->description,
							'acc_by' => auth()->user()->name
						]);
					}else{
						if (is_null($accountBalance)){
							BalanceAccount::create([
								'mutation_date' => now()->format('Y-m-d'),
								'branch' => $companyID,
								'transaction_type' => $TrxOP->transaction_type,
								'account_number' => $trx->account_number,
								'amount' => $TrxOP->amount,
								'start_balance' => $TrxOP->amount,
								'end_balance' => $TrxOP->amount,
							]);
						}else{
							$accountBalance->end_balance = $accountBalance->end_balance + $TrxOP->amount;
							$accountBalance->updated_at = now();
							$accountBalance->save();
						}
						Transaction::create([
							'trx_no' => 'TRX'.now()->format('Ymd').$trxnumber,
							'date_trx' => now()->format('Y-m-d'),
							'account' => $trx->account_number,
							'branch' => $companyID,
							'amount' => $TrxOP->amount,
							'status' => $status,
							'description' => $TrxOP->description,
							'acc_by' => auth()->user()->name
						]);

					}
				}else{
					$status = 'k';
					$codeAccount = substr($trx->account_number,0,1);
					$accountBalance = BalanceAccount::where('account_number',$trx->account_number)->where('branch',$companyID)->first();
					if ($codeAccount == '4' || $codeAccount == '3' || $codeAccount == '2') {
						if (is_null($accountBalance)){
							BalanceAccount::create([
								'mutation_date' => now()->format('Y-m-d'),
								'branch' => $companyID,
								'transaction_type' => $TrxOP->transaction_type,
								'account_number' => $trx->account_number,
								'amount' => $TrxOP->amount,
								'start_balance' => $TrxOP->amount,
								'end_balance' => $TrxOP->amount,
							]);
						}else{
							$accountBalance->end_balance = $accountBalance->end_balance + $TrxOP->amount;
							$accountBalance->updated_at = now();
							$accountBalance->save();
						}
						Transaction::create([
							'trx_no' => 'TRX'.now()->format('Ymd').$trxnumber,
							'date_trx' => now()->format('Y-m-d'),
							'account' => $trx->account_number,
							'branch' => $companyID,
							'amount' => $TrxOP->amount,
							'status' => $status,
							'description' => $TrxOP->description,
							'acc_by' => auth()->user()->name
						]);
					}else{
						if (is_null($accountBalance)){
							BalanceAccount::create([
								'mutation_date' => now()->format('Y-m-d'),
								'branch' => $companyID,
								'transaction_type' => $TrxOP->transaction_type,
								'account_number' => $trx->account_number,
								'amount' => $TrxOP->amount,
								'start_balance' => $TrxOP->amount,
								'end_balance' => $TrxOP->amount,
							]);
						}else{
							$accountBalance->end_balance = $accountBalance->end_balance - $TrxOP->amount;
							$accountBalance->updated_at = now();
							$accountBalance->save();
						}
						Transaction::create([
							'trx_no' => 'TRX'.now()->format('Ymd').$trxnumber,
							'date_trx' => now()->format('Y-m-d'),
							'account' => $trx->account_number,
							'branch' => $companyID,
							'amount' => $TrxOP->amount,
							'status' => $status,
							'description' => $TrxOP->description,
							'acc_by' => auth()->user()->name
						]);

					}
				}
			}
			$TrxOP->status = 'APPROVED';
			$TrxOP->save();
			FacadesDB::commit();
			return redirect('/operational')->with('success', 'Update successfully');
		}catch(\Exception $e){
			FacadesDB::rollback();
			return redirect('/operational')->with('errors', $e->getMessage());
		}
	}
}
