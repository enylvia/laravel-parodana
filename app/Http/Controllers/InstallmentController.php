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
use App\Models\CustomerApprove;
use App\Models\Transaction;
use Carbon\Carbon;
use Validator;
use Auth;
use URL;
use TPDF;
use Yajra\Datatables\Datatables;
use Redirect;
use Illuminate\Support\Facades\DB;
use DateTime;

class InstallmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function lunas(Request $request)
    {

        $users = User::with('companies')->where('id', auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;

        if ($request->ajax()) {
            $loans = DB::table('customer')
                ->join('loans', 'customer.id', '=', 'loans.customer_id')
                ->select('customer.id', 'customer.name', 'customer.member_number', 'loans.contract_date', 'loans.start_month', 'loans.loan_number', 'loans.loan_amount', 'loans.time_period', 'loans.interest_rate', 'loans.pay_principal', 'loans.pay_interest', 'loans.pay_month', 'loans.loan_remaining', 'loans.company_id')
                ->where('loan_remaining', '<=', 0)
                ->where('customer.branch', $companyID)
                ->get();
            return Datatables::of($loans)->make(true);
        }
        return view('loan.installment.lunas');
    }
    public function index(Request $request)
    {
        //    $loans = DB::table('customer')
        //        ->join('loans', 'customer.id', '=', 'loans.customer_id')
        //        ->select('customer.id','customer.name','customer.member_number','loans.contract_date','loans.start_month','loans.loan_number','loans.loan_amount','loans.time_period','loans.interest_rate','loans.pay_principal','loans.pay_interest','loans.pay_month','loans.loan_remaining','loans.company_id')
        //        ->where('loan_remaining','>',0)
        //        ->limit(5);
        //    dd($loans);
        if ($request->ajax()) {
            $users = User::with('companies')->where('id', auth()->user()->id)->first();
            $companyID = $users->companies[0]->id;
            if (Auth::user()->hasRole('superadmin', 'pengawas')) {
                $loans = DB::table('customer')
                    ->join('loans', 'customer.id', '=', 'loans.customer_id')
                    ->select('customer.id', 'customer.name', 'customer.member_number', 'loans.is_created', 'loans.status', 'loans.contract_date', 'loans.start_month', 'loans.loan_number', 'loans.loan_amount', 'loans.time_period', 'loans.interest_rate', 'loans.pay_principal', 'loans.pay_interest', 'loans.pay_month', 'loans.loan_remaining', 'loans.company_id')
                    ->get();
            } else {
                $loans = DB::table('customer')
                    ->join('loans', 'customer.id', '=', 'loans.customer_id')
                    ->select('customer.id', 'customer.name', 'customer.member_number', 'loans.is_created', 'loans.status', 'loans.contract_date', 'loans.loan_number', 'loans.loan_amount', 'loans.time_period', 'loans.interest_rate', 'loans.pay_principal', 'loans.pay_interest', 'loans.pay_month', 'loans.loan_remaining', 'loans.company_id')
                    ->where('loans.company_id', $companyID)
                    ->where('loan_remaining', '>', 0 and 'contract_date', '!=', null)
                    ->get();
            }
            return Datatables::of($loans)->toJson();
        }
        return view('loan.installment.index');
    }

    public function create()
    {
        $start_date = Carbon::now();
        $end_date = Carbon::now()->addMonths(12);
        $tanggals = [];

        for ($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
            $tanggals[] = $date->format('d-m-Y');
        }

        //$tanggals = $period->toArray();
        $customers = Customer::all();
        $loans = Loan::all();

        return view('loan.installment.create', compact('customers', 'tanggals'));
    }

    public function store(Request $request)
    {
        // aturan Validasi //
        $validation = Validator::make($request->all(), [
            'amount' => 'required|string|max:255',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if ($validation->fails()) {
            return redirect()->back()->withInput()
                ->with('errors', $validation->errors());
        } else {
            $loans = Loan::where('member_number', $request->member_number)->get();
            foreach ($loans as $loan) {
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
            $installments['status'] = $request->status;
            //$installments->amount = str_replace('.', '', $request->amount);
            if (!empty($request->input('amount'))) {
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

    public function edit($loan_number)
    {
        //$loans = Loan::where('member_number',$member_number)->get();
        $loans = Loan::where('loan_number', $loan_number)->get();
        foreach ($loans as $loan) {
            $customers = Customer::where('member_number', $loan->member_number)->get();
        }

        return view('loan.installment.edit', compact('loans', 'customers'));
    }

    public function view($loan_number)
    {
        $loanNumber = $loan_number;
        $users = User::with('companies')->where('id', auth()->user()->id)->get();
        foreach ($users as $user) {
            foreach ($user->companies as $company) {
                $companyID = $company->id;
            }
        }
        //$time = strtotime('10/16/2003');
        //$newformat = date('d',$time);
        //dd($newformat);
        //$customers = Customer::where('member_number',$member_number)->get();
        $loans = Loan::where('loan_number', $loanNumber)->get();
        foreach ($loans as $loan) {
            $customers = Customer::where('member_number', $loan->member_number)->get();
        }
        //$loans = Customer::join('loans', 'customer.id', '=', 'loans.customer_id')
        //		->where('loans.company_id',$companyID)
        //		->where('customer.member_number',$memberNumber)
        //		->get(['customer.id','customer.name','customer.member_number','customer.address', 'loans.*']);
        return view('loan.installment.view', compact('loans', 'customers', 'loanNumber'));
        //return view('loan.installment.view')->with('loans','memberNumber', json_decode($loans,$customers,$memberNumber, true));
    }

    public function loan_update(Request $request, $member_number)
    {
        //$loan = Loan::where('member_number',$member_number)->first();
        $loan = Loan::where('member_number', $member_number)->first();
        $custID = $loan->customer_id;
        //dd($custID);
        $contract = CustomerContract::where('customer_id', '=', $custID)->first();
        //dd($request->contract_date);
        //$loan->member_number = $member_number;
        $loan->contract_date = $request->contract_date;
        $loan->start_month = $request->start_month;
        $loan->pay_date = $request->pay_date;
        $loan->loan_amount = str_replace('.', '', $request->loan_amount);
        $loan->time_period = $request->time_period;
        $loan->interest_rate = $request->interest_rate;
        $loan->pay_principal = str_replace('.', '', $request->pay_principal);
        $loan->pay_interest = str_replace('.', '', $request->pay_interest);
        $loan->pay_month = str_replace('.', '', $request->pay_month) + $contract->m_savings;
        $loan->total_principal = str_replace('.', '', $request->total_principal);
        $loan->total_interest = str_replace('.', '', $request->total_interest);
        $loan->loan_remaining = str_replace('.', '', $request->loan_remaining);
        $loan->status = "BELUM LUNAS";
        $loan->save();

        $wajib = str_replace('.', '', $request->wajib);

        CustomerContract::where('customer_id', '=', $loan->customer_id)
            ->where('contract_date', $loan->contract_date)
            ->update(['contract_date' => $loan->contract_date, 'm_savings' => $wajib]);

        return redirect()->back()->with('success', 'Update successfully');
    }

    public function table_create($loan_number)
    {
        $loanNumber = $loan_number;
        $installmentTable = Installment::where('loan_number', $loanNumber)->first();
        if (!empty($installmentTable)) {
            return redirect()->back()->with('success', 'Installment Table already created');
        } else {
            $users = User::with('companies')->where('id', auth()->user()->id)->get();
            foreach ($users as $user) {
                foreach ($user->companies as $company) {
                    $companyID = $company->id;
                }
            }
            $loans = Loan::where('loan_number',$loanNumber)->first();
		    $payDate = !empty($loans->pay_date) ? $loans->pay_date : NULL;
		    $payDate = date('d-m-Y', strtotime($payDate));

		$waktu = $loans->time_period;
		$contract = CustomerContract::where('customer_id',$loans->customer_id)->first();
		for ($bulan = 1; $bulan <= $waktu; $bulan++) {
			$date = $contract->created_at;
			$tanggal = $date->modify('+' . $bulan . ' month');
			$Y = $tanggal->format("Y");
			$m = $tanggal->format("m");
			$d = $loans->pay_date;
            $payDate = explode(',', $d);
			$jumlah = count($payDate);
                 if($jumlah > 1)
                 {
                 	Installment::create([
                 		'loan_number' => $loanNumber,
                 		'inst_to' => $bulan,
                 		'member_number' => $loans->member_number,
                 		'due_date' => $tanggal->setDate($Y, $m, $payDate[0])->format('Y-m-d'),
                 		'pay_date' => null,
                 		'pay_method' => null,
                 		'amount' => 0,
                 		'late_charge' => null,
                 		'branch' => $loans->company_id,
                 		'status' => 'UNPAID'
                 	]);
                 	Installment::create([
                 		'loan_number' => $loanNumber,
                 		'inst_to' => $bulan,
                 		'member_number' => $loans->member_number,
                 		'due_date' => $tanggal->setDate($Y, $m, $payDate[1])->format('Y-m-d'),
                 		'pay_date' => null,
                 		'pay_method' => null,
                 		'amount' => 0,
                 		'late_charge' => null,
                 		'branch' => $loans->company_id,
                 		'status' => 'UNPAID'
                 	]);
                 }else {
                 	Installment::create([
                 		'loan_number' => $loans->loan_number,
                 		'inst_to' => $bulan,
                 		'member_number' => $loans->member_number,
                 		'due_date' => $tanggal->setDate($Y, $m, $d)->format('Y-m-d'),
                 		'pay_date' => null,
                 		'pay_method' => null,
                 		'amount' => 0,
                 		'late_charge' => null,
                 		'branch' => $loans->company_id,
                 		'status' => 'UNPAID'
                 	]);

                 }
            }
        }
        $updateLoan = Loan::where('loan_number', $loanNumber)->first();
        $updateLoan->is_created = true;
        $updateLoan->save();

        return redirect()->back()->with('success', 'Table Add successfully');
    }

    public function full_store(Request $request, $id)
    {
        // dd($request->all());
        DB::beginTransaction();
        $users = User::with('companies')->where('id', auth()->user()->id)->get();
        foreach ($users as $user) {
            foreach ($user->companies as $company) {
                $companyID = $company->id;
                $companyCode = $company->company_id;
            }
        }

        $last = Installment::where('id', '<', $id)->where('reminder', '<', 0)->orderBy('id', 'desc')->first();
        if (!empty($last->id)) {
            $lastID = $last->id;
        } else {
            $lastID = 0;
        }
        $lebih = Installment::where('id', '<', $id)->where('reminder', '>', 0)->orderBy('id', 'desc')->first();
        if (!empty($lebih->id)) {
            $lebihID = $lebih->id;
        } else {
            $lebihID = 0;
        }

        $inst = "INST";
        $date = date("Y-m-d");
        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $hari = substr($date, 8, 2);
        $transNumber = $inst . $this->BuktiUnik(10);
        $installments = Installment::where('id', $id)->first();

        $loans = Loan::where('member_number', $request->memberNumber)->first();
        //$cek = Installment::where('member_number',$request->memberNumber)->where('status','PARTIAL')->first();
        $tempos = Tempo::where('member_number', $request->memberNumber)->where('is_paid', '=', false)->where('inst_to', '=', $installments->inst_to)->where('status', 'confirm')->first();
        $contractNo = $loans->contract_number;
        $contracts = CustomerContract::where('contract_number', $contractNo)->first();
        $tabunganWajib = $contracts->m_savings;

        if (!is_null($tempos)) {
            $tempos_amount =  $tempos->total_amount;
        } else {
            $tempos_amount = 0;
        }

        $transferIn = str_replace('.', '', $request->transfer_in);
        if ($transferIn < ($loans->pay_month + $tempos_amount)) {
            return redirect()->back()->with('errors', 'Uang Masuk Lebih Kecil dari Jumlah Bayar');
        }
        if (empty($tempos)) {
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
        $totalBayar =  $totalCicilan + $totalTempo + $contracts->m_savings;
        //dd($totalBayar);
        $start = now();
        $tanggal = $start->addMonth();
        $Y = $tanggal->format("Y");
        $m = $tanggal->format("m");
        $d = $tanggal->format("d");

        $payDate = explode(',', $loans->pay_date);
        $jumlah = count($payDate);

        $sisaTagihan = $totalBayar - $transferIn;
        $byrBungaTempo = $transferIn - $bungaTempo;
        if ($byrBungaTempo >= $bungaTempo) {
            $x = $byrBungaTempo - $bungaTempo;
            $b_tempo = $byrBungaTempo - $x;
        } else {
            $b_tempo = $byrBungaTempo;
        }
        $byrPokokTempo = $byrBungaTempo - $pokokTempo;
        if ($byrPokokTempo >= $pokokTempo) {
            $x = $byrPokokTempo - $pokokTempo;
            $p_tempo = $byrPokokTempo - $x;
        } else {
            //$p_tempo = $byrPokokTempo;
            $p_tempo = $pokokTempo;
        }
        $byrBungaCicil = $byrPokokTempo - $bungaCicilan;
        if ($byrBungaCicil >= $bungaCicilan) {
            $x = $byrBungaCicil - $bungaCicilan;
            $bungas = $byrBungaCicil - $x;
        } else {
            $bungas = $byrBungaCicil;
        }
        $byrPokokCicil = $byrBungaCicil - $pokokCicilan;
        if ($byrBungaCicil >= $pokokCicilan) {
            $y = $byrBungaCicil - $pokokCicilan;
            $pokoks = $byrBungaCicil - $y;
        } else {
            $pokoks = $byrBungaCicil;
            $kurangPokok = $byrPokokCicil;
        }
        $byrTabungan = $byrPokokCicil - $tabunganWajib;
        if ($pokoks < $pokokCicilan) {
            $tabungan = 0;
        } else {
            $tabungan = $tabunganWajib;
        }
        $sisaUang = $byrTabungan;
        //dd($sisaUang);

        $cekSisa = Installment::where('id', $lastID)->first();
        if (empty($cekSisa)) {
            //$awal = Installment::where('id','<',$id)->first();
            //if (!empty($awal))
            //{
            //	$idx = $awal->id;
            //	$sisaDuit = $awal->reminder;
            //} else {
            //	$idx = $awal->id;
            //	$sisaDuit = $awal->reminder;
            ///}
            $idx = NULL;
            $sisaDuit = 0;
        } else {
            $idx = $cekSisa->id;
            $sisaDuit = $cekSisa->reminder;
        }
        //dd($sisaDuit);

        if ($sisaDuit > 0) {
            $duitSisa = 0;
        } elseif ($sisaDuit < 0) {
            $rDuit = $loans->pay_month + abs($sisaDuit);
            if ($transferIn >= $rDuit) {
                $xDuit = $transferIn - abs($sisaDuit);
                $yDuit = $transferIn - $xDuit;
                $duitSisa = abs($sisaDuit) - $yDuit;
            } else {
                $xDuit = $transferIn - $rDuit;
                $duitSisa = $sisaDuit + $xDuit;
            }
        } else {
            $duitSisa = $sisaUang;
        }
        $checkDataSaving = Savings::where('member_number', $request->memberNumber)
            ->where('tipe', 'wajib')
            ->where('status', 'setor')
            ->orderBy('id', 'desc')
            ->first();

        $svg = "SVG";
        $proofNumber = $svg . $this->TabunganUnik(10);

        if ($jumlah > 1) {
            try {

                $tTabungan = $contracts->m_savings / 2;
                $bTempo = $bungaTempo;
                $pTempo = $pokokTempo;
                $tTempo = $bTempo + $pTempo;
                $bCicil = $bungaCicilan / 2;
                $pCicil = $pokokCicilan / 2;
                $tCicil = $bCicil + $pCicil;

                $installments->trans_number = $transNumber;
                $installments->pay_date = $request->pay_date;

                $installments->pay_method = $request->payment_method;
                $sisaPokok = (int)$loans->total_principal - $pokokCicilan;
                $sisaBunga = (int)$loans->total_interest - $bungaCicilan;
                $installments->pay_status = 'FULL';
                $installments->status = 'PAID';
                $installments->transfer_in = str_replace('.', '', $request->transfer_in);
                $installments->saving = str_replace('.', '', $contracts->m_savings) / 2;
                $installments->b_tempo = str_replace('.', '', $bTempo);
                $installments->p_tempo = str_replace('.', '', $pTempo);
                $installments->t_tempo = str_replace('.', '', $tTempo);
                $installments->pay_principal = $pCicil;
                $installments->pay_rates = $bCicil;
                $installments->t_installment = $tCicil;
                $installments->amount = $tTempo + $tCicil + $tTabungan;
                $installments->reminder = 0;
                $installments->sisa = 0;
                $installments->save();
                $sisaHutang = $loans->loan_remaining - $tCicil - $tTabungan;

                $savings = new Savings();
                $savings->proof_number = $proofNumber;
                $savings->member_number = $request->memberNumber;
                if ($installments->due_date >= now()) {
                    $savings->tr_date = $request->pay_date;
                } else {
                    $savings->tr_date = $request->pay_date;
                }
                $savings->branch = $companyID;
                $savings->tipe = 'wajib';
                $savings->status = 'setor';
                if (!empty($contracts->m_savings)) {
                    $savings->amount = str_replace('.', '', $contracts->m_savings / 2);
                } else {
                    $savings->amount = 0;
                }
                if ($checkDataSaving == null) {
                    $savings->end_balance = str_replace('.', '', $contracts->m_savings / 2);
                } else {
                    $savings->end_balance = str_replace('.', '', $contracts->m_savings / 2) + $checkDataSaving->end_balance;
                }
                $savings->description = "Pembayaran Tab Wajib Pinjaman " . $installments->loan_number . " Angsuran ke " . $installments->inst_to;
                $savings->created_by = auth()->user()->name;
                $savings->save();
                if (!is_null($tempos)) {
                    $tempos->is_paid = true;
                    $tempos->save();
                }
                $this->journal_installment($transNumber);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            try {
                $installments->trans_number = $transNumber;
                $installments->pay_date = $request->pay_date;
                $installments->pay_method = $request->payment_method;
                $sisaPokok = (int)$loans->total_principal - $pokokCicilan;
                $sisaBunga = (int)$loans->total_interest - $bungaCicilan;
                $installments->pay_status = 'FULL';
                $installments->status = 'PAID';
                $installments->transfer_in = str_replace('.', '', $request->transfer_in);
                $installments->saving = str_replace('.', '', $contracts->m_savings);
                $installments->b_tempo = str_replace('.', '', $bungaTempo);
                $installments->p_tempo = str_replace('.', '', $pokokTempo);
                $installments->t_tempo = str_replace('.', '', $totalTempo);
                $installments->pay_principal = $pokokCicilan;
                $installments->pay_rates = $bungaCicilan;
                $installments->t_installment = $bungaCicilan + $pokokCicilan;
                $installments->amount = $totalTempo + $bungaCicilan + $pokokCicilan + $contracts->m_savings;
                $installments->reminder = 0;
                $installments->sisa = 0;
                $installments->save();
                $sisaHutang = $loans->loan_remaining - $totalBayar;

                $savings = new Savings();
                $savings->proof_number = $proofNumber;
                $savings->member_number = $request->memberNumber;
                //$savings->tr_date = $request->pay_date;
                if ($installments->due_date >= now()) {
                    $savings->tr_date = $request->pay_date;
                } else {
                    $savings->tr_date = $request->pay_date;
                }
                $savings->branch = $companyID;
                $savings->tipe = 'wajib';
                $savings->status = 'setor';
                if (!empty($contracts->m_savings)) {
                    $savings->amount = str_replace('.', '', $contracts->m_savings);
                } else {
                    $savings->amount = 0;
                }
                if ($checkDataSaving == null) {
                    $savings->end_balance = str_replace('.', '', $contracts->m_savings);
                } else {
                    $savings->end_balance = str_replace('.', '', $contracts->m_savings) + $checkDataSaving->end_balance;
                }
                $savings->description = "Pembayaran Tab Wajib Pinjaman " . $installments->loan_number . " Angsuran ke " . $installments->inst_to;
                $savings->created_by = auth()->user()->name;
                $savings->save();
                if (!is_null($tempos)) {
                    $tempos->is_paid = true;
                    $tempos->save();
                }
                $this->journal_installment($transNumber);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        Loan::where('member_number', '=', $request->memberNumber)->update(['loan_remaining' => $sisaHutang, 'total_principal' => $sisaPokok, 'total_interest' => $sisaBunga]);
        return redirect()->route('installment.print', ['id' => $id]);
    }
    // free payment
    public function free_store(Request $request, $id)
    {
        // dd($request->all());
        //$id = $request->free_id;
        $users = User::with('companies')->where('id', auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;
        $companyCode = $users->companies[0]->code;
        // Constanta
        $inst = "INST";
        $date = date("Y-m-d");
        $transNumber = $inst . $this->BuktiUnik(10);
        $payDates = now();

        $start = now();
        $tanggal = $start->addMonth();

        $transferIn = str_replace('.', '', $request->transfer_in);
        $tagihan = str_replace('.', '', $request->tagihan);
        // End Constanta
        $awalan = Installment::where('loan_number', $request->loan_number)->where('id', '=', $id)->orderBy('id', 'desc')->first();
        $last = Installment::where('loan_number', $request->loan_number)->where('id', '<', $id)->where('reminder', '<', 0)->orderBy('id', 'desc')->first();
        if (!empty($last->id)) {
            $kurang = Installment::where('loan_number', $request->loan_number)->where('id', '<', $id)->orderBy('id', 'desc')->first();
            $lastID = $last->id;
            if (!is_null($kurang)) {
                $remind = $awalan->reminder;
            } else {
                $remind = $last->reminder;
            }
        } else {
            $kurang = Installment::where('loan_number', $request->loan_number)->where('id', '<', $id)->orderBy('id', 'desc')->first();
            $lastID = $id;
            if (!is_null($kurang)) {
                $remind = $awalan->reminder;
            } else {
                $remind = 0;
            }
        }


        $loans = Loan::where('loan_number', $request->loan_number)->first();

        // Find customer temporary and save savings
        $userID = $loans->customer_id;
        $contracts = CustomerContract::where('customer_id', $userID)->first();
        $tabunganWajib = $contracts->m_savings;
        $findInstall = Installment::where('loan_number', $request->loan_number)->where('id', $id)->first();
        $tempos = Tempo::where('member_number', $request->memberNumber)->where('status', '=', 'confirm')->where('is_paid', '=', false)->where('inst_to', '=', $findInstall->inst_to)->first();
        $sisaUang = 0;

        // cek jika ada sisa pembayaran
        $installB = Installment::where('id', $id - 1)->first();
        $amountRequest = str_replace('.', '', $request->amount);

        if (!is_null($installB)) {
            if ($installB->sisa > 0) {
                $totalPembayaran = $amountRequest + $installB->sisa;
                $amount = $totalPembayaran;
            } else {
                $amount = $amountRequest;
            }
        } else {
            $amount = $amountRequest;
        }

        $payDate = explode(',', $loans->pay_date);
        $jumlah = count($payDate);


        // count pay date for pay more than 1
        if ($jumlah > 1) {
            $sBunga = $loans->pay_interest / 2;
            $sPokok = $loans->pay_principal / 2;
            $tabunganWajib = $contracts->m_savings / 2;


            if (empty($tempos)) {
                $pokokTempo = 0;
                $bungaTempo = 0;
                $totalTempo = 0;
            } else {
                $pokokTempo = $tempos->amount;
                $bungaTempo = $tempos->rate_count;
                $totalTempo = $tempos->total_amount;
            }
        } else {
            $sBunga = $loans->pay_interest;
            $sPokok = $loans->pay_principal;

            if (empty($tempos)) {
                $pokokTempo = 0;
                $bungaTempo = 0;
                $totalTempo = 0;
            } else {
                $pokokTempo = $tempos->amount;
                $bungaTempo = $tempos->rate_count;
                $totalTempo = $tempos->total_amount;
            }
        }
        // end count pay date for pay more than 1
        $byrBungaTempo = $amount - $bungaTempo;
        if ($byrBungaTempo >= $bungaTempo) {
            $x = $byrBungaTempo - $bungaTempo;
            $b_tempo = $byrBungaTempo - $x;
        } else {
            $b_tempo = $byrBungaTempo;
        }

        $byrPokokTempo = $byrBungaTempo - $pokokTempo;
        if ($byrPokokTempo >= $pokokTempo) {
            $x = $byrPokokTempo - $pokokTempo;
            $p_tempo = $byrPokokTempo - $x;
        } else {
            //$p_tempo = $byrPokokTempo;
            $p_tempo = $pokokTempo;
        }

        $byrBungaCicil = $byrPokokTempo - $sBunga;
        if ($byrBungaCicil >= $sBunga) {
            $x = $byrBungaCicil - $sBunga;
            $bungas = $byrBungaCicil - $x;
        } else {
            $bungas = $byrBungaCicil;
        }

        $byrPokokCicil = $byrBungaCicil - $sPokok;
        if ($byrBungaCicil >= $sPokok) {
            $y = $byrBungaCicil - $sPokok;
            $pokoks = $byrBungaCicil - $y;
        } else {
            $pokoks = $byrBungaCicil;
            $kurangPokok = $byrPokokCicil;
        }

        $byrTabungan = $byrPokokCicil - $tabunganWajib;
        if ($pokoks < $sPokok) {
            $tabungan = 0;
        } else {
            $tabungan = $tabunganWajib;
        }

        $sisaUang = $byrTabungan;
        if ($sisaUang > 0) {
            $duitSisa = $sisaUang;
        } else {
            $duitSisa = $sisaUang;
        }
        DB::beginTransaction();
        if ($transferIn < $amountRequest) {
            return redirect()->back()->with('errors', 'Uang Masuk Lebih Kecil dari Jumlah Bayar');
        } else {
            $bayarLebih = $loans->pay_month * 2;
            $checkMinus = Installment::where('id', $request->free_id)->where('reminder', '<', 0)->first();
            if (!is_null($checkMinus)) {
                if ($jumlah > 1) {
                    try {
                        $loan = Loan::where('loan_number', $request->loan_number)->first();
                        $tempo = Tempo::where('member_number', $loan->member_number)->first();
                        $in = Installment::where('id', '=', $id)->where('reminder', '<', 0)->first();
                        $ins = $in->replicate();

                        $ins->trans_number = $in->trans_number;
                        $ins->member_number = $in->member_number;
                        $ins->pay_date = now()->format('Y-m-d');
                        $ins->pay_method = $request->payment_method;
                        $jmlBayar = str_replace('.', '', $amountRequest);
                        if ($jmlBayar >= $checkMinus->reminder * -1) {
                            $ins->pay_status = 'REPAYMENT';
                            $ins->status = 'PAID';
                            $ins->full_free = 'PAYMIN';
                            $ins->transfer_in = $transferIn;
                            $ins->amount = str_replace('.', '', $jmlBayar);
                            $ins->sisa = 0;
                            $ins->reminder = 0;
                        } else {
                            $ins->pay_status = 'REPAYMENT';
                            $ins->status = 'UNPAID';
                            $ins->full_free = 'PAYMIN';
                            $ins->transfer_in = $transferIn;
                            $ins->amount = str_replace('.', '', $jmlBayar);
                            $ins->sisa = str_replace('.', '', $jmlBayar - $in->reminder * -1);
                            $ins->reminder = str_replace('.', '', $jmlBayar - $in->reminder * -1);
                        }

                        if ($in->b_tempo == $bungaTempo) {
                            if ($in->p_tempo == $pokokTempo) {
                                if ($in->pay_rates == $loan->pay_interest / 2) {
                                    if ($in->pay_principal == $loan->pay_principal / 2) {
                                        if ($in->saving == ($loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) / 2) {
                                        } else {
                                            // done
                                            $currentSaving = $in->saving;
                                            $MinusSaving = ($loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) / 2 - $currentSaving;
                                            $ins->saving = $MinusSaving;
                                            $ins->b_tempo = 0;
                                            $ins->p_tempo = 0;
                                            $ins->pay_rates = 0;
                                            $ins->pay_principal = 0;
                                            $ins->t_installment = 0;
                                        }
                                    } else {
                                        $currentPayPrincipal = $in->pay_principal;
                                        $MinusPayPrincipal = $loan->pay_principal / 2 - $currentPayPrincipal;

                                        if ($jmlBayar >= $MinusPayPrincipal) {
                                            $ins->pay_principal = $MinusPayPrincipal;
                                            $ins->t_installment = $ins->pay_principal;
                                            $ins->b_tempo = 0;
                                            $ins->p_tempo = 0;
                                            $ins->pay_rates = 0;
                                            $sisaBayar = $jmlBayar - $MinusPayPrincipal;
                                            if ($sisaBayar >= ($loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) / 2) {
                                                $ins->saving = ($loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) / 2;
                                            } else {
                                                $ins->saving = $sisaBayar;
                                            }
                                        } else {
                                            $ins->pay_principal = $jmlBayar;
                                        }
                                    }
                                } else {
                                    $currentPayInterest = $in->pay_rates;
                                    $MinusPayInterest = $loan->pay_interest / 2 - $currentPayInterest;

                                    if ($jmlBayar >= $MinusPayInterest) {
                                        $ins->pay_rates = $MinusPayInterest;
                                        $ins->t_installment = $ins->pay_rates;
                                        $ins->b_tempo = 0;
                                        $ins->p_tempo = 0;
                                        $sisaBayar = $jmlBayar - $MinusPayInterest;
                                        if ($sisaBayar >= $loan->pay_principal / 2) {
                                            $ins->pay_principal = $loan->pay_principal / 2;
                                            $ins->t_installment = $ins->pay_rates + $ins->pay_principal;
                                            $sisaBayar = $sisaBayar - $loan->pay_principal / 2;
                                            if ($sisaBayar >= ($loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) / 2) {
                                                $ins->saving = ($loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) / 2;
                                                $ins->t_installment = $ins->pay_rates + $ins->pay_principal;
                                            } else {
                                                $ins->saving = $sisaBayar;
                                            }
                                        } else {
                                            $ins->pay_principal = $sisaBayar;
                                        }
                                    } else {
                                        $ins->pay_rates = $jmlBayar;
                                    }
                                }
                            } else {

                                $currentPayTempoPrincipal = $in->p_tempo;
                                $MinusPayTempoPrincipal = $tempo->amount - $currentPayTempoPrincipal;

                                if ($jmlBayar >= $MinusPayTempoPrincipal) {
                                    $ins->p_tempo = $MinusPayTempoPrincipal;
                                    $ins->t_tempo = $MinusPayTempoPrincipal;
                                    $ins->b_tempo = 0;
                                    $sisaBayar = $jmlBayar - $MinusPayTempoPrincipal;
                                    if ($sisaBayar >= $loan->pay_interest / 2) {
                                        $ins->pay_rates = $loan->pay_interest / 2;
                                        $ins->t_installment = $ins->pay_rates;
                                        $sisaBayar = $jmlBayar - $loan->pay_interest / 2;
                                        if ($sisaBayar >= $loan->pay_principal / 2) {
                                            $ins->pay_principal = $loan->pay_principal / 2;
                                            $ins->t_installment = $ins->pay_rates + $ins->pay_principal;
                                            $sisaBayar = $sisaBayar - $loan->pay_principal / 2;
                                            if ($sisaBayar >= ($loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) / 2) {
                                                $ins->saving = ($loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) / 2;
                                            } else {
                                                $ins->saving = $sisaBayar;
                                            }
                                        } else {
                                            $ins->pay_principal = $sisaBayar;
                                        }
                                    } else {
                                        $ins->pay_rates = $sisaBayar;
                                    }
                                } else {
                                    $ins->p_tempo = $jmlBayar;
                                }
                            }
                        } else {

                            $currentPayRatesTempo = $in->b_tempo;
                            $MinusPayRatesTempo = $tempo->rate_count - $currentPayRatesTempo;
                            if ($jmlBayar >= $MinusPayRatesTempo) {
                                $ins->b_tempo = $MinusPayRatesTempo;
                                $ins->t_tempo = $MinusPayRatesTempo;
                                $sisaBayar = $jmlBayar - $MinusPayRatesTempo;
                                if ($sisaBayar >= $tempo->amount) {
                                    $ins->p_tempo = $tempo->amount;
                                    $ins->t_tempo = $MinusPayRatesTempo + $tempo->amount;
                                    $sisaBayar = $sisaBayar - $tempo->amount;
                                    if ($sisaBayar >= $loan->pay_interest / 2) {
                                        $ins->pay_rates = $loan->pay_interest / 2;
                                        $ins->t_installment = $ins->pay_rates;
                                        $sisaBayar = $sisaBayar - $loan->pay_interest / 2;
                                        if ($sisaBayar >= $loan->pay_principal / 2) {
                                            $ins->pay_principal = $loan->pay_principal / 2;
                                            $ins->t_installment = $ins->pay_rates + $ins->pay_principal;
                                            $sisaBayar = $sisaBayar - $loan->pay_principal / 2;
                                            if ($sisaBayar >= ($loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) / 2) {
                                                $ins->saving = ($loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) / 2;
                                            } else {
                                                $ins->saving = $sisaBayar;
                                            }
                                        } else {
                                            $ins->pay_principal = $sisaBayar;
                                        }
                                    } else {
                                        $ins->pay_rates = $sisaBayar;
                                    }
                                } else {
                                    $ins->p_tempo = $sisaBayar;
                                }
                            } else {
                                $ins->b_tempo = $jmlBayar;
                            }
                        }
                        $dataSave = $ins->save();
                        $sisaHutang = $loans->loan_remaining - str_replace('.', '', $jmlBayar);
                        Loan::where('loan_number', '=', $request->loan_number)->update(['loan_remaining' => $sisaHutang]);
                        $in->reminder = 0;
                        $in->sisa = 0;
                        $in->save();
                        $checkDataSaving = Savings::where('member_number', $request->memberNumber)
                            ->where('tipe', 'wajib')
                            ->where('status', 'setor')
                            ->orderBy('id', 'desc')
                            ->first();

                        $svg = "SVG";
                        $proofNumber = $svg . $this->TabunganUnik(10);
                        $savings = new Savings();
                        $savings->proof_number = $proofNumber;
                        $savings->member_number = $request->memberNumber;
                        //$savings->tr_date = $payDates;
                        if ($payDates >= now()) {
                            $savings->tr_date = now()->format('Y-m-d');
                        } else {
                            $savings->tr_date = now()->format('Y-m-d');
                        }
                        $savings->branch = $companyID;
                        $savings->tipe = 'wajib';
                        $savings->status = 'setor';
                        if (!empty($contracts->m_savings)) {
                            $savings->amount = $ins->saving;
                        } else {
                            $savings->amount = 0;
                        }
                        if ($checkDataSaving == null) {
                            $savings->end_balance = $ins->saving;
                        } else {
                            $savings->end_balance = $checkDataSaving->end_balance + $ins->saving;
                        }
                        $savings->description = "Pembayaran Tab Wajib Pinjaman " . $ins->loan_number . " Angsuran ke " . $ins->inst_to;
                        $savings->created_by = auth()->user()->name;
                        $savings->save();

                        $this->journal_installment($ins->trans_number);
                        if (!is_null($tempos)) {
                            if ($tempos->inst_to == $ins->inst_to) {
                                $tempos->status = 'PAID';
                                $tempos->is_paid = true;
                                $tempos->save();
                            }
                        }
                        DB::commit();
                        return redirect()->route('installment.print', ['id' => $ins->id]);
                    } catch (\Exception $e) {
                        DB::rollback();
                        return redirect()->back()->with('errors', $e->getMessage());
                    }
                } else {
                    try {
                        $loan = Loan::where('loan_number', $request->loan_number)->first();
                        $tempo = Tempo::where('member_number', $loan->member_number)->first();
                        $in = Installment::where('id', '=', $id)->where('reminder', '<', 0)->first();
                        $ins = $in->replicate();

                        $ins->trans_number = $in->trans_number;
                        $ins->member_number = $in->member_number;
                        $ins->pay_date = now()->format('Y-m-d');
                        $ins->pay_method = $request->payment_method;
                        $jmlBayar = str_replace('.', '', $amountRequest);
                        if ($jmlBayar >= $checkMinus->reminder * -1) {
                            $ins->pay_status = 'REPAYMENT';
                            $ins->status = 'PAID';
                            $ins->full_free = 'PAYMIN';
                            $ins->transfer_in = $transferIn;
                            $ins->amount = str_replace('.', '', $jmlBayar);
                            $ins->sisa = 0;
                            $ins->reminder = 0;
                        } else {
                            $ins->pay_status = 'REPAYMENT';
                            $ins->status = 'UNPAID';
                            $ins->full_free = 'PAYMIN';
                            $ins->transfer_in = $transferIn;
                            $ins->amount = str_replace('.', '', $jmlBayar);
                            $ins->sisa = str_replace('.', '', $jmlBayar - $in->reminder * -1);
                            $ins->reminder = str_replace('.', '', $jmlBayar - $in->reminder * -1);
                        }

                        if ($in->b_tempo == $bungaTempo) {
                            if ($in->p_tempo == $pokokTempo) {
                                if ($in->pay_rates == $loan->pay_interest) {
                                    if ($in->pay_principal == $loan->pay_principal) {
                                        if ($in->saving == ($loan->pay_month - ($loan->pay_principal + $loan->pay_interest))) {
                                        } else {
                                            // done
                                            $currentSaving = $in->saving;
                                            $MinusSaving = ($loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) - $currentSaving;
                                            $ins->saving = $MinusSaving;
                                            $ins->t_installment = 0;
                                            $ins->pay_principal = 0;
                                            $ins->pay_rates = 0;
                                            $ins->b_tempo = 0;
                                            $ins->p_tempo = 0;
                                            $ins->t_tempo = 0;
                                        }
                                    } else {
                                        $currentPayPrincipal = $in->pay_principal;
                                        $MinusPayPrincipal = $loan->pay_principal - $currentPayPrincipal;

                                        if ($jmlBayar >= $MinusPayPrincipal) {
                                            $ins->pay_principal = $MinusPayPrincipal;
                                            $ins->t_installment = $ins->pay_principal;
                                            $ins->pay_rates = 0;
                                            $ins->b_tempo = 0;
                                            $ins->p_tempo = 0;
                                            $ins->t_tempo = 0;
                                            $sisaBayar = $jmlBayar - $MinusPayPrincipal;
                                            if ($sisaBayar >= $loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) {
                                                $ins->saving = $loan->pay_month - ($loan->pay_principal + $loan->pay_interest);
                                            } else {
                                                $ins->saving = $sisaBayar;
                                            }
                                        } else {
                                            $ins->pay_principal = $jmlBayar;
                                        }
                                    }
                                } else {
                                    $currentPayInterest = $in->pay_rates;
                                    $MinusPayInterest = $loan->pay_interest - $currentPayInterest;

                                    if ($jmlBayar >= $MinusPayInterest) {
                                        $ins->pay_rates = $MinusPayInterest;
                                        $ins->t_installment = $ins->pay_rates;
                                        $ins->b_tempo = 0;
                                        $ins->p_tempo = 0;
                                        $ins->t_tempo = 0;
                                        $sisaBayar = $jmlBayar - $MinusPayInterest;
                                        if ($sisaBayar >= $loan->pay_principal) {
                                            $ins->pay_principal = $loan->pay_principal;
                                            $ins->t_installment = $ins->pay_rates + $ins->pay_principal;
                                            $sisaBayar = $sisaBayar - $loan->pay_principal;
                                            if ($sisaBayar >= $loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) {
                                                $ins->saving = $loan->pay_month - ($loan->pay_principal + $loan->pay_interest);
                                            } else {
                                                $ins->saving = $sisaBayar;
                                            }
                                        } else {
                                            $ins->pay_principal = $sisaBayar;
                                        }
                                    } else {
                                        $ins->pay_rates = $jmlBayar;
                                    }
                                }
                            } else {

                                $currentPayTempoPrincipal = $in->p_tempo;
                                $MinusPayTempoPrincipal = $tempo->amount - $currentPayTempoPrincipal;

                                if ($jmlBayar >= $MinusPayTempoPrincipal) {
                                    $ins->b_tempo = 0;
                                    $ins->p_tempo = $MinusPayTempoPrincipal;
                                    $ins->t_tempo = $MinusPayTempoPrincipal;
                                    $sisaBayar = $jmlBayar - $MinusPayTempoPrincipal;
                                    if ($sisaBayar >= $loan->pay_interest) {
                                        $ins->pay_rates = $loan->pay_interest;
                                        $ins->t_installment = $ins->pay_rates;
                                        $sisaBayar = $jmlBayar - $loan->pay_interest;
                                        if ($sisaBayar >= $loan->pay_principal) {
                                            $ins->pay_principal = $loan->pay_principal;
                                            $ins->t_installment = $ins->pay_rates + $ins->pay_principal;
                                            $sisaBayar = $sisaBayar - $loan->pay_principal;
                                            if ($sisaBayar >= $loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) {
                                                $ins->saving = $loan->pay_month - ($loan->pay_principal + $loan->pay_interest);
                                            } else {
                                                $ins->saving = $sisaBayar;
                                            }
                                        } else {
                                            $ins->pay_principal = $sisaBayar;
                                        }
                                    } else {
                                        $ins->pay_rates = $sisaBayar;
                                    }
                                } else {
                                    $ins->p_tempo = $jmlBayar;
                                }
                            }
                        } else {

                            $currentPayRatesTempo = $in->b_tempo;
                            $MinusPayRatesTempo = $tempo->rate_count - $currentPayRatesTempo;
                            if ($jmlBayar >= $MinusPayRatesTempo) {
                                $ins->b_tempo = $MinusPayRatesTempo;
                                $ins->t_tempo = $MinusPayRatesTempo;
                                $sisaBayar = $jmlBayar - $MinusPayRatesTempo;
                                if ($sisaBayar >= $tempo->amount) {
                                    $ins->p_tempo = $tempo->amount;
                                    $ins->t_tempo = $MinusPayRatesTempo + $tempo->amount;
                                    $sisaBayar = $sisaBayar - $tempo->amount;
                                    if ($sisaBayar >= $loan->pay_interest) {
                                        $ins->pay_rates = $loan->pay_interest;
                                        $ins->t_installment = $ins->pay_rates;
                                        $sisaBayar = $sisaBayar - $loan->pay_interest;
                                        if ($sisaBayar >= $loan->pay_principal) {
                                            $ins->pay_principal = $loan->pay_principal;
                                            $ins->t_installment = $ins->pay_rates + $ins->pay_principal;
                                            $sisaBayar = $sisaBayar - $loan->pay_principal;
                                            if ($sisaBayar >= $loan->pay_month - ($loan->pay_principal + $loan->pay_interest)) {
                                                $ins->saving = $loan->pay_month - ($loan->pay_principal + $loan->pay_interest);
                                            } else {
                                                $ins->saving = $sisaBayar;
                                            }
                                        } else {
                                            $ins->pay_principal = $sisaBayar;
                                        }
                                    } else {
                                        $ins->pay_rates = $sisaBayar;
                                    }
                                } else {
                                    $ins->p_tempo = $sisaBayar;
                                }
                            } else {
                                $ins->b_tempo = $jmlBayar;
                            }
                        }
                        $dataSave = $ins->save();

                        $sisaHutang = $loans->loan_remaining - str_replace('.', '', $jmlBayar);
                        Loan::where('loan_number', '=', $request->loan_number)->update(['loan_remaining' => $sisaHutang]);
                        $in->reminder = 0;
                        $in->sisa = 0;
                        $in->save();
                        $checkDataSaving = Savings::where('member_number', $request->memberNumber)
                            ->where('tipe', 'wajib')
                            ->where('status', 'setor')
                            ->orderBy('id', 'desc')
                            ->first();

                        $svg = "SVG";
                        $proofNumber = $svg . $this->TabunganUnik(10);
                        $savings = new Savings();
                        $savings->proof_number = $proofNumber;
                        $savings->member_number = $request->memberNumber;
                        //$savings->tr_date = $payDates;
                        if ($payDates >= now()) {
                            $savings->tr_date = now()->format('Y-m-d');
                        } else {
                            $savings->tr_date = now()->format('Y-m-d');
                        }
                        $savings->branch = $companyID;
                        $savings->tipe = 'wajib';
                        $savings->status = 'setor';
                        if (!empty($contracts->m_savings)) {
                            $savings->amount = $ins->saving;
                        } else {
                            $savings->amount = 0;
                        }
                        if ($checkDataSaving == null) {
                            $savings->end_balance = $ins->saving;
                        } else {
                            $savings->end_balance = $checkDataSaving->end_balance + $ins->saving;
                        }
                        $savings->description = "Pembayaran Tab Wajib Pinjaman " . $ins->loan_number . " Angsuran ke " . $ins->inst_to;
                        $savings->created_by = auth()->user()->name;
                        $savings->save();

                        if (!is_null($tempos)) {
                            if ($tempos->inst_to == $ins->inst_to) {
                                $tempos->status = 'PAID';
                                $tempos->is_paid = true;
                                $tempos->save();
                            }
                        }
                        $this->journal_installment($ins->trans_number);
                        DB::commit();
                        return redirect()->route('installment.print', ['id' => $ins->id]);
                    } catch (\Exception $e) {
                        DB::rollback();
                        return redirect()->back()->with('errors', $e->getMessage());
                    }
                }
                //               Bayar angsuran 2x Gaji
            } else {
                if ($jumlah > 1) {
                    $bayarLebihduaKali = $loans->pay_month + $totalTempo;
                    if ($amount >= $bayarLebihduaKali and $amount < $loans->loan_remaining) {
                        try {
                            $loan = Loan::where('loan_number', $request->loan_number)->first();

                            $bagi = $amountRequest / (($loan->pay_month / 2) + $totalTempo);
                            $xbagi = ceil($bagi);

                            $t_installment = ($sPokok + $sBunga + $tabungan) * $xbagi;
                            $sisa = + ($amountRequest - $t_installment);

                            $jmlBayar = $amountRequest;
                            $ins = Installment::where('loan_number', '=', $request->loan_number)->where('status', "UNPAID")->get();
                            foreach ($ins->take($xbagi) as $in) {
                                $in->trans_number = $transNumber;
                                $in->member_number = $request->memberNumber;
                                $in->pay_date = now()->format('Y-m-d');
                                $in->pay_method = $request->payment_method;
                                if ($jmlBayar >= $loans->pay_month / 2 + $totalTempo) {
                                    $in->pay_status = 'FREE';
                                    $in->status = 'PAID';
                                    $in->transfer_in = $transferIn;
                                    $in->amount = str_replace('.', '', $loans->pay_month / 2 + $totalTempo);
                                    $in->sisa = 0;
                                    $in->reminder = 0;
                                } else {
                                    $in->pay_status = 'FREE';
                                    $in->status = 'PAID';
                                    $in->transfer_in = $transferIn;
                                    $in->amount = str_replace('.', '', $jmlBayar);
                                    $in->sisa = str_replace('.', '', $jmlBayar - ($loans->pay_month / 2) - $totalTempo);
                                    $in->reminder = str_replace('.', '', $jmlBayar - ($loans->pay_month / 2) - $totalTempo);
                                }

                                if ($jmlBayar > $totalTempo) {
                                    $in->b_tempo = $b_tempo;
                                    $in->p_tempo = $p_tempo;
                                    $in->t_tempo = $totalTempo;

                                    $sisaAmount = $jmlBayar - $totalTempo;
                                    $jmlBayar = $jmlBayar - $totalTempo;
                                    if ($sisaAmount > $sBunga) {
                                        $in->pay_rates = $sBunga;
                                        $sisaAmount = $sisaAmount - $sBunga;
                                        $jmlBayar = $jmlBayar - $sBunga;
                                        $in->t_installment = $sBunga;
                                        if ($sisaAmount > $sPokok) {
                                            $in->pay_principal = $sPokok;
                                            $sisaAmount = $sisaAmount - $sPokok;
                                            $jmlBayar = $jmlBayar - $sPokok;
                                            $in->t_installment = $sPokok + $sBunga;
                                            if ($sisaAmount > $tabunganWajib) {
                                                $in->saving = $tabunganWajib;
                                                $sisaAmount = $sisaAmount - $tabunganWajib;
                                                $jmlBayar = $jmlBayar - $tabunganWajib;
                                            } else {
                                                $in->saving = $sisaAmount;
                                                $sisaAmount = $sisaAmount - $sisaAmount;
                                                $jmlBayar = $jmlBayar - $sisaAmount;
                                            }
                                        } else {
                                            $in->pay_principal = $sisaAmount;
                                            $in->t_installment = $sisaAmount + $sBunga;
                                            $sisaAmount = $sisaAmount - $sisaAmount;
                                            $jmlBayar = $jmlBayar - $sisaAmount;
                                        }
                                    } else {
                                        $in->pay_rates = $sisaAmount;
                                        $in->t_installment = $sisaAmount;
                                        $sisaAmount = $sisaAmount - $sisaAmount;
                                        $jmlBayar = $jmlBayar - $sisaAmount;
                                    }
                                } else if ($jmlBayar < $totalTempo) {
                                    if ($jmlBayar > $b_tempo) {
                                        $in->b_tempo = $b_tempo;
                                        $sisaAmount = $jmlBayar - $b_tempo;
                                        $jmlBayar = $jmlBayar - $b_tempo;
                                        if ($sisaAmount > $p_tempo) {
                                            $in->p_tempo = $p_tempo;
                                            $sisaAmount = $sisaAmount - $p_tempo;
                                            $jmlBayar = $jmlBayar - $p_tempo;
                                        } else {
                                            $in->p_tempo = $sisaAmount;
                                            $sisaAmount = $sisaAmount - $sisaAmount;
                                            $jmlBayar = $jmlBayar - $sisaAmount;
                                        }
                                    } else {
                                        $in->b_tempo = $jmlBayar;
                                        $sisaAmount = $jmlBayar - $jmlBayar;
                                    }
                                }
                                $in->save();
                                $sisaHutang = $loans->loan_remaining - str_replace('.', '', $request->amount);
                                Loan::where('loan_number', '=', $request->loan_number)->update(['loan_remaining' => $sisaHutang]);
                                $checkDataSaving = Savings::where('member_number', $request->memberNumber)
                                    ->where('tipe', 'wajib')
                                    ->where('status', 'setor')
                                    ->orderBy('id', 'desc')
                                    ->first();
                                $svg = "SVG";
                                $proofNumber = $svg . $this->TabunganUnik(10);
                                $savings = new Savings();
                                $savings->proof_number = $proofNumber;
                                $savings->member_number = $request->memberNumber;
                                //$savings->tr_date = $payDates;
                                if ($payDates >= now()) {
                                    $savings->tr_date = now()->format('Y-m-d');
                                } else {
                                    $savings->tr_date = now()->format('Y-m-d');
                                }
                                $savings->branch = $companyID;
                                $savings->tipe = 'wajib';
                                $savings->status = 'setor';
                                if (!empty($contracts->m_savings)) {
                                    $savings->amount = $in->saving;
                                } else {
                                    $savings->amount = 0;
                                }
                                if ($checkDataSaving == null) {
                                    $savings->end_balance = $in->saving;
                                } else {
                                    $savings->end_balance = $checkDataSaving->end_balance + $in->saving;
                                }
                                $savings->description = "Pembayaran Tab Wajib Pinjaman " . $in->loan_number . " Angsuran ke " . $in->inst_to;
                                $savings->created_by = auth()->user()->name;
                                $savings->save();
                                $this->journal_installment($transNumber);
                                if (!is_null($tempos)) {
                                    if ($tempos->inst_to == $in->inst_to) {
                                        $tempos->status = 'PAID';
                                        $tempos->is_paid = true;
                                        $tempos->save();
                                    }
                                }
                            }
                            DB::commit();
                            return redirect()->route('installment.print', ['id' => $in->id]);
                        } catch (\Exception $e) {
                            DB::rollback();
                            return redirect()->back()->with('errors', $e->getMessage());
                        }
                    }
                    // pembayaran seluruh angsuran 2x gaji
                    elseif ($amount >= $loans->loan_remaining) {
                        try {
                            $bagi = $amount / ($bayarLebihduaKali / 2) + 0.1;
                            $xbagi = ceil($bagi);
                            $ybagi = $xbagi - 1;
                            $zbagi = strlen($xbagi);
                            $xyz = $ybagi;
                            $jmlBayar = $amount / $xyz;
                            $afc  = ($jmlBayar - $bayarLebihduaKali / 2) * $ybagi;
                            $sisaBayar = $bayarLebihduaKali / 2 - $afc;

                            $ins = Installment::where('loan_number', '=', $request->loan_number)->where('status', "UNPAID")->get();
                            foreach ($ins->take($xyz) as $in) {
                                $addMore = Installment::where('id', $in->id)->first();
                                //dd($addMore);
                                $addMore->trans_number = $transNumber;
                                $addMore->member_number = $request->memberNumber;
                                $addMore->pay_date = now()->format('Y-m-d');
                                $addMore->transfer_in = str_replace('.', '', $request->transfer_in);
                                $addMore->pay_method = $request->payment_method;
                                $addMore->saving = $tabungan;
                                $addMore->b_tempo = $b_tempo;
                                $addMore->p_tempo = $p_tempo;
                                $addMore->t_tempo = $totalTempo;
                                $addMore->pay_status = 'LUNAS';
                                $addMore->status = 'PAID';
                                $addMore->pay_principal = $pokoks;
                                $a = $sPokok;
                                $addMore->pay_rates = $bungas;
                                $b = $sBunga;
                                $addMore->t_installment = $a + $b;
                                $addMore->amount = abs($jmlBayar);
                                $addMore->sisa = floor($afc);
                                $addMore->reminder = floor($afc);
                                $addMore->save();

                                $checkDataSaving = Savings::where('member_number', $request->memberNumber)
                                    ->where('tipe', 'wajib')
                                    ->where('status', 'setor')
                                    ->orderBy('id', 'desc')
                                    ->first();
                                $svg = "SVG";
                                $proofNumber = $svg . $this->TabunganUnik(10);
                                $savings = new Savings();
                                // loop for save saving 1-1
                                $savings->proof_number = $proofNumber;
                                $savings->member_number = $request->memberNumber;
                                if ($payDates >= now()) {
                                    $savings->tr_date = now()->format('Y-m-d');
                                } else {
                                    $savings->tr_date = now()->format('Y-m-d');
                                }
                                $savings->branch = $companyID;
                                $savings->tipe = 'wajib';
                                $savings->status = 'setor';
                                if (!empty($contracts->m_savings)) {
                                    $savings->amount = $addMore->saving;
                                } else {
                                    $savings->amount = 0;
                                }
                                if ($checkDataSaving == null) {
                                    $savings->end_balance = $addMore->saving;
                                } else {
                                    $savings->end_balance = $checkDataSaving->end_balance + $addMore->saving;
                                }

                                $savings->description = "Pembayaran Tab Wajib Pinjaman " . $addMore->loan_number . " Semua Angsuran ";
                                $savings->created_by = auth()->user()->name;
                                $savings->save();
                                $this->journal_installment($transNumber);
                            }
                            $sisaHutang = $loans->loan_remaining - floor($amount);
                            Loan::where('loan_number', '=', $request->loan_number)->update(['loan_remaining' => $sisaHutang]);
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            return redirect()->back()->with('errors', $e->getMessage());
                        }
                    } else if ($amountRequest < $loans->pay_month / 2 + $totalTempo) {
                        try {
                            $installs = Installment::where('id', $id)->first();
                            $installs->trans_number = $transNumber;
                            $installs->member_number = $request->memberNumber;
                            $installs->pay_date = now()->format('Y-m-d');
                            $installs->transfer_in = str_replace('.', '', $request->transfer_in);
                            $installs->pay_method = $request->payment_method;
                            if ((($loans->pay_month / 2) - ($contracts->m_savings / 2)) - $amount > 0) {
                                $tabungans = 0;
                            } else {
                                $tabungans = $contracts->m_savings / 2;
                            }

                            // alternate code for table installment
                            $checkSisa = Installment::where('loan_number', '=', $request->loan_number)->where('id', '<', $id)->orderBy('id', 'desc')->where('status', 'PAID')->first();
                            if (is_null($checkSisa)) {
                                $amountRequest = $amountRequest;
                            } else {
                                $amountRequest = $amountRequest + $checkSisa->sisa;
                            }
                            if ($amountRequest > $totalTempo) {
                                $installs->b_tempo = $b_tempo;
                                $installs->p_tempo = $p_tempo;
                                $installs->t_tempo = $totalTempo;

                                $sisaAmount = $amountRequest - $totalTempo;
                                if ($sisaAmount > $sBunga) {
                                    $installs->pay_rates = $sBunga;
                                    $sisaAmount = $sisaAmount - $sBunga;
                                    $installs->t_installment = $sBunga;
                                    if ($sisaAmount > $sPokok) {
                                        $installs->pay_principal = $sPokok;
                                        $installs->t_installment = $sBunga + $sPokok;
                                        $sisaAmount = $sisaAmount - $sPokok;
                                        if ($sisaAmount > $tabungans) {
                                            $installs->saving = $tabungans;
                                            $sisaAmount = $sisaAmount - $tabungans;
                                        } else {
                                            $installs->saving = $sisaAmount;
                                            $sisaAmount = $sisaAmount - $sisaAmount;
                                        }
                                    } else {
                                        $installs->pay_principal = $sisaAmount;
                                        $installs->t_installment = $sBunga + $sisaAmount;
                                        $sisaAmount = $sisaAmount - $sisaAmount;
                                    }
                                } else {
                                    $installs->pay_rates = $sisaAmount;
                                    $installs->t_installment = $sisaAmount;
                                    $sisaAmount = $sisaAmount - $sisaAmount;
                                }
                            } else if ($amountRequest < $totalTempo) {
                                if ($amountRequest > $b_tempo) {
                                    $installs->b_tempo = $b_tempo;
                                    $sisaAmount = $amountRequest - $b_tempo;
                                    if ($sisaAmount > $p_tempo) {
                                        $installs->p_tempo = $p_tempo;
                                        $sisaAmount = $sisaAmount - $p_tempo;
                                    } else {
                                        $installs->p_tempo = $sisaAmount;
                                        $sisaAmount = $sisaAmount - $sisaAmount;
                                    }
                                } else {
                                    $installs->b_tempo = $amountRequest;
                                    $sisaAmount = $amountRequest - $amountRequest;
                                }
                            }
                            $installs->pay_status = 'FREE';
                            $installs->full_free = 'PAYMIN';
                            $installs->status = 'PAID';
                            $installs->amount = str_replace('.', '', $request->amount);
                            $installs->sisa = floor($duitSisa);
                            $installs->reminder = floor($duitSisa);
                            $installs->save();

                            $sisaHutang = $loans->loan_remaining - str_replace('.', '', $request->amount);
                            Loan::where('loan_number', '=', $request->loan_number)->update(['loan_remaining' => $sisaHutang]);


                            if ($duitSisa > 0) {
                                Installment::where('loan_number', '=', $request->loan_number)->where('id', '<', $id)->orderBy('id', 'desc')->where('status', 'PAID')->update(['reminder' => floor(0), 'sisa' => floor(0)]);
                                $ad = Installment::where('loan_number', '=', $request->loan_number)->where('id', '<', $id)->first();
                                Installment::where('loan_number', '=', $request->loan_number)->where('id', $id)->update(['reminder' => floor($ad->reminder)]);
                            } else {
                                Installment::where('id', $lastID)->update(['reminder' => ceil($duitSisa - $remind)]);
                            }
                            $checkDataSaving = Savings::where('member_number', $request->memberNumber)
                                ->where('tipe', 'wajib')
                                ->where('status', 'setor')
                                ->orderBy('id', 'desc')
                                ->first();
                            $svg = "SVG";
                            $proofNumber = $svg . $this->TabunganUnik(10);
                            $savings = new Savings();
                            $savings->proof_number = $proofNumber;
                            $savings->member_number = $request->memberNumber;
                            //$savings->tr_date = $payDates;
                            if ($payDates >= now()) {
                                $savings->tr_date = now()->format('Y-m-d');
                            } else {
                                $savings->tr_date = now()->format('Y-m-d');
                            }
                            $savings->branch = $companyID;
                            $savings->tipe = 'wajib';
                            $savings->status = 'setor';
                            if (!empty($contracts->m_savings)) {
                                $savings->amount = $installs->saving;
                            } else {
                                $savings->amount = 0;
                            }
                            if ($checkDataSaving == null) {
                                $savings->end_balance = $installs->saving;
                            } else {
                                $savings->end_balance = (int) $checkDataSaving->end_balance + (int) str_replace('.', '', $installs->saving);
                            }
                            $savings->description = "Pembayaran Tab Wajib Pinjaman " . $installs->loan_number . " Angsuran ke " . $installs->inst_to;

                            $savings->created_by = auth()->user()->name;
                            $savings->save();
                            $this->journal_installment($transNumber);
                            if (!is_null($tempos)) {
                                if ($tempos->inst_to == $installs->inst_to) {
                                    $tempos->status = 'PAID';
                                    $tempos->is_paid = true;
                                    $tempos->save();
                                }
                            }
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            return redirect()->back()->with('errors', $e->getMessage());
                        }
                    } else if ($amountRequest >= $loans->pay_month / 2 + $totalTempo) {
                        try {
                            $loan = Loan::where('loan_number', $request->loan_number)->first();

                            $bagi = $amountRequest / (($loan->pay_month / 2) + $totalTempo);
                            $xbagi = ceil($bagi);

                            $t_installment = ($sPokok + $sBunga + $tabungan) * $xbagi;
                            $sisa = + ($amountRequest - $t_installment);

                            $jmlBayar = $amountRequest;
                            $ins = Installment::where('loan_number', '=', $request->loan_number)->where('status', "UNPAID")->get();
                            foreach ($ins->take($xbagi) as $in) {
                                $in->trans_number = $transNumber;
                                $in->member_number = $request->memberNumber;
                                $in->pay_date = now()->format('Y-m-d');
                                $in->pay_method = $request->payment_method;
                                if ($jmlBayar >= $loans->pay_month / 2 + $totalTempo) {
                                    $in->pay_status = 'FREE';
                                    $in->status = 'PAID';
                                    $in->transfer_in = $transferIn;
                                    $in->amount = str_replace('.', '', $loans->pay_month / 2 + $totalTempo);
                                    $in->sisa = 0;
                                    $in->reminder = 0;
                                } else {
                                    $in->pay_status = 'FREE';
                                    $in->status = 'PAID';
                                    $in->transfer_in = $transferIn;
                                    $in->amount = str_replace('.', '', $jmlBayar);
                                    $in->sisa = str_replace('.', '', $jmlBayar - ($loans->pay_month / 2) - $totalTempo);
                                    $in->reminder = str_replace('.', '', $jmlBayar - ($loans->pay_month / 2) - $totalTempo);
                                }

                                if ($jmlBayar > $totalTempo) {
                                    $in->b_tempo = $b_tempo;
                                    $in->p_tempo = $p_tempo;
                                    $in->t_tempo = $totalTempo;

                                    $sisaAmount = $jmlBayar - $totalTempo;
                                    $jmlBayar = $jmlBayar - $totalTempo;
                                    if ($sisaAmount > $sBunga) {
                                        $in->pay_rates = $sBunga;
                                        $sisaAmount = $sisaAmount - $sBunga;
                                        $jmlBayar = $jmlBayar - $sBunga;
                                        $in->t_installment = $sBunga;
                                        if ($sisaAmount > $sPokok) {
                                            $in->pay_principal = $sPokok;
                                            $sisaAmount = $sisaAmount - $sPokok;
                                            $jmlBayar = $jmlBayar - $sPokok;
                                            $in->t_installment = $sPokok + $sBunga;
                                            if ($sisaAmount > $tabunganWajib) {
                                                $in->saving = $tabunganWajib;
                                                $sisaAmount = $sisaAmount - $tabunganWajib;
                                                $jmlBayar = $jmlBayar - $tabunganWajib;
                                            } else {
                                                $in->saving = $sisaAmount;
                                                $jmlBayar = $jmlBayar - $sisaAmount;
                                                $sisaAmount = $sisaAmount - $sisaAmount;
                                            }
                                        } else {
                                            $in->pay_principal = $sisaAmount;
                                            $in->t_installment = $sisaAmount + $sBunga;
                                            $jmlBayar = $jmlBayar - $sisaAmount;
                                            $sisaAmount = $sisaAmount - $sisaAmount;
                                        }
                                    } else {
                                        $in->pay_rates = $sisaAmount;
                                        $in->t_installment = $sisaAmount;
                                        $jmlBayar = $jmlBayar - $sisaAmount;
                                        $sisaAmount = $sisaAmount - $sisaAmount;
                                    }
                                } else if ($jmlBayar < $totalTempo) {
                                    if ($jmlBayar > $b_tempo) {
                                        $in->b_tempo = $b_tempo;
                                        $sisaAmount = $jmlBayar - $b_tempo;
                                        $jmlBayar = $jmlBayar - $b_tempo;
                                        if ($sisaAmount > $p_tempo) {
                                            $in->p_tempo = $p_tempo;
                                            $sisaAmount = $sisaAmount - $p_tempo;
                                            $jmlBayar = $jmlBayar - $p_tempo;
                                        } else {
                                            $in->p_tempo = $sisaAmount;
                                            $jmlBayar = $jmlBayar - $sisaAmount;
                                            $sisaAmount = $sisaAmount - $sisaAmount;
                                        }
                                    } else {
                                        $in->b_tempo = $jmlBayar;
                                        $sisaAmount = $jmlBayar - $jmlBayar;
                                    }
                                }
                                $in->save();
                                $sisaHutang = $loans->loan_remaining - str_replace('.', '', $request->amount);
                                Loan::where('loan_number', '=', $request->loan_number)->update(['loan_remaining' => $sisaHutang]);
                                $checkDataSaving = Savings::where('member_number', $request->memberNumber)
                                    ->where('tipe', 'wajib')
                                    ->where('status', 'setor')
                                    ->orderBy('id', 'desc')
                                    ->first();
                                $svg = "SVG";
                                $proofNumber = $svg . $this->TabunganUnik(10);
                                $savings = new Savings();
                                $savings->proof_number = $proofNumber;
                                $savings->member_number = $request->memberNumber;
                                //$savings->tr_date = $payDates;
                                if ($payDates >= now()) {
                                    $savings->tr_date = now()->format('Y-m-d');
                                } else {
                                    $savings->tr_date = now()->format('Y-m-d');
                                }
                                $savings->branch = $companyID;
                                $savings->tipe = 'wajib';
                                $savings->status = 'setor';
                                if (!empty($contracts->m_savings)) {
                                    $savings->amount = $in->saving;
                                } else {
                                    $savings->amount = 0;
                                }
                                if ($checkDataSaving == null) {
                                    $savings->end_balance = $in->saving;
                                } else {
                                    $savings->end_balance = $checkDataSaving->end_balance + $in->saving;
                                }
                                $savings->description = "Pembayaran Tab Wajib Pinjaman " . $in->loan_number . " Angsuran ke " . $in->inst_to;
                                $savings->created_by = auth()->user()->name;
                                $savings->save();
                                $this->journal_installment($transNumber);
                                if (!is_null($tempos)) {
                                    if ($tempos->inst_to == $in->inst_to) {
                                        $tempos->status = 'PAID';
                                        $tempos->is_paid = true;
                                        $tempos->save();
                                    }
                                }
                            }
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            return redirect()->back()->with('errors', $e->getMessage());
                        }
                    }
//                    untuk melunasi sisa berdasarkan amount request. dan jika amount request kurang dari pembayaran bulanan.
                } else if ($amountRequest < $loans->pay_month + $totalTempo) {
                    try {
                        $installB = Installment::where('id', $id)->first();
                        $installB->trans_number = $transNumber;
                        $installB->member_number = $request->memberNumber;
                        $installB->pay_date = now()->format('Y-m-d');
                        $installB->transfer_in = str_replace('.', '', $request->transfer_in);
                        $installB->pay_method = $request->payment_method;
                        if (($loans->pay_month - $contracts->m_savings) - $amount > 0) {
                            $tabungans = 0;
                        } else {
                            $tabungans = $contracts->m_savings;
                        }
                        $checkSisa = Installment::where('loan_number', '=', $request->loan_number)->where('id', '<', $id)->orderBy('id', 'desc')->where('status', 'PAID')->first();
                        if (is_null($checkSisa)) {
                            $amountRequest = $amountRequest;
                        } else {
                            $amountRequest = $amountRequest + $checkSisa->sisa;
                        }
                        if ($amountRequest > $totalTempo) {
                            $installB->b_tempo = $b_tempo;
                            $installB->p_tempo = $p_tempo;
                            $installB->t_tempo = $totalTempo;

                            $sisaAmount = $amountRequest - $totalTempo;
                            if ($sisaAmount > $sBunga) {
                                $installB->pay_rates = $sBunga;
                                $sisaAmount = $sisaAmount - $sBunga;
                                $installB->t_installment = $sBunga;
                                if ($sisaAmount > $sPokok) {
                                    $installB->pay_principal = $sPokok;
                                    $installB->t_installment = $sBunga + $sPokok;
                                    $sisaAmount = $sisaAmount - $sPokok;
                                    if ($sisaAmount > $tabungans) {
                                        $installB->saving = $tabungans;
                                        $sisaAmount = $sisaAmount - $tabungans;
                                    } else {
                                        $installB->saving = $sisaAmount;
                                        $sisaAmount = $sisaAmount - $sisaAmount;
                                    }
                                } else {
                                    $installB->pay_principal = $sisaAmount;
                                    $installB->t_installment = $sisaAmount + $sBunga;
                                    $sisaAmount = $sisaAmount - $sisaAmount;
                                }
                            } else {
                                $installB->pay_rates = $sisaAmount;
                                $installB->t_installment = $sisaAmount;
                                $sisaAmount = $sisaAmount - $sisaAmount;
                            }
                        } else if ($amountRequest < $totalTempo) {
                            if ($amountRequest > $b_tempo) {
                                $installB->b_tempo = $b_tempo;
                                $sisaAmount = $amountRequest - $b_tempo;
                                if ($sisaAmount > $p_tempo) {
                                    $installB->p_tempo = $p_tempo;
                                    $sisaAmount = $sisaAmount - $p_tempo;
                                } else {
                                    $installB->p_tempo = $sisaAmount;
                                    $sisaAmount = $sisaAmount - $sisaAmount;
                                }
                            } else {
                                $installB->b_tempo = $amountRequest;
                                $sisaAmount = $amountRequest - $amountRequest;
                            }
                        }
                        $installB->pay_status = 'FREE';
                        $installB->full_free = 'PAYMIN';
                        $installB->status = 'PAID';
                        $installB->amount = str_replace('.', '', $request->amount);
                        $installB->sisa = floor($duitSisa);
                        $installB->reminder = floor($duitSisa);
                        $installB->save();

                        $sisaHutang = $loans->loan_remaining - str_replace('.', '', $request->amount);
                        Loan::where('loan_number', '=', $request->loan_number)->update(['loan_remaining' => $sisaHutang]);

                        // dd($duitSisa);
                        if ($duitSisa > 0) {
                            Installment::where('loan_number', '=', $request->loan_number)->where('id', '<', $id)->orderBy('id', 'desc')->where('status', 'PAID')->update(['reminder' => floor(0), 'sisa' => floor(0)]);
                            $ad = Installment::where('loan_number', '=', $request->loan_number)->where('id', '<', $id)->first();
                            Installment::where('loan_number', '=', $request->loan_number)->where('id', $id)->update(['reminder' => floor($ad->reminder)]);
                        } else {
                            Installment::where('id', $lastID)->update(['reminder' => ceil($duitSisa - $remind)]);
                        }
                        $checkDataSaving = Savings::where('member_number', $request->memberNumber)
                            ->where('tipe', 'wajib')
                            ->where('status', 'setor')
                            ->orderBy('id', 'desc')
                            ->first();
                        $svg = "SVG";
                        $proofNumber = $svg . $this->TabunganUnik(10);
                        $savings = new Savings();
                        $savings->proof_number = $proofNumber;
                        $savings->member_number = $request->memberNumber;
                        //$savings->tr_date = $payDates;
                        if ($payDates >= now()) {
                            $savings->tr_date = now()->format('Y-m-d');
                        } else {
                            $savings->tr_date = now()->format('Y-m-d');
                        }
                        $savings->branch = $companyID;
                        $savings->tipe = 'wajib';
                        $savings->status = 'setor';
                        if (!empty($contracts->m_savings)) {
                            $savings->amount = $installB->saving;
                        } else {
                            $savings->amount = 0;
                        }
                        if ($checkDataSaving == null) {
                            $savings->end_balance = $installB->saving;
                        } else {
                            $savings->end_balance = $checkDataSaving->end_balance + $installB->saving;
                        }
                        $savings->description = "Pembayaran Tab Wajib Pinjaman " . $installB->loan_number . " Angsuran ke " . $installB->inst_to;
                        $savings->created_by = auth()->user()->name;
                        $savings->save();

                        if (!is_null($tempos)) {
                            if ($tempos->inst_to == $installB->inst_to) {
                                $tempos->status = 'PAID';
                                $tempos->is_paid = true;
                                $tempos->save();
                            }
                        }
                        $this->journal_installment($transNumber);
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                        return redirect()->back()->with('errors', $e->getMessage());
                    }
                    // 1x pembayaran per angsuran
                } elseif ($amount >= $bayarLebih and $amount < $loans->loan_remaining) {
                    try {
                        $loan = Loan::where('loan_number', $request->loan_number)->first();

                        $bagi = $amountRequest / ($loan->pay_month + $totalTempo);
                        $xbagi = ceil($bagi);

                        $t_installment = ($sPokok + $sBunga + $tabungan) * $xbagi;
                        $sisa = + ($amountRequest - $t_installment);

                        $jmlBayar = $amountRequest;
                        $ins = Installment::where('loan_number', '=', $request->loan_number)->where('status', "UNPAID")->get();
                        foreach ($ins->take($xbagi) as $in) {
                            $in->trans_number = $transNumber;
                            $in->member_number = $request->memberNumber;
                            $in->pay_date = now()->format('Y-m-d');
                            $in->pay_method = $request->payment_method;
                            if ($jmlBayar >= $loans->pay_month + $totalTempo) {
                                $in->pay_status = 'FREE';
                                $in->status = 'PAID';
                                $in->transfer_in = $transferIn;
                                $in->amount = str_replace('.', '', $loans->pay_month + $totalTempo);
                                $in->sisa = 0;
                                $in->reminder = 0;
                            } else {
                                $in->pay_status = 'FREE';
                                $in->status = 'PAID';
                                $in->transfer_in = $transferIn;
                                $in->amount = str_replace('.', '', $jmlBayar);
                                $in->sisa = str_replace('.', '', $jmlBayar - $loans->pay_month - $totalTempo);
                                $in->reminder = str_replace('.', '', $jmlBayar - $loans->pay_month - $totalTempo);
                            }

                            if ($jmlBayar > $totalTempo) {
                                $in->b_tempo = $b_tempo;
                                $in->p_tempo = $p_tempo;
                                $in->t_tempo = $totalTempo;

                                $sisaAmount = $jmlBayar - $totalTempo;
                                $jmlBayar = $jmlBayar - $totalTempo;
                                if ($sisaAmount > $sBunga) {
                                    $in->pay_rates = $sBunga;
                                    $sisaAmount = $sisaAmount - $sBunga;
                                    $jmlBayar = $jmlBayar - $sBunga;
                                    $in->t_installment = $sBunga;
                                    if ($sisaAmount > $sPokok) {
                                        $in->pay_principal = $sPokok;
                                        $sisaAmount = $sisaAmount - $sPokok;
                                        $jmlBayar = $jmlBayar - $sPokok;
                                        $in->t_installment = $sPokok + $sBunga;
                                        if ($sisaAmount > $tabunganWajib) {
                                            $in->saving = $tabunganWajib;
                                            $sisaAmount = $sisaAmount - $tabunganWajib;
                                            $jmlBayar = $jmlBayar - $tabunganWajib;
                                        } else {
                                            $in->saving = $sisaAmount;
                                            $sisaAmount = $sisaAmount - $sisaAmount;
                                            $jmlBayar = $jmlBayar - $sisaAmount;
                                        }
                                    } else {
                                        $in->pay_principal = $sisaAmount;
                                        $in->t_installment = $sisaAmount + $sBunga;
                                        $sisaAmount = $sisaAmount - $sisaAmount;
                                        $jmlBayar = $jmlBayar - $sisaAmount;
                                    }
                                } else {
                                    $in->pay_rates = $sisaAmount;
                                    $in->t_installment = $sisaAmount;
                                    $sisaAmount = $sisaAmount - $sisaAmount;
                                    $jmlBayar = $jmlBayar - $sisaAmount;
                                }
                            } else if ($jmlBayar < $totalTempo) {
                                if ($jmlBayar > $b_tempo) {
                                    $in->b_tempo = $b_tempo;
                                    $sisaAmount = $jmlBayar - $b_tempo;
                                    $jmlBayar = $jmlBayar - $b_tempo;
                                    if ($sisaAmount > $p_tempo) {
                                        $in->p_tempo = $p_tempo;
                                        $sisaAmount = $sisaAmount - $p_tempo;
                                        $jmlBayar = $jmlBayar - $p_tempo;
                                    } else {
                                        $in->p_tempo = $sisaAmount;
                                        $sisaAmount = $sisaAmount - $sisaAmount;
                                        $jmlBayar = $jmlBayar - $sisaAmount;
                                    }
                                } else {
                                    $in->b_tempo = $jmlBayar;
                                    $sisaAmount = $jmlBayar - $jmlBayar;
                                }
                            }
                            $in->save();
                            $sisaHutang = $loans->loan_remaining - str_replace('.', '', $request->amount);
                            Loan::where('loan_number', '=', $request->loan_number)->update(['loan_remaining' => $sisaHutang]);
                            $checkDataSaving = Savings::where('member_number', $request->memberNumber)
                                ->where('tipe', 'wajib')
                                ->where('status', 'setor')
                                ->orderBy('id', 'desc')
                                ->first();
                            $svg = "SVG";
                            $proofNumber = $svg . $this->TabunganUnik(10);
                            $savings = new Savings();
                            $savings->proof_number = $proofNumber;
                            $savings->member_number = $request->memberNumber;
                            //$savings->tr_date = $payDates;
                            if ($payDates >= now()) {
                                $savings->tr_date = now()->format('Y-m-d');
                            } else {
                                $savings->tr_date = now()->format('Y-m-d');
                            }
                            $savings->branch = $companyID;
                            $savings->tipe = 'wajib';
                            $savings->status = 'setor';
                            if (!empty($contracts->m_savings)) {
                                $savings->amount = $in->saving;
                            } else {
                                $savings->amount = 0;
                            }
                            if ($checkDataSaving == null) {
                                $savings->end_balance = $in->saving;
                            } else {
                                $savings->end_balance = $checkDataSaving->end_balance + $in->saving;
                            }
                            $savings->description = "Pembayaran Tab Wajib Pinjaman " . $in->loan_number . " Angsuran ke " . $in->inst_to;
                            $savings->created_by = auth()->user()->name;
                            $savings->save();
                            $this->journal_installment($transNumber);
                            if (!is_null($tempos)) {
                                if ($tempos->inst_to == $in->inst_to) {
                                    $tempos->status = 'PAID';
                                    $tempos->is_paid = true;
                                    $tempos->save();
                                }
                            }
                        }
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                        return redirect()->back()->with('errors', $e->getMessage());
                    }
                // pembayaran angsuran seluruhny
                } elseif ($amount >= $loans->loan_remaining) {
                    try {

                        $bagi = $amount / $loans->pay_month + 0.1;
                        $xbagi = ceil($bagi);
                        $ybagi = $xbagi - 1;
                        $zbagi = strlen($xbagi);

                        $waktu = $ybagi;

                        $jmlBayar = $amount / $ybagi;
                        $afc  = ($jmlBayar - $loans->pay_month) * $ybagi;
                        $installC = Installment::where('id', $id)->first();
                        for ($bulan = 1; $bulan <= $waktu; $bulan++) {
                            if ($installC->due_date >= now()) {
                                $tanggal = now();
                            } else {
                                $tanggal = $installC->due_date;
                            }
                            $date = new DateTime($tanggal);
                            $tgl = $date->modify('+' . $bulan .  'month');
                            $Y = $tgl->format("Y");
                            $m = $tgl->format("m");
                            $d = $tgl->format("d");
                            $tglBaru = $tgl->setDate($Y, $m - 1, $d)->format('Y-m-d');

                            $ins = Installment::where('loan_number', '=', $request->loan_number)->where('due_date', $tglBaru)->get();
                            foreach ($ins as $in) {
                                $addMore = Installment::where('id', $in->id)->first();
                                //dd($addMore);
                                $addMore->trans_number = $transNumber;
                                $addMore->member_number = $request->memberNumber;
                                $addMore->pay_date = now()->format('Y-m-d');
                                $addMore->transfer_in = str_replace('.', '', $request->transfer_in);
                                $addMore->pay_method = $request->payment_method;
                                $addMore->saving = $tabungan;
                                $addMore->b_tempo = $b_tempo;
                                $addMore->p_tempo = $p_tempo;
                                $addMore->t_tempo = $totalTempo;
                                $addMore->pay_status = 'LUNAS';
                                $addMore->status = 'PAID';
                                $addMore->pay_principal = $pokoks;
                                $a = $sPokok;
                                $addMore->pay_rates = $bungas;
                                $b = $sBunga;
                                $addMore->t_installment = $a + $b;
                                $addMore->amount = $jmlBayar;
                                $addMore->sisa = $afc;
                                $addMore->reminder = $afc;
                                $addMore->save();

                                $checkDataSaving = Savings::where('member_number', $request->memberNumber)
                                    ->where('tipe', 'wajib')
                                    ->where('status', 'setor')
                                    ->orderBy('id', 'desc')
                                    ->first();
                                $svg = "SVG";
                                $proofNumber = $svg . $this->TabunganUnik(10);
                                $savings = new Savings();
                                $savings->proof_number = $proofNumber;
                                $savings->member_number = $request->memberNumber;
                                if ($payDates >= now()) {
                                    $savings->tr_date = now()->format('Y-m-d');
                                } else {
                                    $savings->tr_date = now()->format('Y-m-d');
                                }
                                $savings->branch = $companyID;
                                $savings->tipe = 'wajib';
                                $savings->status = 'setor';
                                if (!empty($contracts->m_savings)) {
                                    $savings->amount = $addMore->saving;
                                } else {
                                    $savings->amount = 0;
                                }

                                if ($checkDataSaving == null) {
                                    $savings->end_balance = $addMore->saving;
                                } else {
                                    $savings->end_balance = $checkDataSaving->end_balance + $addMore->saving;
                                }
                                $savings->description = "Pembayaran Tab Wajib Pinjaman " . $addMore->loan_number . " Semua Angsuran ";
                                $savings->created_by = auth()->user()->name;
                                $savings->save();
                                $this->journal_installment($transNumber);
                            }
                        }

                        $sisaHutang = $loans->loan_remaining - $amount;
                        Loan::where('loan_number', '=', $request->loan_number)->update(['loan_remaining' => $sisaHutang]);

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                        return redirect()->back()->with('errors', $e->getMessage());
                    }
                } else if ($amount >= $loans->pay_month + $totalTempo) {
                    try {
                        $loan = Loan::where('loan_number', $request->loan_number)->first();

                        $bagi = $amountRequest / ($loan->pay_month + $totalTempo);
                        $xbagi = ceil($bagi);

                        $t_installment = ($sPokok + $sBunga + $tabungan) * $xbagi;
                        $sisa = + ($amountRequest - $t_installment);

                        $jmlBayar = $amountRequest;
                        $ins = Installment::where('loan_number', '=', $request->loan_number)->where('status', "UNPAID")->get();
                        foreach ($ins->take($xbagi) as $in) {
                            $in->trans_number = $transNumber;
                            $in->member_number = $request->memberNumber;
                            $in->pay_date = now()->format('Y-m-d');
                            $in->pay_method = $request->payment_method;
                            if ($jmlBayar >= $loans->pay_month + $totalTempo) {
                                $in->pay_status = 'FREE';
                                $in->status = 'PAID';
                                $in->transfer_in = $transferIn;
                                $in->amount = str_replace('.', '', $loans->pay_month + $totalTempo);
                                $in->sisa = 0;
                                $in->reminder = 0;
                            } else {
                                $in->pay_status = 'FREE';
                                $in->status = 'PAID';
                                $in->transfer_in = $transferIn;
                                $in->amount = str_replace('.', '', $jmlBayar);
                                $in->sisa = str_replace('.', '', $jmlBayar - $loans->pay_month - $totalTempo);
                                $in->reminder = str_replace('.', '', $jmlBayar - $loans->pay_month - $totalTempo);
                            }

                            if ($jmlBayar > $totalTempo) {
                                $in->b_tempo = $b_tempo;
                                $in->p_tempo = $p_tempo;
                                $in->t_tempo = $totalTempo;

                                $sisaAmount = $jmlBayar - $totalTempo;
                                $jmlBayar = $jmlBayar - $totalTempo;
                                if ($sisaAmount > $sBunga) {
                                    $in->pay_rates = $sBunga;
                                    $sisaAmount = $sisaAmount - $sBunga;
                                    $jmlBayar = $jmlBayar - $sBunga;
                                    $in->t_installment = $sBunga;
                                    if ($sisaAmount > $sPokok) {
                                        $in->pay_principal = $sPokok;
                                        $sisaAmount = $sisaAmount - $sPokok;
                                        $jmlBayar = $jmlBayar - $sPokok;
                                        $in->t_installment = $sPokok + $sBunga;
                                        if ($sisaAmount > $tabunganWajib) {
                                            $in->saving = $tabunganWajib;
                                            $sisaAmount = $sisaAmount - $tabunganWajib;
                                            $jmlBayar = $jmlBayar - $tabunganWajib;
                                        } else {
                                            $in->saving = $sisaAmount;
                                            $sisaAmount = $sisaAmount - $sisaAmount;
                                            $jmlBayar = $jmlBayar - $sisaAmount;
                                        }
                                    } else {
                                        $in->pay_principal = $sisaAmount;
                                        $in->t_installment = $sisaAmount + $sBunga;
                                        $sisaAmount = $sisaAmount - $sisaAmount;
                                        $jmlBayar = $jmlBayar - $sisaAmount;
                                    }
                                } else {
                                    $in->pay_rates = $sisaAmount;
                                    $in->t_installment = $sisaAmount;
                                    $sisaAmount = $sisaAmount - $sisaAmount;
                                    $jmlBayar = $jmlBayar - $sisaAmount;
                                }
                            } else if ($jmlBayar < $totalTempo) {
                                if ($jmlBayar > $b_tempo) {
                                    $in->b_tempo = $b_tempo;
                                    $sisaAmount = $jmlBayar - $b_tempo;
                                    $jmlBayar = $jmlBayar - $b_tempo;
                                    if ($sisaAmount > $p_tempo) {
                                        $in->p_tempo = $p_tempo;
                                        $sisaAmount = $sisaAmount - $p_tempo;
                                        $jmlBayar = $jmlBayar - $p_tempo;
                                    } else {
                                        $in->p_tempo = $sisaAmount;
                                        $sisaAmount = $sisaAmount - $sisaAmount;
                                        $jmlBayar = $jmlBayar - $sisaAmount;
                                    }
                                } else {
                                    $in->b_tempo = $jmlBayar;
                                    $sisaAmount = $jmlBayar - $jmlBayar;
                                }
                            }
                            $in->save();
                            $sisaHutang = $loans->loan_remaining - str_replace('.', '', $request->amount);
                            Loan::where('loan_number', '=', $request->loan_number)->update(['loan_remaining' => $sisaHutang]);
                            $checkDataSaving = Savings::where('member_number', $request->memberNumber)
                                ->where('tipe', 'wajib')
                                ->where('status', 'setor')
                                ->orderBy('id', 'desc')
                                ->first();
                            $svg = "SVG";
                            $proofNumber = $svg . $this->TabunganUnik(10);
                            $savings = new Savings();
                            $savings->proof_number = $proofNumber;
                            $savings->member_number = $request->memberNumber;
                            //$savings->tr_date = $payDates;
                            if ($payDates >= now()) {
                                $savings->tr_date = now()->format('Y-m-d');
                            } else {
                                $savings->tr_date = now()->format('Y-m-d');
                            }
                            $savings->branch = $companyID;
                            $savings->tipe = 'wajib';
                            $savings->status = 'setor';
                            if (!empty($contracts->m_savings)) {
                                $savings->amount = $in->saving;
                            } else {
                                $savings->amount = 0;
                            }
                            if ($checkDataSaving == null) {
                                $savings->end_balance = $in->saving;
                            } else {
                                $savings->end_balance = $checkDataSaving->end_balance + $in->saving;
                            }
                            $savings->description = "Pembayaran Tab Wajib Pinjaman " . $in->loan_number . " Angsuran ke " . $in->inst_to;
                            $savings->created_by = auth()->user()->name;
                            $savings->save();
                            $this->journal_installment($transNumber);
                            if (!is_null($tempos)) {
                                if ($tempos->inst_to == $in->inst_to) {
                                    $tempos->status = 'PAID';
                                    $tempos->is_paid = true;
                                    $tempos->save();
                                }
                            }
                        }
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                        return redirect()->back()->with('errors', $e->getMessage());
                    }
                }
            }
            return redirect()->route('installment.print', ['id' => $id]);
        }
    }
    public function printAll(Request $request, $loanNumber)
    {
        $installment = Installment::where('loan_number', $loanNumber)->first();
        //if(empty($id)) return redirect('customer/balance');

        $pdf = new TPDF;

        $users = User::with('companies')->where('id', auth()->user()->id)->get();
        foreach ($users as $user) {
            foreach ($user->companies as $company) {
                $companyID = $company->company_id;
            }
        }

        $profiles = Company::where('company_id', $companyID)->get();

        foreach ($profiles as $profile) {
            $profileName = $profile->name;
            $profileAddress = $profile->address;
            $provinsi = Provinsi::where('id', $profile->provinsi)->first();
            $kabupaten = Kabupaten::where('id', $profile->kabupaten)->first();
            $kecamatan = Kecamatan::where('id', $profile->kecamatan)->first();
            $kelurahan = Kelurahan::where('id', $profile->kelurahan)->first();
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
        $pdf::Cell(0, 10, "" . $kelurahan->nama . " , " . $kecamatan->nama . " , " . $kabupaten->nama . " , " . $provinsi->nama, 'B', 1,  'C', 0, 0, '', '', true, 0, false, true, 10, 'M');

        $pdf::Ln();

        // Neraca Saldo
        $pdf::SetFont('Arial', 'B', 14);
        $pdf::Cell(0, 10, "KWITANSI ANGSURAN", 0, 2, 'C');
        $pdf::Ln();

        $customer = Customer::where('member_number', $installment->member_number)->orderBy('id', 'asc')->first();

        $pdf::SetFont('Arial', 'B', 12);
        $pdf::MultiCell(40, 10, "NO. NASABAH", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
        $pdf::MultiCell(10, 10, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        $pdf::MultiCell(130, 10, $installment->member_number, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
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
        $installments = Installment::where('loan_number', $loanNumber)->orderBy('due_date', 'ASC')->get();
        //$total = $installments->sum('amount');

        foreach ($installments as $key => $item) {
            $tabungan = !empty($item->saving) ? $item->saving : 0;
            $jumlah = !empty($item->amount) ? $item->amount : 0;
            $pdf::SetFont('Arial', '', 12);
            $pdf::Cell(10, 8, $key + 1, 1, 0, 'C');
            $pdf::Cell(40, 8, $item->trans_number, 1, 0, 'C');
            $pdf::Cell(30, 8, $item->pay_date ? date('d-m-Y', strtotime($item->pay_date)) : '', 1, 0, 'C');
            $pdf::Cell(40, 8, "Rp. " . number_format($item->transfer_in, 0, ',', '.') . ",-", 1, 0, 'R');
            $pdf::Cell(40, 8, "Rp. " . number_format($item->tempo, 0, ',', '.') . ",-", 1, 0, 'R');
            $pdf::Cell(40, 8, "Rp. " . number_format($tabungan, 0, ',', '.') . ",-", 1, 0, 'R');
            $pdf::Cell(40, 8, "Rp. " . number_format($jumlah, 0, ',', '.') . ",-", 1, 0, 'R');
            $pdf::Cell(40, 8, $item->status, 1, 0, 'L');
            $pdf::Ln();
            //$jumlah = $item->amount;
        }

        $total = Installment::where('loan_number', $loanNumber)->get()->sum('amount');
        $pdf::SetFont('Arial', 'B', 10);
        $pdf::Cell(280, 8, "Rp. " . number_format($total, 0, ',', '.') . ",-", 1, 0, 'R');
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
        $pdf::SetFont('Arial', 'I', 8);
        $pdf::Cell(0, 10, "Dicetak Oleh Akuntan : " . $profileName . " Pada " . date("d-m-Y H:i:s")
            . " WIB", 0, 0, 'C');

        ob_end_clean();
        return $pdf::Output('pembelian.pdf', 'I');
    }

    public function print($id)
    {
        $pdf = new TPDF;

        $users = User::with('companies')->where('id', auth()->user()->id)->get();
        foreach ($users as $user) {
            foreach ($user->companies as $company) {
                $companyID = $company->company_id;
            }
        }

        $profiles = Company::where('company_id', $companyID)->get();

        foreach ($profiles as $profile) {
            $profileName = $profile->name;
            $profileAddress = $profile->address;
            $provinsi = Provinsi::where('id', $profile->provinsi)->first();
            $kabupaten = Kabupaten::where('id', $profile->kabupaten)->first();
            $kecamatan = Kecamatan::where('id', $profile->kecamatan)->first();
            $kelurahan = Kelurahan::where('id', $profile->kelurahan)->first();
        }

        $pdf::AddPage('P', 'A4');
        //$pdf::AddPage();
        ob_start();
        // Header
        $pdf::setJPEGQuality(90);
        $pdf::Image('img/logo/logo-small.png', 10, 10, 25, 0, 'PNG', '');
        $pdf::SetFont('', 'B', 18);
        $pdf::Cell(0, 10, $profileName, 0, 2, 'C');
        $pdf::SetFont('', 'B', 12);
        $pdf::Cell(0, 10, $profileAddress, 0, 1, 'C');
        $pdf::Cell(0, 10, "" . $kelurahan->nama . " , " . $kecamatan->nama . " , " . $kabupaten->nama . " , " . $provinsi->nama, 'B', 1,  'C', 0, 0, '', '', true, 0, false, true, 10, 'M');

        $pdf::Ln();

        // Neraca Saldo
        $pdf::SetFont('', 'B', 12);
        $pdf::Cell(0, 10, "KWITANSI ANGSURAN", 0, 2, 'C');
        $pdf::Ln();

        $installment = Installment::where('id', $id)->orderBy('id', 'asc')->first();
        if (empty($installment)) {
            return redirect('/installment');
        } else {
            $member = $installment->member_number;
            $loanNumber = $installment->loan_number;
            $loans = Loan::where('loan_number', $loanNumber)->first();
            $nasabah = Customer::where('member_number', $member)->orderBy('id', 'asc')->first();

            $payDate = explode(',', $loans->pay_date);
            $jumlah = count($payDate);

            $installmentTemps = Installment::where('id', $id)->first();
            $getMember = $loans->member_number;
            $tempos = Tempo::where('member_number', $loans->member_number)->where('status', '=', 'PAID')->where('is_paid', true)->where('inst_to', '=', $installmentTemps->inst_to)->first();

            $contractNo = $loans->contract_number;
            $contracts = CustomerContract::where('contract_number', $contractNo)->first();

            $pdf::SetFont('', 'B', 12);
            $pdf::MultiCell(30, 8, "Tanggal", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(45, 8, $installment->pay_date ? date('d-m-Y', strtotime($installment->pay_date)) : '', 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
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
            $pdf::MultiCell(140, 8, "Rp. " . number_format($installment->transfer_in, 0, ',', '.') . ",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::Ln();
            $pdf::MultiCell(40, 8, "Tempo", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(140, 8, "Rp. " . number_format($installment->t_tempo, 0, ',', '.') . ",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::Ln();

            // need to work with this part
            $install = 0;
            if ($installment->pay_status == "FULL") {
                $install = $installment->t_installment;
            } else if ($installment->pay_status == "FREE" && $installment->full_free == 'PAYMIN') {
                if ($installment->saving > 0) {
                    if ($installment->t_installment > 0) {
                        $install = $installment->t_installment;
                    } else {
                        $install = $installment->t_installment;
                    }
                } else {
                    $install = $installment->t_installment;
                }
            } else if ($installment->pay_status == "REPAYMENT" && $installment->full_free == 'PAYMIN') {
                if ($installment->saving > 0) {
                    if ($installment->t_installment > 0) {
                        $install = $installment->t_installment;
                    } else {
                        $install = $installment->t_installment;
                    }
                } else {
                    $install = $installment->t_installment;
                }
            } else if ($installment->pay_status == "FREE" || $installment->pay_status == "LUNAS") {
                $sumInstallment = Installment::where('trans_number', $installment->trans_number)->sum('t_installment');
                $install = $sumInstallment;
            } else {
                $install = 0;
            }

            $pdf::MultiCell(40, 8, "Angsuran", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
            //$pdf::MultiCell(140, 8, "Rp. ".number_format($installment->t_installment, 0, ',', '.').",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(140, 8, "Rp. " . number_format($install, 0, ',', '.') . ",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::Ln();

            // this to
            $tabungan = 0;
            if ($installment->pay_status == "FULL") {
                $tabungan = $installment->saving;
            } else if ($installment->pay_status == "FREE" && $installment->full_free == 'PAYMIN') {
                $tabungan = $installment->saving;
            } else if ($installment->pay_status == "REPAYMENT" && $installment->full_free == 'PAYMIN') {
                $tabungan = $installment->saving;
            } else if ($installment->pay_status == "FREE" || $installment->pay_status == "LUNAS") {
                $sumSaving = Installment::where('trans_number', $installment->trans_number)->sum('saving');
                $tabungan = $sumSaving;
            } else {
                $tabungan = 0;
            }

            $pdf::MultiCell(40, 8, "Tabungan", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(140, 8, "Rp. " . number_format((float)$tabungan, 0, ',', '.') . ",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::Ln();

            // this too
            $totalAmount = 0;

            if ($installment->pay_status == "FULL") {
                $totalAmount = $installment->amount;
            } else if ($installment->pay_status == "FREE" && $installment->full_free == 'PAYMIN') {
                $totalAmount = $installment->amount;
            } else if ($installment->pay_status == "REPAYMENT" && $installment->full_free == 'PAYMIN') {
                $totalAmount = $installment->amount;
            } else if ($installment->pay_status == "FREE" || $installment->pay_status == "LUNAS") {
                $sumAmount = Installment::where('trans_number', $installment->trans_number)->sum('amount');
                $totalAmount = $sumAmount;
            } else {
                $totalAmount = 0;
            }
            $pdf::MultiCell(40, 8, "Total Yang Dibayar", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(140, 8, "Rp. " . number_format($totalAmount, 0, ',', '.') . ",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::Ln();

            // and this
            $sisa = 0;

            if ($installment->pay_status == "FULL") {
                $sisa = $installment->transfer_in - $installment->amount;
            } else if ($installment->pay_status == "FREE" && $installment->full_free == 'PAYMIN') {
                $sisa = $installment->transfer_in - $installment->amount;
            } else if ($installment->pay_status == "REPAYMENT" && $installment->full_free == 'PAYMIN') {
                $sisa = $installment->transfer_in - $installment->amount;
            } else if ($installment->pay_status == "FREE" || $installment->pay_status == "LUNAS") {
                $sumAmount = Installment::where('trans_number', $installment->trans_number)->sum('amount');
                $sisa = $installment->transfer_in - $sumAmount;
            } else {
                $sisa = 0;
            }

            $pdf::MultiCell(40, 8, "Sisa", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(140, 8, "Rp. " . number_format($sisa, 0, ',', '.') . ",-", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::Ln();
            $pdf::MultiCell(40, 8, "Angsuran Ke", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(10, 8, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::MultiCell(140, 8, $installment->inst_to, 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
            $pdf::Ln();

            // Footer
            $pdf::SetY(179);
            $pdf::SetX(30);
            $pdf::SetFont('', 'I', 8);
            $pdf::Cell(0, 10, $kelurahan->nama . "," . date_format($installment->updated_at, "Y-m-d"), 0, 0, 'C');
            $pdf::Ln();

            $pdf::SetY(200);
            $pdf::SetX(20);
            $pdf::SetFont('', 'I', 8);
            $pdf::Cell(0, 10, ($nasabah->name));
            $pdf::Ln();

            $pdf::SetY(200);
            $pdf::SetX(150);
            $pdf::SetFont('', 'I', 8);
            $pdf::Cell(0, 10, (auth()->user()->name));
            $pdf::Ln();

            $pdf::SetY(250);
            $pdf::SetX(160);

            $html = '<a href= "' . url('/installment') . '" class="btn btn-xs btn-default">Close</a>';

            $pdf::writeHTML($html, true, false, true, false, '');

            ob_end_clean();
            return $pdf::Output('angsuran.pdf', 'I');
        }
    }
    public function metode_flat($jumlahPinjaman, $jangkaWaktu, $sukuBunga)
    {
        $data = [];
        $sukuBunga = $sukuBunga / 100;
        $pokok = $jumlahPinjaman / $jangkaWaktu;
        $bunga = $jumlahPinjaman * $sukuBunga / $jangkaWaktu;
        $sisaPinjaman = $jumlahPinjaman;
        $jumlahAngsuran = $pokok + $bunga;

        for ($i = 0; $i < $jangkaWaktu; $i++) {
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
        $bunga_bulan      = ($bunga / 12) / 100;
        $pembagi          = 1 - (1 / pow(1 + $bunga_bulan, $jangka));
        $hasil            = $besar_pinjaman / ($pembagi / $bunga_bulan);
        return $hasil;
    }

    public function hitung_flat($besar_pinjaman, $jangka, $bunga)
    {
        $cicilan_bulan    = $besar_pinjaman / $jangka;
        $bunga_bulan      = $bunga / 12 / 100;
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



    public function getDetailsData($loan_number)
    {
        $cicilan = Installment::where('loan_number', $loan_number)->orderBy('due_date', 'asc')->orderBy('inst_to', 'asc')->get();
        return Datatables::of($cicilan)->make(true);
    }

    public function getCicilan($loanNumber)
    {
        // $cicilans = Installment::where('loan_number',$loanNumber)->get();
        $cicilans = Installment::where('loan_number', $loanNumber)->orderBy('due_date', 'asc')->orderBy('inst_to', 'asc')->get();
        $findTempo = Tempo::where('member_number', $cicilans[0]->member_number)->where('status', 'confirm')->where('is_paid', false)->first();

        $response = "<div class='table-responsive' id='tblAngsur'>";
        $response .= "<table class='table table-responsive table-striped'>";
        $response .= "<thead>";
        $response .= "<tr>";
        $response .= "<th>Angsuran Ke</th>";
        $response .= "<th>No Pinjman</th>";
        $response .= "<th>Jatuh Tempo</th>";
        $response .= "<th class='text-center'>Total</th>";
        $response .= "<th class='text-center'>Aksi</th>";
        $response .= "</tr>";
        $response .= "</thead>";
        $response .= "<tbody>";
        foreach ($cicilans as $key => $row) {
            $loans = Loan::where('loan_number', $loanNumber)->first();
            $getMember = $loans->member_number;
            $sisa = Installment::where('id', $row->id)->where('loan_number', $loanNumber)->first();
            $lebih = Installment::where('id', $row->id - 1)->where('loan_number', $loanNumber)->first();
            if (is_null($lebih)) {
                $payMonth = 0;
            } else {
                $payMonth = $lebih->reminder;
            }

            $payDate = explode(',', $loans->pay_date);
            $jumlah = count($payDate);
            $i = $key;
            if ($jumlah > 1) {
                $payMonth = $loans->pay_month / 2;
                if (is_null($lebih)) {
                    $payMonth = $loans->pay_month / 2;
                } else if ($lebih->reminder < 0) {
                    $payMonth = $loans->pay_month / 2;
                } else {
                    $payMonth = $loans->pay_month / 2 - $lebih->reminder;
                }
                if (!is_null($findTempo)) {
                    $tempo_amount = $findTempo->total_amount;
                }
            } else {
                if (is_null($lebih)) {
                    $payMonth = $loans->pay_month;
                } else if ($lebih->reminder < 0) {
                    $payMonth = $loans->pay_month;
                } else {
                    $payMonth = $loans->pay_month - $lebih->reminder;
                }
                if (!is_null($findTempo)) {
                    $tempo_amount = $findTempo->total_amount;
                }
            }

            if ($row->status == 'PAID') {
                $totTab = $row->saving;
                $totCicil = $row->pay_rates + $row->pay_principal;
                $totBayar = $totTab + $totCicil;

                if ($row->reminder > 0) {
                    $payMonth = 0;
                    $aksi = "LUNAS | <a href='" . URL::to('/installment/print/' . $row->id) . "' target='_blank'>Cetak Kwitansi</a>";
                }
                if ($row->reminder < 0) {
                    $payMonth = $loans->pay_month;
                    $aksi = "<a href='' class='btnFull' data-id='" . $row->id . "' data-members='" . $row->member_number . "' data-inst='" . $row->inst_to . "'data-loan='" . $row->loan_number . "' onClick='ShowModalFull(this)' data-dismiss='modal'>Bayar Penuh</a> | <a href='' id='free' class='btnFree' data-id='" . $row->id . "' data-members='" . $row->member_number . "' data-inst='" . $row->inst_to . "'data-loan='" . $row->loan_number . "' onClick='ShowModalFree(this)' data-dismiss='modal'>Bayar bebas</a>";
                }
                if ($row->reminder == 0) {
                    $payMonth = 0;
                    $aksi = "LUNAS | <a href='" . URL::to('/installment/print/' . $row->id) . "' target='_blank'>Cetak Kwitansi</a>";
                }
                $total = $sisa->sisa;
            } else {
                $aksi = "<a href='' class='btnFull' data-id='" . $row->id . "' data-members='" . $row->member_number . "' data-inst='" . $row->inst_to . "'data-loan='" . $row->loan_number . "' onClick='ShowModalFull(this)' data-dismiss='modal'>Bayar Penuh</a> | <a href='' id='free' class='btnFree' data-id='" . $row->id . "' data-members='" . $row->member_number . "' data-inst='" . $row->inst_to . "'data-loan='" . $row->loan_number . "' onClick='ShowModalFree(this)' data-dismiss='modal'>Bayar bebas</a>";
            }
            $total = $sisa->sisa;
            if ($total < 0) {
                $total = $total * -1;
            }
            $zero = 0;
            $response .= "<tr>";
            $response .= "<td>" . $row->inst_to . "</td>";
            $response .= "<td>" . $row->loan_number . "</td>";
            $response .= "<td>" . date('d-m-Y', strtotime($row->due_date)) . "</td>";
            if ($sisa->sisa < 0) {
                $response .= "<td>" . $total . "</td>";
            } else if ($sisa->sisa >= 0 && $sisa->status == "PAID") {
                $response .= "<td>" . $zero . "</td>";
            } else {
                if (is_null($findTempo)) {
                    $response .= "<td>" . $payMonth . "</td>";
                } else if ($findTempo->total_amount > 0 && $findTempo->inst_to == $row->inst_to && $row->is_tempo) {
                    $dateString = $findTempo->tempo_date;
                    $date = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);
                    $formattedDate = $date->format('y-m-d');
                    $response .= "<td>" . $payMonth . "</br>" . $tempo_amount . "</br> ( " . $formattedDate . " )</td>";
                } else {
                    $response .= "<td>" . $payMonth . "</td>";
                }
            }
            $response .= "<td>$aksi</td>";
            $response .= "</tr>";
        }
        $response .= "</tbody>";
        $response .= "</table>";
        $response .= "</div>";

        echo $response;
    }

    public function journal_installment($transNumber)
    {
        // implement me later
        $users = User::with('companies')->where('id', auth()->user()->id)->first();
        $companyID = $users->companies[0]->id;
        DB::beginTransaction();
        $data = Installment::where('trans_number', $transNumber)->where('status', 'PAID')->orderBy('id', 'desc')->first();
        $customer = Customer::where('member_number', $data->member_number)->first();
        $trxnumber = Transaction::max('id');
        $trxnumber = $trxnumber + 1;
        try {
            $transactions = [
                [
                    'trx_no' => 'TRX' . now()->format('Ymd') . $trxnumber,
                    'date_trx' => now()->format('Y-m-d'),
                    'account' => '310-02',
                    'branch' => $companyID,
                    'amount' => $data->saving,
                    'description' => 'Pembayaran Tab Wajib Pinjaman ' . $data->loan_number . ' Angsuran ke ' . $data->inst_to . ' ( ' . $customer->name . ' )',
                    'status' => 'k',
                    'jenis' => "ANG",
                    'acc_by' => auth()->user()->name,
                ],
                [
                    'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 1),
                    'date_trx' => now()->format('Y-m-d'),
                    'account' => '100-01',
                    'branch' => $companyID,
                    'amount' => $data->saving,
                    'description' => 'Pembayaran Tab Wajib Pinjaman ' . $data->loan_number . ' Angsuran ke ' . $data->inst_to . ' ( ' . $customer->name . ' )',
                    'status' => 'd',
                    'jenis' => "ANG",
                    'acc_by' => auth()->user()->name,
                ],
                [
                    'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 2),
                    'date_trx' => now()->format('Y-m-d'),
                    'account' => '140-01',
                    'branch' => $companyID,
                    'amount' => $data->pay_principal,
                    'description' => 'Pembayaran Pokok Pinjaman ' . $data->loan_number . ' Angsuran ke ' . $data->inst_to . ' ( ' . $customer->name . ' )',
                    'status' => 'k',
                    'jenis' => "ANG",
                    'acc_by' => auth()->user()->name,
                ],
                [
                    'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 3),
                    'date_trx' => now()->format('Y-m-d'),
                    'account' => '100-01',
                    'branch' => $companyID,
                    'amount' => $data->pay_principal,
                    'description' => 'Pembayaran Pokok Pinjaman ' . $data->loan_number . ' Angsuran ke ' . $data->inst_to . ' ( ' . $customer->name . ' )',
                    'status' => 'd',
                    'jenis' => "ANG",
                    'acc_by' => auth()->user()->name,
                ],
                [
                    'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 4),
                    'date_trx' => now()->format('Y-m-d'),
                    'account' => '410-05',
                    'branch' => $companyID,
                    'amount' => $data->pay_rates,
                    'description' => 'Pembayaran Bunga Pinjaman ' . $data->loan_number . ' Angsuran ke ' . $data->inst_to . ' ( ' . $customer->name . ' )',
                    'status' => 'k',
                    'jenis' => "ANG",
                    'acc_by' => auth()->user()->name,
                ],
                [
                    'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 5),
                    'date_trx' => now()->format('Y-m-d'),
                    'account' => '100-01',
                    'branch' => $companyID,
                    'amount' => $data->pay_rates,
                    'description' => 'Pembayaran Bunga Pinjaman ' . $data->loan_number . ' Angsuran ke ' . $data->inst_to . ' ( ' . $customer->name . ' )',
                    'status' => 'd',
                    'jenis' => "ANG",
                    'acc_by' => auth()->user()->name,
                ],
                [
                    'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 6),
                    'date_trx' => now()->format('Y-m-d'),
                    'account' => '100-01',
                    'branch' => $companyID,
                    'amount' => $data->p_tempo,
                    'description' => 'Pembayaran Pokok Pinjaman Tempo ' . $data->loan_number . ' Angsuran ke ' . $data->inst_to . ' ( ' . $customer->name . ' )',
                    'status' => 'd',
                    'jenis' => "ANG",
                    'acc_by' => auth()->user()->name,
                ],
                [
                    'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 7),
                    'date_trx' => now()->format('Y-m-d'),
                    'account' => '140-01',
                    'branch' => $companyID,
                    'amount' => $data->p_tempo,
                    'description' => 'Pembayaran Pokok Pinjaman Tempo ' . $data->loan_number . ' Angsuran ke ' . $data->inst_to . ' ( ' . $customer->name . ' )',
                    'status' => 'k',
                    'jenis' => "ANG",
                    'acc_by' => auth()->user()->name,
                ],
                [
                    'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 8),
                    'date_trx' => now()->format('Y-m-d'),
                    'account' => '100-01',
                    'branch' => $companyID,
                    'amount' => $data->b_tempo,
                    'description' => 'Pembayaran Bunga Pinjaman Tempo ' . $data->loan_number . ' Angsuran ke ' . $data->inst_to . ' ( ' . $customer->name . ' )',
                    'status' => 'd',
                    'jenis' => "ANG",
                    'acc_by' => auth()->user()->name,
                ],
                [
                    'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 9),
                    'date_trx' => now()->format('Y-m-d'),
                    'account' => '410-05',
                    'branch' => $companyID,
                    'amount' => $data->b_tempo,
                    'description' => 'Pembayaran Bunga Pinjaman Tempo ' . $data->loan_number . ' Angsuran ke ' . $data->inst_to . ' ( ' . $customer->name . ' )',
                    'status' => 'k',
                    'jenis' => "ANG",
                    'acc_by' => auth()->user()->name,
                ],
            ];
            Transaction::insert($transactions);
            for ($i = 0; $i <= (count($transactions) - 1); $i++) {
                $accountBalance = BalanceAccount::where('account_number', $transactions[$i]['account'])->where('branch', $companyID)->first();
                $accountType = AccountGroup::where('account_number', $transactions[$i]['account'])->first();
                if ($transactions[$i]['status'] == 'd') {
                    $codeAccount = substr($transactions[$i]['account'], 0, 1);
                    if ($codeAccount == '4' || $codeAccount == '3' || $codeAccount == '2') {
                        if (is_null($accountBalance)) {
                            BalanceAccount::create([
                                'branch' => $companyID,
                                'transaction_type' => $accountType->account_name,
                                'account_number' => $transactions[$i]['account'],
                                'amount' => $transactions[$i]['amount'],
                                'start_balance' => $transactions[$i]['amount'],
                                'end_balance' => $transactions[$i]['amount'],
                            ]);
                        } else {
                            $accountBalance->end_balance = $accountBalance->end_balance - $transactions[$i]['amount'];
                            $accountBalance->updated_at = now();
                            $accountBalance->save();
                        }
                    } else {
                        if (is_null($accountBalance)) {
                            BalanceAccount::create([
                                'branch' => $companyID,
                                'transaction_type' => $accountType->account_name,
                                'account_number' => $transactions[$i]['account'],
                                'amount' => $transactions[$i]['amount'],
                                'start_balance' => $transactions[$i]['amount'],
                                'end_balance' => $transactions[$i]['amount'],
                            ]);
                        } else {
                            $accountBalance->end_balance = (int)$accountBalance->end_balance + (int)$transactions[$i]['amount'];
                            $accountBalance->updated_at = now();
                            $accountBalance->save();
                        }
                    }
                } else {
                    $codeAccount = substr($transactions[$i]['account'], 0, 1);
                    if ($codeAccount == '4' || $codeAccount == '3' || $codeAccount == '2') {
                        if (is_null($accountBalance)) {
                            BalanceAccount::create([
                                'branch' => $companyID,
                                'transaction_type' => $accountType->account_name,
                                'account_number' => $transactions[$i]['account'],
                                'amount' => $transactions[$i]['amount'],
                                'start_balance' => (0 - $transactions[$i]['amount']),
                                'end_balance' => (0 - $transactions[$i]['amount']),
                            ]);
                        } else {
                            $accountBalance->end_balance = (int) $transactions[$i]['amount'] + (int) $accountBalance->end_balance;
                            $accountBalance->updated_at = now();
                            $accountBalance->save();
                        }
                    } else {
                        if (is_null($accountBalance)) {
                            BalanceAccount::create([
                                'branch' => $companyID,
                                'transaction_type' => $accountType->account_name,
                                'account_number' => $transactions[$i]['account'],
                                'amount' => $transactions[$i]['amount'],
                                'start_balance' => (0 - $transactions[$i]['amount']),
                                'end_balance' => (0 - $transactions[$i]['amount']),
                            ]);
                        } else {
                            $accountBalance->end_balance = (int) $accountBalance->end_balance - (int) $transactions[$i]['amount'];
                            $accountBalance->updated_at = now();
                            $accountBalance->save();
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
