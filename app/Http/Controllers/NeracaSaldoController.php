<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountGroup;
use App\Models\Journal;
use App\Models\Customer;
use App\Models\Company;
use App\Models\User;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use PDF;
use TPDF;
use Validator;
use Auth;
use App\Helper\Terbilang;
use App\Models\BalanceAccount;
use App\Models\BalanceHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NeracaSaldoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index()
    {
		return view('neracasaldo.index');
	}
    public function index_saldo()
    {
		return view('neracasaldo.index_saldo');
	}
	
	public function detail(Request $request)
    {
        $users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;

        $start_date = $request->start_date;
        $formatted_start_date = date('Y-m', strtotime($start_date));
        $end_date = $request->end_date;
        
        $aktivaA  = 1;
        $aktivaB  = 2;
        $aktivaC  = 3;
        $akunD =  4;
        $akunE =  5;
        $date = Carbon::now()->format('Y-m-d');
        $aktiva = DB::table('account_balance')
            ->where('account_balance.branch', $companyID)
            ->leftJoin('transactions', function ($join) use ($formatted_start_date) {
                $join->on('account_balance.account_number', '=', 'transactions.account')
                    ->where(DB::raw("DATE_FORMAT(transactions.date_trx, '%Y-%m')"), 'like', '%' . $formatted_start_date . '%');
            })
            ->where(function ($query) use ($aktivaA) {
                $query->whereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$aktivaA]);
            })
            ->select(DB::raw("account_balance.account_number, account_balance.start_balance,account_balance.end_balance, transactions.date_trx,transaction_type as type, 
        SUM(CASE WHEN transactions.status = 'd' THEN transactions.amount ELSE 0 END) AS debit, 
        SUM(CASE WHEN transactions.status = 'k' THEN transactions.amount ELSE 0 END) AS kredit"))
            ->orderBy('account_balance.account_number', 'asc')
            ->groupBy('account_balance.account_number')
            ->get();
        // dd($aktiva);
        $pasiva = DB::table('account_balance')
            ->where('account_balance.branch', $companyID)
            ->leftJoin('transactions', function ($join) use ($formatted_start_date) {
                $join->on('account_balance.account_number', '=', 'transactions.account')
                    ->where(DB::raw("DATE_FORMAT(transactions.date_trx, '%Y-%m')"), 'like', '%' . $formatted_start_date . '%');
            })
            ->where(function ($query) use ($aktivaB,$aktivaC) {
                $query->whereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$aktivaB])
                    ->orWhereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$aktivaC]);
            })
            ->select(DB::raw("account_balance.account_number, account_balance.start_balance,account_balance.end_balance, transactions.date_trx,transaction_type as type, 
            SUM(CASE WHEN transactions.status = 'd' THEN transactions.amount ELSE 0 END) AS debit, 
            SUM(CASE WHEN transactions.status = 'k' THEN transactions.amount ELSE 0 END) AS kredit"))
            ->orderBy('account_balance.account_number', 'asc')
            ->groupBy('account_balance.account_number')
            ->get();
        // dd($pasiva);
        $dataPendapatan = DB::table('account_balance')
            ->leftJoin('transactions', function ($join) use ($formatted_start_date) {
                $join->on('account_balance.account_number', '=', 'transactions.account')
                    ->where(DB::raw("DATE_FORMAT(transactions.date_trx, '%Y-%m')"), 'like', '%' . $formatted_start_date . '%');
            })
            ->select(DB::raw("account_balance.account_number, account_balance.start_balance,account_balance.end_balance, transactions.date_trx,transaction_type as type, 
            SUM(CASE WHEN transactions.status = 'd' THEN transactions.amount ELSE 0 END) AS debit, 
            SUM(CASE WHEN transactions.status = 'k' THEN transactions.amount ELSE 0 END) AS kredit"))
            ->where('account_balance.branch', $companyID)
            ->where(function ($query) use ($akunD) {
                $query->whereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$akunD]);
            })
            ->orderBy('account_balance.account_number', 'asc')
            ->groupBy('account_balance.account_number')
            ->get();


        $biaya = DB::table('account_balance')
            ->leftJoin('transactions', function ($join) use ($formatted_start_date) {
                $join->on('account_balance.account_number', '=', 'transactions.account')
                    ->where(DB::raw("DATE_FORMAT(transactions.date_trx, '%Y-%m')"), 'like', '%' . $formatted_start_date . '%');
            })
            ->select(DB::raw("account_balance.account_number, account_balance.start_balance,account_balance.end_balance, transactions.date_trx,transaction_type as type, 
            SUM(CASE WHEN transactions.status = 'd' THEN transactions.amount ELSE 0 END) AS debit, 
            SUM(CASE WHEN transactions.status = 'k' THEN transactions.amount ELSE 0 END) AS kredit"))
            ->where('account_balance.branch', $companyID)
            ->where(function ($query) use ($akunE) {
                $query->whereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$akunE]);
            })
            ->orderBy('account_balance.account_number', 'asc')
            ->groupBy('account_balance.account_number')
            ->get();

        return view('neracasaldo.neracasistem', compact('dataPendapatan','pasiva','aktiva','biaya','request'));
    }

    public function saveToBalanceHistory(Request $request)
    {
        $users = User::with('companies')->where('id', auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;
        $start_date = $request->start_date;
        $formatted_start_date = date('Y-m', strtotime($start_date));
        $end_date = $request->end_date;
    
        $aktivaA = 1;
        $aktivaB = 2;
        $aktivaC = 3;
        $akunD = 4;
        $akunE = 5;
        $date = Carbon::now()->format('Y-m-d');
    
        // Mengambil data aktiva
        $aktiva = DB::table('account_balance')
            ->where('account_balance.branch', $companyID)
            ->leftJoin('transactions', function ($join) use ($formatted_start_date) {
                $join->on('account_balance.account_number', '=', 'transactions.account')
                    ->where(DB::raw("DATE_FORMAT(transactions.date_trx, '%Y-%m')"), 'like', '%' . $formatted_start_date . '%');
            })
            ->where(function ($query) use ($aktivaA) {
                $query->whereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$aktivaA]);
            })
            ->select(DB::raw("account_balance.account_number, account_balance.start_balance,account_balance.end_balance, transactions.date_trx,transaction_type as type, 
        SUM(CASE WHEN transactions.status = 'd' THEN transactions.amount ELSE 0 END) AS debit, 
        SUM(CASE WHEN transactions.status = 'k' THEN transactions.amount ELSE 0 END) AS kredit"))
            ->orderBy('account_balance.account_number', 'asc')
            ->groupBy('account_balance.account_number')
            ->get();

        // Mengambil data pasiva
        $pasiva = DB::table('account_balance')
            ->where('account_balance.branch', $companyID)
            ->leftJoin('transactions', function ($join) use ($formatted_start_date) {
                $join->on('account_balance.account_number', '=', 'transactions.account')
                    ->where(DB::raw("DATE_FORMAT(transactions.date_trx, '%Y-%m')"), 'like', '%' . $formatted_start_date . '%');
            })
            ->where(function ($query) use ($aktivaB,$aktivaC) {
                $query->whereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$aktivaB])
                    ->orWhereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$aktivaC]);
            })
            ->select(DB::raw("account_balance.account_number, account_balance.start_balance,account_balance.end_balance, transactions.date_trx,transaction_type as type, 
            SUM(CASE WHEN transactions.status = 'd' THEN transactions.amount ELSE 0 END) AS debit, 
            SUM(CASE WHEN transactions.status = 'k' THEN transactions.amount ELSE 0 END) AS kredit"))
            ->orderBy('account_balance.account_number', 'asc')
            ->groupBy('account_balance.account_number')
            ->get();

        // Mengambil data pendapatan
        $dataPendapatan = DB::table('account_balance')
            ->leftJoin('transactions', function ($join) use ($formatted_start_date) {
                $join->on('account_balance.account_number', '=', 'transactions.account')
                    ->where(DB::raw("DATE_FORMAT(transactions.date_trx, '%Y-%m')"), 'like', '%' . $formatted_start_date . '%');
            })
            ->select(DB::raw("account_balance.account_number, account_balance.start_balance,account_balance.end_balance, transactions.date_trx,transaction_type as type, 
            SUM(CASE WHEN transactions.status = 'd' THEN transactions.amount ELSE 0 END) AS debit, 
            SUM(CASE WHEN transactions.status = 'k' THEN transactions.amount ELSE 0 END) AS kredit"))
            ->where('account_balance.branch', $companyID)
            ->where(function ($query) use ($akunD) {
                $query->whereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$akunD]);
            })
            ->orderBy('account_balance.account_number', 'asc')
            ->groupBy('account_balance.account_number')
            ->get();
    
        // Mengambil data biaya
        $biaya = DB::table('account_balance')
            ->leftJoin('transactions', function ($join) use ($formatted_start_date) {
                $join->on('account_balance.account_number', '=', 'transactions.account')
                    ->where(DB::raw("DATE_FORMAT(transactions.date_trx, '%Y-%m')"), 'like', '%' . $formatted_start_date . '%');
            })
            ->select(DB::raw("account_balance.account_number, account_balance.start_balance,account_balance.end_balance, transactions.date_trx,transaction_type as type, 
            SUM(CASE WHEN transactions.status = 'd' THEN transactions.amount ELSE 0 END) AS debit, 
            SUM(CASE WHEN transactions.status = 'k' THEN transactions.amount ELSE 0 END) AS kredit"))
            ->where('account_balance.branch', $companyID)
            ->where(function ($query) use ($akunE) {
                $query->whereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$akunE]);
            })
            ->orderBy('account_balance.account_number', 'asc')
            ->groupBy('account_balance.account_number')
            ->get();
    
        // // Proses perhitungan jumlah saldo dan mutasi
    
        // Simpan data ke dalam balance history untuk setiap akun
        foreach ($aktiva as $item) {
            $balance =($item->start_balance + $item->debit) - $item->kredit;
            $balanceHistory = new BalanceHistory();
            $balanceHistory->account_number = $item->account_number;
            $balanceHistory->branch = $companyID;
            $balanceHistory->trx_date = $date;
            if ($balance < 0) {
                $balanceHistory->mutasi_Debet = "0";  
                $balanceHistory->mutasi_kredit = $balance;
            } else {
                $balanceHistory->mutasi_Debet = $balance;
                $balanceHistory->mutasi_kredit = "0";
            }
            $balanceHistory->save();
            
            $findAccount = BalanceAccount::where('account_number', $item->account_number)->first();
            $findAccount->amount = $balance;
            $findAccount->start_balance = $balance;
            $findAccount->save();
        }
    
        foreach ($pasiva as $item) {
            $balanceP = ($item->start_balance + $item->kredit) - $item->debit;
            $balanceHistory = new BalanceHistory();
            $balanceHistory->account_number = $item->account_number;
            $balanceHistory->branch = $companyID;
            $balanceHistory->trx_date = $date;
            $balanceHistory->mutasi_Debet = "0";
            $balanceHistory->mutasi_kredit = $balanceP;
            $balanceHistory->save();

            $findAccount = BalanceAccount::where('account_number', $item->account_number)->first();
            $findAccount->amount = $balanceP;
            $findAccount->start_balance = $balanceP;
            $findAccount->save();
        }
    
        foreach ($dataPendapatan as $item) {
            $balanceDp = ($item->kredit - $item->debit);
            $balanceHistory = new BalanceHistory();
            $balanceHistory->account_number = $item->account_number;
            $balanceHistory->branch = $companyID;
            $balanceHistory->trx_date = $date;
            $balanceHistory->mutasi_Debet = "0";
            $balanceHistory->mutasi_kredit = $balanceDp;
            $balanceHistory->save();

            $findAccount = BalanceAccount::where('account_number', $item->account_number)->first();
            $findAccount->amount = $balanceDp;
            $findAccount->start_balance = $balanceDp;
            $findAccount->save();
        }
    
        foreach ($biaya as $item) {
            $balanceB = ($item->debit - $item->kredit);
            $balanceHistory = new BalanceHistory();
            $balanceHistory->account_number = $item->account_number;
            $balanceHistory->branch = $companyID;
            $balanceHistory->trx_date = $date;
            $balanceHistory->mutasi_Debet = $balanceB;
            $balanceHistory->mutasi_kredit = "0";
            $balanceHistory->save();

            $findAccount = BalanceAccount::where('account_number', $item->account_number)->first();
            $findAccount->amount = $balanceB;
            $findAccount->start_balance = $balanceB;
            $findAccount->save();
        }
    
        return redirect()->back()->with('success', 'Balance History berhasil disimpan');
    }

    public function neracasaldo(Request $request){
        $users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;
        $start_date = $request->start_date;
        $formatted_start_date = date('Y-m', strtotime($start_date));
        $end_date = $request->end_date;

        $aktivaA  = 1;
        $aktivaB  = 2;
        $aktivaC  = 3;
        $akunD =  4;
        $akunE =  5;
        $date = Carbon::now()->format('Y-m-d');
        $aktiva = DB::table('account_balance')
            ->where('account_balance.branch', $companyID)
            ->leftJoin('transactions', function ($join) use ($formatted_start_date) {
                $join->on('account_balance.account_number', '=', 'transactions.account')
                  ->where(DB::raw("DATE_FORMAT(transactions.date_trx, '%Y-%m')"), 'like', '%' . $formatted_start_date . '%');
            })
            ->where(function ($query) use ($aktivaA) {
                $query->whereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$aktivaA]);
            })
            ->select(DB::raw("account_balance.account_number, account_balance.start_balance,account_balance.end_balance, transactions.date_trx,transaction_type as type, 
        SUM(CASE WHEN transactions.status = 'd' THEN transactions.amount ELSE 0 END) AS debit, 
        SUM(CASE WHEN transactions.status = 'k' THEN transactions.amount ELSE 0 END) AS kredit"))
            ->orderBy('account_balance.account_number', 'asc')
            ->groupBy('account_balance.account_number')
            ->get();

        $pasiva = DB::table('account_balance')
        ->where('account_balance.branch', $companyID)
            ->leftJoin('transactions', function ($join) use ($formatted_start_date) {
                $join->on('account_balance.account_number', '=', 'transactions.account')
                  ->where(DB::raw("DATE_FORMAT(transactions.date_trx, '%Y-%m')"), 'like', '%' . $formatted_start_date . '%');
            })
        ->where(function ($query) use ($aktivaB,$aktivaC) {
            $query->whereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$aktivaB])
            ->orWhereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$aktivaC]);
        })
        ->select(DB::raw("account_balance.account_number, account_balance.start_balance,account_balance.end_balance, transactions.date_trx,transaction_type as type, 
            SUM(CASE WHEN transactions.status = 'd' THEN transactions.amount ELSE 0 END) AS debit, 
            SUM(CASE WHEN transactions.status = 'k' THEN transactions.amount ELSE 0 END) AS kredit"))
        ->orderBy('account_balance.account_number', 'asc')
        ->groupBy('account_balance.account_number')
        ->get();
        // dd($pasiva);
        $dataPendapatan = DB::table('account_balance')
            ->leftJoin('transactions', function ($join) use ($formatted_start_date) {
                $join->on('account_balance.account_number', '=', 'transactions.account')
                  ->where(DB::raw("DATE_FORMAT(transactions.date_trx, '%Y-%m')"), 'like', '%' . $formatted_start_date . '%');
            })
        ->select(DB::raw("account_balance.account_number, account_balance.start_balance,account_balance.end_balance, transactions.date_trx,transaction_type as type, 
            SUM(CASE WHEN transactions.status = 'd' THEN transactions.amount ELSE 0 END) AS debit, 
            SUM(CASE WHEN transactions.status = 'k' THEN transactions.amount ELSE 0 END) AS kredit"))
        ->where('account_balance.branch', $companyID)
        ->where(function ($query) use ($akunD) {
            $query->whereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$akunD]);
        })
        ->orderBy('account_balance.account_number', 'asc')
        ->groupBy('account_balance.account_number')
        ->get();

        // dd($dataPendapatan);

        $biaya = DB::table('account_balance')
            ->leftJoin('transactions', function ($join) use ($formatted_start_date) {
                $join->on('account_balance.account_number', '=', 'transactions.account')
                  ->where(DB::raw("DATE_FORMAT(transactions.date_trx, '%Y-%m')"), 'like', '%' . $formatted_start_date . '%');
            })
        ->select(DB::raw("account_balance.account_number, account_balance.start_balance,account_balance.end_balance, transactions.date_trx,transaction_type as type, 
            SUM(CASE WHEN transactions.status = 'd' THEN transactions.amount ELSE 0 END) AS debit, 
            SUM(CASE WHEN transactions.status = 'k' THEN transactions.amount ELSE 0 END) AS kredit"))
        ->where('account_balance.branch', $companyID)
        ->where(function ($query) use ($akunE) {
            $query->whereRaw("SUBSTRING(account_balance.account_number, 1, 1) = ?", [$akunE]);
        })
        ->orderBy('account_balance.account_number', 'asc')
        ->groupBy('account_balance.account_number')
        ->get();

        // dd($biaya);
        return view ('neracasaldo.neracasaldo',compact('dataPendapatan','pasiva','aktiva','biaya','request'));
    }
}
