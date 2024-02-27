<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountGroup;
use App\Models\Journal;
use App\Models\User;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use Validator;

class LedgerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index()
	{
        return view('ledger.index');
	}

	public function detail(Request $request)
	{
        $users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $dataDebet = DB::table('account_balance')
        ->leftJoin('transactions', function ($join) use ($start_date, $end_date) {
            $join->on('account_balance.account_number', '=', 'transactions.account')
            ->whereBetween('transactions.date_trx', [$start_date, $end_date]);
        })
        ->select(DB::raw("account_balance.account_number as account, account_balance.start_balance,account_balance.end_balance, transactions.date_trx,transaction_type as type, 
            SUM(CASE WHEN transactions.status = 'd' THEN transactions.amount ELSE 0 END) AS debit, 
            SUM(CASE WHEN transactions.status = 'k' THEN transactions.amount ELSE 0 END) AS kredit"))
        ->where('account_balance.branch', $companyID)
        ->orderBy('account_balance.account_number', 'asc')
        ->groupBy('account_balance.account_number','transactions.date_trx')
        ->get();


        $group = AccountGroup::get();
        return view('ledger.detail', compact('dataDebet','group'));
	}
}
