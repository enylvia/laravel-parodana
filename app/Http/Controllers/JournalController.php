<?php

namespace App\Http\Controllers;

use App\Models\CustomerApprove;
use Illuminate\Http\Request;
use App\Models\AccountGroup;
use App\Models\BalanceHistory;
use App\Models\User;
use App\Models\Company;
use App\Models\CustomerContract;
use App\Models\CustomerInsurance;
use App\Models\Installment;
use App\Models\Journal;
use App\Models\Loan;
use App\Models\Posting;
use App\Models\Savings;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class JournalController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index()
    {
		$users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;
        return view('transaction.index');
    }
	
	public function daftarTransaction(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $account = $request->akun;

        $users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;
        // dd($tipe);
        try {
            if ($account == null){
                    $trxdata = Transaction::whereBetween('date_trx', [$start_date, $end_date])
                    ->where('amount', '!=',0)
                    ->where('branch', $companyID)
                    ->get();
            }else{
                    $trxdata = Transaction::whereBetween('date_trx', [$start_date, $end_date])
                    ->where('amount', '!=',0)
                    ->where('account_id', $account)
                    ->where('branch', $companyID)
                    ->get();
                }
            return DataTables::of($trxdata)->make(true);
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function laporan_transaksi() {
        return view('transaction.laporan_trx');
    }

    public function laporan(Request $request){
        $date_trx = $request->date_trx;
        $users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;

        // $data = DB::table('transactions')
        //     ->join('account_balance', 'transactions.account', '=', 'account_balance.account_number')
        //     ->where('transactions.date_trx', $date_trx)
        //     ->select('transactions.account', 
        //              DB::raw('SUM(CASE WHEN transactions.status = "d" THEN transactions.amount ELSE 0 END) AS debit'), 
        //              DB::raw('SUM(CASE WHEN transactions.status = "k" THEN transactions.amount ELSE 0 END) AS kredit'),'account_balance.transaction_type as account_type','transactions.status as status')
        //     ->groupBy('transactions.account')
        //     ->get();
        $data = DB::table('transactions')
            ->join('account_group', 'transactions.account', '=', 'account_group.account_number')
            ->select('account', 
                     DB::raw('SUM(CASE WHEN status = "d" THEN amount ELSE 0 END) AS debit'), 
                     DB::raw('SUM(CASE WHEN status = "k" THEN amount ELSE 0 END) AS kredit'),'account_group.account_name as account_type','transactions.status as status')
            ->groupBy('account','status')
            ->where('branch', $companyID)
            ->where('date_trx', $date_trx)
            ->get();
        $kas = DB::table('account_balance')->where('transaction_type','Kas')->first();

            // check all table installment where pay_date = due_date
            $paidData = DB::table('installment')
            ->join('customer', 'installment.member_number' ,'=', 'customer.member_number')
            ->where('installment.status', 'PAID')
            ->where('installment.branch', $companyID)
            ->where('installment.pay_date', $date_trx)
                ->orderBy('installment.inst_to','ASC')
            ->get();
            $unpaidData = DB::table('installment')
            ->join('customer', 'installment.member_number' ,'=', 'customer.member_number')
            ->join('loans', 'installment.loan_number', '=', 'loans.loan_number')
            ->where('installment.branch', $companyID)
            ->leftJoin('tempos', function($join) {
                $join->on('installment.member_number', '=', 'tempos.member_number')
                     ->where('tempos.status', '=', 'confirm');
            })
            ->join('customer_contract', 'customer.id', '=', 'customer_contract.customer_id')
            ->select('installment.member_number as member_number','installment.loan_number as loan_number','loans.pay_principal as pay_principal','loans.pay_interest as pay_rates','customer_contract.m_savings as saving','tempos.total_amount as t_tempo','customer.name as name')
            ->where('installment.status', 'UNPAID')
            ->where('installment.due_date', $date_trx)
                ->orderBy('installment.inst_to','ASC')
            ->get();

        $countPaidTunai = DB::table('installment')
            ->join('customer', 'installment.member_number', '=', 'customer.member_number')
            ->where('installment.status', 'PAID')
            ->where('installment.branch', $companyID)
            ->where('installment.pay_date', $date_trx)
            ->where('pay_method', 'Tunai')
            ->orderBy('installment.inst_to', 'ASC')
            ->count();

        $countPaidKartuDebet = DB::table('installment')
            ->join('customer', 'installment.member_number', '=', 'customer.member_number')
            ->where('installment.status', 'PAID')
            ->where('installment.branch', $companyID)
            ->where('installment.pay_date', $date_trx)
            ->where('pay_method', 'Kartu Debet')
            ->orderBy('installment.inst_to', 'ASC')
            ->count();

        $countKartuOCBC = DB::table('installment')
            ->join('customer', 'installment.member_number', '=', 'customer.member_number')
            ->where('installment.status', 'PAID')
            ->where('installment.branch', $companyID)
            ->where('installment.pay_date', $date_trx)
            ->where('pay_method', 'Kartu Debet OCBC')
            ->orderBy('installment.inst_to', 'ASC')
            ->count();

        $countKartuPermata = DB::table('installment')
            ->join('customer', 'installment.member_number', '=', 'customer.member_number')
            ->where('installment.status', 'PAID')
            ->where('installment.branch', $companyID)
            ->where('installment.pay_date', $date_trx)
            ->where('pay_method', 'Kartu Debet Permata')
            ->orderBy('installment.inst_to', 'ASC')
            ->count();


        switch ($request->get("page")) {
                case "paid" :
                    return view('transaction.report_paid', compact('data', 'paidData', 'countPaidTunai', 'countPaidKartuDebet', 'countKartuOCBC', 'countKartuPermata'));
                    break;
                case "unpaid" :
                    return view('transaction.report_unpaid',compact('data','unpaidData'));
                break;
                default:
                    return view('transaction.report_defpage',compact('data','kas'));
        }
    }

    public function running_report(Request $request) {
        $date_trx = Carbon::parse($request->date_trx);
        $firstDayOfMonth = Carbon::now()->firstOfMonth();
        // Format tanggal sesuai kebutuhan (opsional)
        $formattedDate = $firstDayOfMonth->format('Y-m-d');

        $users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;


        switch($request->get('status')){
            case 'UNPAID':
                $unpaidData = DB::table('installment')
                ->join('customer', 'installment.member_number' ,'=', 'customer.member_number')
                ->join('loans', 'installment.loan_number', '=', 'loans.loan_number')
                ->join('customer_contract', 'customer.id', '=', 'customer_contract.customer_id')
                ->where('installment.branch', $companyID)
                ->LeftJoin('tempos', function($join) {
                    $join->on('installment.member_number', '=', 'tempos.member_number')
                         ->where('tempos.status', '=', 'confirm')
                         ->where('tempos.inst_to', '=','installment.inst_to');
                })
                ->where('installment.status', 'UNPAID')
                ->where('installment.due_date','<=',$date_trx)
                ->where('installment.due_date','>=',$formattedDate)
                ->select('installment.member_number as member_number','installment.loan_number as loan_number','loans.pay_principal as pay_principal','loans.pay_interest as pay_rates','customer_contract.m_savings as saving','tempos.total_amount as t_tempo','customer.name as name')
                ->get();
    
                // dd($unpaidData);
                return view('transaction.report_unpaid_monthly',compact('unpaidData'));
                break;
            default:
                $paidData = DB::table('installment')
                ->join('customer', 'installment.member_number', '=', 'customer.member_number')
                ->where('installment.status', 'PAID')
                ->where('installment.branch', $companyID)
                ->where('installment.pay_date','<=',$date_trx)
                ->where('installment.pay_date','>=',$formattedDate)
                ->orderBy('installment.pay_date','asc')
                ->get();
                return view('transaction.report_paid_monthly',compact('paidData'));
                break;
        }
    }

    public function pinjaman_baru_report(Request $request){ 
        $date_trx = Carbon::parse($request->date_trx);
        $firstDay = Carbon::parse($request->date_trx);
        $firstDayOfMonth = $firstDay->firstOfMonth();       
        // Format tanggal sesuai kebutuhan (opsional)
        $formattedDate = $firstDayOfMonth->format('Y-m-d');

        $users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;


        $cc = CustomerContract::where('customer_contract.contract_date', '<=', $date_trx)
        ->where('customer_contract.contract_date','>=',$formattedDate)
        ->where('customer_contract.branch',$companyID)
        ->join('loans', 'customer_contract.customer_id', '=', 'loans.customer_id')
        ->join('customer', 'customer_contract.customer_id' ,'=', 'customer.id')
        ->orderBy('customer_contract.contract_date','asc')
        ->get();
        
        return view('transaction.report_pinjaman_baru',compact('cc'));
    }
    public function pinjaman_baru (){
        return view('transaction.pinjaman_baru');

    }
    public function running_index(Request $request) {
        return view('transaction.running_trx');
    }
    public function labarugi(Request $request) { 
        $keyword = '4';
        $keywordBB = '5';
        $users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $data = DB::table('balance_history')
        ->join('account_group', 'balance_history.account_number', '=', 'account_group.account_number')
        ->select('balance_history.account_number', 'account_group.account_name', 'balance_history.mutasi_debet as debit', 'balance_history.mutasi_kredit as kredit', 'balance_history.trx_date as date')
        ->where('balance_history.branch', $companyID)
        ->whereBetween('balance_history.trx_date', [$start_date, $end_date])
        ->where(function ($query) use ($keyword) {
            $query->whereRaw("SUBSTRING(balance_history.account_number, 1, 1) = ?", [$keyword]);
        })
        ->orderBy('balance_history.account_number', 'asc')
        ->groupBy('balance_history.account_number')
        ->get();
        $dataBeban = DB::table('balance_history')
        ->join('account_group', 'balance_history.account_number', '=', 'account_group.account_number')
        ->select('balance_history.account_number', 'account_group.account_name', 'balance_history.mutasi_debet as debit', 'balance_history.mutasi_kredit as kredit', 'balance_history.trx_date as date')
        ->where('balance_history.branch', $companyID)
        ->whereBetween('balance_history.trx_date', [$start_date, $end_date])
        ->where(function ($query) use ($keywordBB) {
            $query->whereRaw("SUBSTRING(balance_history.account_number, 1, 1) = ?", [$keywordBB]);
        })
        ->orderBy('balance_history.account_number', 'asc')
        ->groupBy('balance_history.account_number')
        ->get();
        // dd($data);
        return view('transaction.report_labarugi',compact('data','dataBeban'));
    }

    public function indexperubahanmodal() {
        return view('transaction.perubahan_modal');
    }
    public function perubahanmodal(Request $request){
        $users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $modal = 0;
        
        $keyword = '4';
        $keywordBB = '5';
        $total_pendapatan = 0;
        $total_beban = 0;
        $datapendapatan = DB::table('balance_history')
                ->join('account_group', 'balance_history.account_number', '=', 'account_group.account_number')
                ->select('balance_history.account_number', 'account_group.account_name', 'balance_history.mutasi_debet as debit', 'balance_history.mutasi_kredit as kredit', 'balance_history.trx_date as date')
                ->where('balance_history.branch', $companyID)
                ->whereBetween('balance_history.trx_date', [$start_date, $end_date])
                ->where(function ($query) use ($keyword) {
                    $query->whereRaw("SUBSTRING(balance_history.account_number, 1, 1) = ?", [$keyword]);
                })
                ->orderBy('balance_history.account_number', 'asc')
                ->groupBy('balance_history.account_number')
                ->get();
        $dataBeban = DB::table('balance_history')
                ->join('account_group', 'balance_history.account_number', '=', 'account_group.account_number')
                ->select('balance_history.account_number', 'account_group.account_name', 'balance_history.mutasi_debet as debit', 'balance_history.mutasi_kredit as kredit', 'balance_history.trx_date as date')
                ->where('balance_history.branch', $companyID)
                ->whereBetween('balance_history.trx_date', [$start_date, $end_date])
                ->where(function ($query) use ($keywordBB) {
                    $query->whereRaw("SUBSTRING(balance_history.account_number, 1, 1) = ?", [$keywordBB]);
                })
                ->orderBy('balance_history.account_number', 'asc')
                ->groupBy('balance_history.account_number')
                ->get();
        foreach($datapendapatan as $pendapatan){
            $total_pendapatan += $pendapatan->kredit;
        }
        foreach($dataBeban as $beban){
            $total_beban += $beban->debit;
        }

        $laba = $total_pendapatan - $total_beban;


        // BATAS
        $keyword = '2';
        $keyworda = '3';

        $data = DB::table('balance_history')
        ->join('account_group', 'balance_history.account_number', '=', 'account_group.account_number')
        ->select('balance_history.account_number', 'account_group.account_name', 'balance_history.mutasi_debet as debit', 'balance_history.mutasi_kredit as kredit', 'balance_history.trx_date as date')
        ->where('balance_history.branch', $companyID)
        ->whereBetween('balance_history.trx_date', [$start_date, $end_date])
        ->where(function ($query) use ($keyword,$keyworda) {
            $query->whereRaw("SUBSTRING(balance_history.account_number, 1, 1) = ?", [$keyword])
            ->orWhereRaw("SUBSTRING(balance_history.account_number, 1, 1) = ?", [$keyworda]);
        })
        ->orderBy('balance_history.account_number', 'asc')
        ->groupBy('balance_history.account_number')
        ->get();

        foreach($data as $d){
            if ($d->account_number == '330-01'){
                $modal += $d->kredit;
            }
        }
        return view('transaction.report_perubahanmodal',compact('data','laba','modal'));
    }
    public function posisikeuangan(){
        return view('transaction.posisi_keuangan');
    }

    public function reportPosisi(Request $request){
        $keywordA = '1';
        $keywordB = '2';
        $keywordC = '3';

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $modal = 0;

        $users = User::with('companies')->where('id',auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;

        $keyword = '4';
        $keywordBB = '5';
        $total_pendapatan = 0;
        $total_beban = 0;
        $datapendapatan = DB::table('balance_history')
                ->join('account_group', 'balance_history.account_number', '=', 'account_group.account_number')
                ->select('balance_history.account_number', 'account_group.account_name', 'balance_history.mutasi_debet as debit', 'balance_history.mutasi_kredit as kredit', 'balance_history.trx_date as date')
                ->where('balance_history.branch', $companyID)
                ->whereBetween('balance_history.trx_date', [$start_date, $end_date])
                ->where(function ($query) use ($keyword) {
                    $query->whereRaw("SUBSTRING(balance_history.account_number, 1, 1) = ?", [$keyword]);
                })
                ->orderBy('balance_history.account_number', 'asc')
                ->groupBy('balance_history.account_number')
                ->get();
        $dataBeban = DB::table('balance_history')
                ->join('account_group', 'balance_history.account_number', '=', 'account_group.account_number')
                ->select('balance_history.account_number', 'account_group.account_name', 'balance_history.mutasi_debet as debit', 'balance_history.mutasi_kredit as kredit', 'balance_history.trx_date as date')
                ->where('balance_history.branch', $companyID)
                ->whereBetween('balance_history.trx_date', [$start_date, $end_date])
                ->where(function ($query) use ($keywordBB) {
                    $query->whereRaw("SUBSTRING(balance_history.account_number, 1, 1) = ?", [$keywordBB]);
                })
                ->orderBy('balance_history.account_number', 'asc')
                ->groupBy('balance_history.account_number')
                ->get();
        foreach($datapendapatan as $pendapatan){
            $total_pendapatan += $pendapatan->kredit;
        }
        foreach($dataBeban as $beban){
            $total_beban += $beban->debit;
        }

        $laba = $total_pendapatan - $total_beban;      
        

        $aktiva = DB::table('balance_history')
        ->join('account_group', 'balance_history.account_number', '=', 'account_group.account_number')
        ->select('balance_history.account_number', 'account_group.account_name', 'balance_history.mutasi_debet as debit', 'balance_history.mutasi_kredit as kredit', 'balance_history.trx_date as date')
        ->where('balance_history.branch', $companyID)
        ->whereBetween('balance_history.trx_date', [$start_date, $end_date])
        ->where(function ($query) use ($keywordA) {
            $query->whereRaw("SUBSTRING(balance_history.account_number, 1, 1) = ?", [$keywordA]);
        })
        ->orderBy('balance_history.account_number', 'asc')
        ->groupBy('balance_history.account_number')
        ->get();

        $hutang = DB::table('balance_history')
        ->join('account_group', 'balance_history.account_number', '=', 'account_group.account_number')
        ->select('balance_history.account_number', 'account_group.account_name', 'balance_history.mutasi_debet as debit', 'balance_history.mutasi_kredit as kredit', 'balance_history.trx_date as date')
        ->where('balance_history.branch', $companyID)
        ->whereBetween('balance_history.trx_date', [$start_date, $end_date])
        ->where(function ($query) use ($keywordB,$keywordC) {
            $query->whereRaw("SUBSTRING(balance_history.account_number, 1, 1) = ?", [$keywordB])
            ->orWhereRaw("SUBSTRING(balance_history.account_number, 1, 1) = ?", [$keywordC]);
        })
        ->orderBy('balance_history.account_number', 'asc')
        ->groupBy('balance_history.account_number')
        ->get();

        foreach($hutang as $d){
            if ($d->account_number == '330-01'){
                $modal += $d->kredit;
            }
        }

        return view('transaction.report_posisikeuangan',compact('aktiva','hutang','modal','laba'));
    }

    public function baki(){
        return view('transaction.baki');
    }
    public function reportBaki(Request $request)
{
    $keyworda = '140-01';
    $keywordb = '310-02';
    $keywordc = '410-05';
    $start_date = $request->start_date;
    $end_date = $request->end_date;

    $users = User::with('companies')->where('id', auth()->user()->id)->first();
    $companyID = $users->companies[0]->id;

    $dataDebet = DB::table('account_balance')
    ->leftJoin('transactions', function ($join) use ($start_date, $end_date) {
        $join->on('account_balance.account_number', '=', 'transactions.account')
            ->whereBetween('transactions.date_trx', [$start_date, $end_date]);
    })
    ->where(function ($query) use ($keyworda, $keywordb, $keywordc) {
        $query->whereRaw("transactions.account = ?", [$keyworda])
            ->orWhereRaw("transactions.account = ?", [$keywordb])
            ->orWhereRaw("transactions.account = ?", [$keywordc]);
    })
    ->select(DB::raw("account_balance.account_number as account, account_balance.start_balance, account_balance.end_balance, transactions.date_trx, transaction_type as type, 
        SUM(CASE WHEN transactions.status = 'd' AND transactions.account = '140-01' THEN transactions.amount ELSE 0 END) AS debit_pinjaman, 
        SUM(CASE WHEN transactions.status = 'k' AND transactions.account = '140-01' THEN transactions.amount ELSE 0 END) AS kredit_pinjaman,
        SUM(CASE WHEN transactions.status = 'd' AND transactions.account = '310-02' THEN transactions.amount ELSE 0 END) AS debit_tabungan,
        SUM(CASE WHEN transactions.status = 'k' AND transactions.account = '310-02' THEN transactions.amount ELSE 0 END) AS kredit_tabungan,
        SUM(CASE WHEN transactions.status = 'd' AND transactions.account = '410-05' THEN transactions.amount ELSE 0 END) AS debit_bunga,
        SUM(CASE WHEN transactions.status = 'k' AND transactions.account = '410-05' THEN transactions.amount ELSE 0 END) AS kredit_bunga"))

    ->where('account_balance.branch', $companyID)
    ->orderBy('transactions.date_trx', 'asc')
    ->groupBy('transactions.date_trx')
    ->get();

    // dd($dataDebet);
    // Mengelompokkan data berdasarkan date_trx
    $groupedData = $dataDebet->groupBy('date_trx');
    // dd($groupedData);
    return view('transaction.report_baki',compact('groupedData','start_date','end_date'));
}
public function GetInsurance(Request $request){

    $start_date = $request->start_date;
    $end_date = $request->end_date;

    $users = User::with('companies')->where('id', auth()->user()->id)->first();
    $companyID = $users->companies[0]->id;

    if ($request->ajax()) {
        $data = CustomerInsurance::join('loans', 'loans.customer_id', '=', 'customer_insurance.customer_id')
            ->join('customer_contract', 'customer_contract.customer_id', '=', 'customer_insurance.customer_id')
            ->join('customer_approve', 'customer_approve.customer_id', '=', 'customer_insurance.customer_id')
            ->whereDate('customer_insurance.created_at', ">=", $start_date)
            ->whereDate('customer_insurance.created_at', "<=", $end_date)
            ->where('branch',$companyID)
            ->groupBy('customer_insurance.customer_id')
            ->orderBy('customer_insurance.id', 'desc')
            ->get();

        return Datatables::of($data)->make(true);
    }
    $data = CustomerInsurance::join('loans', 'loans.customer_id', '=', 'customer_insurance.customer_id')
        ->join('customer_contract', 'customer_contract.customer_id', '=', 'customer_insurance.customer_id')
        ->join('customer_approve', 'customer_approve.customer_id', '=', 'customer_insurance.customer_id')
        ->whereDate('customer_insurance.created_at', ">=", $start_date)
        ->whereDate('customer_insurance.created_at', "<=", $end_date)
        ->where('branch',$companyID)
        ->groupBy('customer_insurance.customer_id')
        ->orderBy('customer_insurance.id', 'desc')
        ->get();


    return view('transaction.report_asuransi',compact('data','request'));
}

public function insuranceIndex(){
    return view('transaction.insurance');
}
public function bukuHutang(Request $request)
{
    $loans = DB::table('loans')->where('is_created', 1)
    ->join('customer', 'customer.id', '=', 'loans.customer_id')
    ->get();
    return view('transaction.buku_hutang', compact('loans'));
}
public function getBukuHutang($id) {

    // Mengambil semua daftar angsuran yang sudah didaftarkan pada table installment
    $installment = Installment::where('loan_number', $id)
        ->orderByRaw('due_date asc')
//        ->orderByRaw('CAST(inst_to AS SIGNED) ASC')
        ->get();
    // Mengambil semua daftar angsuran yang sudah dibayarkan pada table installment
    // mengambil loan amount pada table loans  dan saving pada table saving
    $loan = Loan::where('loan_number', $id)->first();
    $customerApprove = CustomerApprove::where('customer_id',$loan->customer_id)->first();
    $getSaving = CustomerContract::where('customer_id', $loan->customer_id)->first();
    // kemudian membagi setiap loan sejumlah dengan jumlah time period dan berapa kali bayar
    // lalu mengurangi loan tersebut dengan jumlah yang sudah dibayarkan berdasarkan inst_to atau no angsuran

    // return tablenya.
    $payDate = explode(',', $loan->pay_date);
	$jumlah = count($payDate);

    $response = '<table style="width: 100%;" class="table-bordered" id="buku-hutang-table">';
    $response .= '<tr>';
    $response .= '<td rowspan="2" class="text-center">ANGSURAN KE</td>';
    $response .= '<td rowspan="2" class="text-center">NO PINJAMAN</td>';
    $response .= '<td rowspan="2" class="text-center">TANGGAL</td>';
    $response .= '<td rowspan="2" class="text-center">TANGGAL BAYAR</td>';
    $response .= '<td rowspan="2" class="text-center">BAKI</td>';
    $response .= '<td colspan="5" class="text-center">ANGSURAN</td>';
    $response .= '<td colspan="5" class="text-center">TUNGGAKAN</td>';
    $response .= '</tr>';
    $response .= '<tr>';
    $response .= '<td class="text-center">POKOK</td>';
    $response .= '<td class="text-center">BUNGA</td>';
    $response .= '<td class="text-center">TABUNGAN</td>';
    $response .= '<td class="text-center">DENDA</td>';
    $response .= '<td class="text-center">TOTAL</td>';
    $response .= '<td class="text-center">POKOK</td>';
    $response .= '<td class="text-center">BUNGA</td>';
    $response .= '<td class="text-center">TABUNGAN</td>';
    $response .= '<td class="text-center">DENDA</td>';
    $response .= '<td class="text-center">TOTAL</td>';
    $response .= '</tr>';

    if ($jumlah > 1 ) {
        $tempBaki = $loan->pay_principal * $loan->time_period;
    }else{
        $tempBaki = $loan->pay_principal * $loan->time_period;
    }

    foreach($installment as $key => $data){
        $row = $key + 1;

        if ($jumlah > 1 ) {
            $payPrincipal = $loan->pay_principal / 2 ;
            $payRates = $loan->pay_interest / 2 ;
            $paySaving = $getSaving->m_savings / 2 ;

            $total = (int)$data->pay_principal + (int)$data->pay_rates + (int)$data->saving;
            $totalBaki = (int)$data->pay_principal;
            $tempBaki = $tempBaki - $totalBaki;
            
        }else{
            $payPrincipal = $loan->pay_principal;
            $payRates = $loan->pay_interest;
            $paySaving = $getSaving->m_savings;
            $total = (int)$data->pay_principal + (int)$data->pay_rates + (int)$data->saving;
            $totalBaki = (int)$data->pay_principal;
            $tempBaki = $tempBaki - $totalBaki;

        }
        $response .= '<tr>';
        $response .= '<td class="text-center">'.$data->inst_to.'</td>';
        $response .= '<td class="text-center">'.$data->loan_number.'</td>';
        $response .= '<td class="text-center">'.$data->due_date.'</td>';
        $response .= '<td class="text-center">'.$data->pay_date.'</td>';
        if ($tempBaki <= 0){
            $response .= '<td class="text-center">'.number_format(0, 2, ',', '.').'</td>';
        }else{
            $response .= '<td class="text-center">'.number_format($tempBaki, 2, ',', '.').'</td>';
        }
        if ($data->pay_principal <= 0){
            $response .= '<td class="text-center">'.number_format(0, 2, ',', '.').'</td>';
        }else{
            $response .= '<td class="text-center">'.number_format($data->pay_principal, 2, ',', '.').'</td>';
        }
        if ($data->pay_rates <= 0){
            $response .= '<td class="text-center">'.number_format(0, 2, ',', '.').'</td>';
        }else{
            $response .= '<td class="text-center">'.number_format($data->pay_rates, 2, ',', '.').'</td>';
        }
        if ($data->saving <= 0){
            $response .= '<td class="text-center">'.number_format(0, 2, ',', '.').'</td>';
        }else{
            $response .= '<td class="text-center">'.number_format($data->saving, 2, ',', '.').'</td>';
        }
        $response .= '<td class="text-center">'.number_format(0, 2, ',', '.').'</td>';
        if ($total <= 0){
            $response .= '<td class="text-center">'.number_format(0, 2, ',', '.').'</td>';
        }else{
            $response .= '<td class="text-center">'.number_format($total, 2, ',', '.').'</td>';
        }
        $totalTunggakanPrincipal = 0;
        $totalTunggakanRates = 0;
        $totalTunggakanSaving = 0;
        // TUNGGAKAN
        if ($data->status == 'PAID') {
            if ($data->pay_status == 'REPAYMENT'){
                $paidInstallment = Installment::where('trans_number',$data->trans_number)
                ->where('inst_to',$data->inst_to)
                ->where('pay_status','FREE')
                ->orderByRaw('CAST(inst_to AS SIGNED) ASC')
                ->first();
                if (!empty($paidInstallment)) {
                    $response .= '<td class="text-center">'.number_format(($paidInstallment->pay_principal + $data->pay_principal) - $payPrincipal, 2, ',', '.').'</td>';
                    $totalTunggakanPrincipal = ($paidInstallment->pay_principal + $data->pay_principal) - $payPrincipal;
                }
            }else if ($data->pay_status == 'FREE'){
                $paidInstallment = Installment::where('trans_number',$data->trans_number)
                ->where('inst_to',$data->inst_to)
                ->where('pay_status','REPAYMENT')
                ->orderByRaw('CAST(inst_to AS SIGNED) ASC')
                ->first();
                
                if (!empty($paidInstallment)) {
                    $response .= '<td class="text-center">'.number_format(($paidInstallment->pay_principal + $data->pay_principal) - $payPrincipal, 2, ',', '.').'</td>';
                    $totalTunggakanPrincipal = ($paidInstallment->pay_principal + $data->pay_principal) - $payPrincipal;
                }else{
                    $response .= '<td class="text-center">'.number_format($payPrincipal - $data->pay_principal, 2, ',', '.').'</td>';
                    $totalTunggakanPrincipal = $payPrincipal - $data->pay_principal;
                }
            }else{
                $response .= '<td class="text-center">'.number_format($payPrincipal - $data->pay_principal, 2, ',', '.').'</td>';
            }
        }else{
            $response .= '<td class="text-center">'.number_format($payPrincipal, 2, ',', '.').'</td>';
            $totalTunggakanPrincipal = $payPrincipal;
        }


        if ($data->status == 'PAID') {
            if ($data->pay_status == 'REPAYMENT'){
                $paidInstallment = Installment::where('trans_number',$data->trans_number)
                ->where('inst_to',$data->inst_to)
                ->where('pay_status','FREE')
                ->orderByRaw('CAST(inst_to AS SIGNED) ASC')
                ->first();
                if (!empty($paidInstallment)) {
                    $response .= '<td class="text-center">'.number_format(($paidInstallment->pay_rates + $data->pay_rates) - $payRates, 2, ',', '.').'</td>';
                    $totalTunggakanRates = ($paidInstallment->pay_rates + $data->pay_rates) - $payRates;
                }
            }else if ($data->pay_status == 'FREE'){
                $paidInstallment = Installment::where('trans_number',$data->trans_number)
                ->where('inst_to',$data->inst_to)
                ->where('pay_status','REPAYMENT')
                ->orderByRaw('CAST(inst_to AS SIGNED) ASC')
                ->first();
                
                if (!empty($paidInstallment)) {
                    $response .= '<td class="text-center">'.number_format(($paidInstallment->pay_rates + $data->pay_rates) - $payRates, 2, ',', '.').'</td>';
                    $totalTunggakanRates = ($paidInstallment->pay_rates + $data->pay_rates) - $payRates;
                }else{
                    $response .= '<td class="text-center">'.number_format($payRates - $data->pay_rates, 2, ',', '.').'</td>';
                    $totalTunggakanRates = $payRates - $data->pay_rates;
                }
            }else{
                $response .= '<td class="text-center">'.number_format($payRates - $data->pay_rates, 2, ',', '.').'</td>';
            }
        }else{
            $response .= '<td class="text-center">'.number_format($payRates, 2, ',', '.').'</td>';
            $totalTunggakanRates = $payRates;
        }
        if ($data->status == 'PAID') {
            if ($data->pay_status == 'REPAYMENT'){
                $paidInstallment = Installment::where('trans_number',$data->trans_number)
                ->where('inst_to',$data->inst_to)
                ->where('pay_status','FREE')
                ->orderByRaw('CAST(inst_to AS SIGNED) ASC')
                ->first();
                if (!empty($paidInstallment)) {
                    $response .= '<td class="text-center">'.number_format(($paidInstallment->saving + $data->saving) - $paySaving, 2, ',', '.').'</td>';
                    $totalTunggakanSaving = ($paidInstallment->saving + $data->saving) - $paySaving;
                }
            }else if ($data->pay_status == 'FREE'){
                $paidInstallment = Installment::where('trans_number',$data->trans_number)
                ->where('inst_to',$data->inst_to)
                ->where('pay_status','REPAYMENT')
                ->orderByRaw('CAST(inst_to AS SIGNED) ASC')
                ->first();
                
                if (!empty($paidInstallment)) {
                    $response .= '<td class="text-center">'.number_format(($paidInstallment->saving + $data->saving) - $paySaving, 2, ',', '.').'</td>';
                    $totalTunggakanSaving = ($paidInstallment->saving + $data->saving) - $paySaving;
                }else{
                    $response .= '<td class="text-center">'.number_format($paySaving - $data->saving, 2, ',', '.').'</td>';
                    $totalTunggakanSaving = $paySaving - $data->saving;
                }
            }else{
                $response .= '<td class="text-center">'.number_format($paySaving - $data->saving, 2, ',', '.').'</td>';
            }
        }else{
            $response .= '<td class="text-center">'.number_format($paySaving, 2, ',', '.').'</td>';
            $totalTunggakanSaving = $paySaving;
        }
        $response .= '<td class="text-center">'.number_format(0, 2, ',', '.').'</td>';

        $totalTunggakan = $totalTunggakanPrincipal + $totalTunggakanRates + $totalTunggakanSaving;
        $response .= '<td class="text-center">'.number_format($totalTunggakan, 2, ',', '.').'</td>';
       

        $response .= '</tr>';
    }
    
    $response .= '</table>';

    $responses = [
        'html' => $response,
        'loan' => $loan,
        'loan_amount' => $customerApprove->approve_amount,
    ];
    return response()->json($responses);
}

public function getAllTransactionTakTertagih(){
    $users = User::with('companies')->where('id', auth()->user()->id)->first();
    $companyID = $users->companies[0]->id;

    $currentDate = Carbon::now()->format('Y-m-d');
    // $unpaidData = DB::table('installment')
    //             ->join('customer', 'installment.member_number' ,'=', 'customer.member_number')
    //             ->join('loans', 'installment.loan_number', '=', 'loans.loan_number')
    //             ->join('customer_contract', 'customer.id', '=', 'customer_contract.customer_id')
    //             ->where('installment.branch', $companyID)
    //             ->LeftJoin('tempos', function($join) {
    //                 $join->on('installment.member_number', '=', 'tempos.member_number')
    //                      ->where('tempos.status', '=', 'confirm')
    //                      ->where('tempos.inst_to', '=','installment.inst_to');
    //             })
    //             ->where('installment.status', 'UNPAID')
    //             ->where('installment.due_date','<=',$currentDate)
    //             ->select('installment.member_number as member_number','installment.loan_number as loan_number','loans.pay_principal as pay_principal','loans.pay_interest as pay_rates','customer_contract.m_savings as saving','tempos.total_amount as t_tempo','customer.name as name')
    //             ->paginate(10);
    $unpaidData = DB::table('installment')
                ->join('customer', 'installment.member_number', '=', 'customer.member_number')
                ->join('loans', 'installment.loan_number', '=', 'loans.loan_number')
                ->join('customer_contract', 'customer.id', '=', 'customer_contract.customer_id')
                ->leftJoin('tempos', function ($join) use ($currentDate) {
                    $join->on('installment.member_number', '=', 'tempos.member_number')
                        ->where('tempos.status', '=', 'confirm')
                        ->where('tempos.inst_to', '=', 'installment.inst_to');
                })
                ->where('installment.branch', $companyID)
                ->where('installment.status', 'UNPAID')
                ->where('installment.due_date', '<=', $currentDate)
                ->select(
                    'installment.member_number as member_number',
                    'installment.loan_number as loan_number',
                    'loans.pay_principal as pay_principal',
                    'loans.pay_interest as pay_rates',
                    'customer_contract.m_savings as saving',
                    DB::raw('IFNULL(tempos.total_amount, 0) as t_tempo'),
                    'customer.name as name'
                )
                // ->simplePaginate(10);
                ->get();
            
    return view('transaction.report_not_running_trx',compact('unpaidData'));
    }
}