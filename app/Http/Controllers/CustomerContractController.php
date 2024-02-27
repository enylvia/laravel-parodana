<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\CardType;
use App\Models\Education;
use App\Models\Maritial;
use App\Models\Religion;
use App\Models\CustomerContract;
use App\Models\CustomerApprove;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\DocumentHandover;
use Carbon\Carbon;
use Validator;
use Exception;
use DPDF;
use TPDF;
use App\Helper\Terbilang;
use App\Models\AccountGroup;
use App\Models\BalanceAccount;
use App\Models\CustomerInsurance;
use App\Models\Savings;
use App\Models\Transaction;
use Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerContractController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	// HALAMAN YANG DIUBAH
	public function contract()
	{
		$users = User::with('companies')->where('id', auth()->user()->id)->get();
		foreach ($users as $user) {
			foreach ($user->companies as $company) {
				$companyID = $company->id;
			}
		}

		if (Auth::user()->hasRole('superadmin', 'pengawas')) {
			$customers = Customer::where('approve', 1)->where('status', 'approve')->paginate(10);
		} else {
			$customers = Customer::where('approve', 1)->where('status', 'approve')->where('branch', $companyID)->paginate(10);
		}
		$cards = CardType::all();
		$educations = Education::all();
		$maritials = Maritial::all();
		$religions = Religion::all();
		return view('customer.contract.contract', compact('customers', 'cards', 'educations', 'maritials', 'religions'));
	}

	// DIUBAH JUGA NANTI

	public function create($id)
	{
		$regNumber = $id;
		$users = User::with('companies')->where('id', auth()->user()->id)->get();
		foreach ($users as $user) {
			foreach ($user->companies as $company) {
				$companyID = $company->id;
			}
		}
		//$employee = User::where('id',auth()->user()->id)->employee->first();
		//dd($employee);
		$cek = CustomerApprove::where('reg_number', $regNumber)->where('approve', 1)->first();
		if ($cek->approve_amount > 0) {
			$getCompany = Company::where('id', Auth()->user()->id)->first();
			$years = range(Carbon::now()->year, 2010);
			$haris = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
			$tanggals = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31];
			$bulans = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
			$tahuns = $years;
			$customers = Customer::where('reg_number', $regNumber)->where('approve', 1)->where('status', 'approve')->get();
			$employees = User::where('id', '<>', 1)->where('id', '<>', 2)->get();
			return view('customer.contract.create', compact('customers', 'employees', 'haris', 'tanggals', 'bulans', 'tahuns'));
		} else {
			return redirect()->back()->with('error', 'Pinjaman tidak boleh kosong');
		}
	}

	// implement DB transactions later..  
	public function store(Request $request)
	{
		DB::beginTransaction();
		try {
			$customer = Customer::where('id', $request->customer_id)->first();

			$custID = $customer->id;
			$branch = $customer->branch;
			$payDate = $customer->payday_date;
			$regNumber = $customer->reg_number;
			$contractDate = $customer->created_at;


			$approve = CustomerApprove::where('customer_id', $request->customer_id)->first();
			$company = Company::where('id', $branch)->first();

			$companyId = $company->company_id;
			$branchId = $company->id;

			$contracts = new CustomerContract();
			$contracts->customer_id = $request->customer_id;
			$contracts->reg_number = $regNumber;
			$contracts->contract_number = $contracts->contractNumber($contractDate, $companyId);
			$contracts->contract_date = $contractDate->format('Y-m-d');
			$contracts->employee_id = auth()->user()->name;
			$contracts->m_savings = $approve->m_savings;
			$contracts->deskripsi = $request->deskripsi;
			$contracts->insurance = $request->persenasuransi;
			if (!empty($request->input('materai'))) {
				$contracts->stamp = str_replace('.', '', $request->materai);
			} else {
				$contracts->stamp = 0;
			}
			$contracts->provision = $request->provision;
			$contracts->status = 'BELUM LUNAS';
			$contracts->insurance = $request->insurance;
			$contracts->branch = $branchId;

			$memberNumber = $customer->memberNumber($companyId, $customer->id);
			Customer::where('id', '=', $contracts->customer_id)->update(
				[
					'status' => 'member',
					'member_number' => $memberNumber,
					'member' => 1
				]
			);

			$start = Carbon::now();
			$sukuBunga = $approve->interest_rate / 12;
			$pokok = $approve->approve_amount / $approve->time_period;
			$bunga = $approve->approve_amount * $sukuBunga / 100;
			$jumlahAngsuran = $pokok + $bunga + $approve->m_savings;
			$payMonth = ceil($jumlahAngsuran / 1000) * 1000;
			$loanNumber = 'LOA01' . $request->customer_id;
			$loanAmount = $approve->approve_amount + ($bunga * $approve->time_period) + ($approve->m_savings * $approve->time_period);

			Loan::create([
				'loan_number' => $loanNumber,
				'customer_id' => $request->customer_id,
				'contract_number' => $contracts->contract_number,
				'contract_date' => $contracts->contract_date,
				'start_month' => $start->addMonth(),
				'member_number' => $memberNumber,
				'loan_amount' => $payMonth * $approve->time_period,
				'time_period' => $approve->time_period,
				'pay_date' => $payDate,
				'interest_rate' => $approve->interest_rate,
				'pay_principal' => ceil($pokok / 1000) * 1000,
				'pay_interest' => ceil($bunga),
				'pay_month' => ceil($payMonth),
				'company_id' => $branchId,
				'loan_remaining' => $payMonth * $approve->time_period,
				'status' => 'BELUM LUNAS'
			]);

			// add insurance when contract is created
			CustomerInsurance::create([
				'customer_id' => $request->customer_id,
				'no_kontrak' => 'ASR' . $request->customer_id . now()->format('Ymd'),
				'duration' => $approve->time_period,
				'name_user' => $customer->name,
				'branch' => $branchId,
				'company' => 'NIKOMAS GEMILANG',
			]);

			$savings = new Savings();
			$svg = "SVG";
			$checkDataSaving = Savings::where('member_number', $request->memberNumber)
				->where('tipe', 'pokok')
				->where('status', 'setor')
				->orderBy('id', 'desc')
				->first();
			$randomNumber = new InstallmentController();
			$proofNumber = $svg . $randomNumber->TabunganUnik(10);
			$savings = new Savings();
			$savings->proof_number = $proofNumber;
			$savings->member_number = $memberNumber;
			$savings->tr_date = now()->format('Y-m-d');
			$savings->branch = $customer->branch;
			$savings->tipe = 'pokok';
			$savings->status = 'setor';
			$savings->amount = $approve->approve_amount * (2 / 100);
			if ($checkDataSaving == null) {
				$savings->end_balance = str_replace('.', '', $approve->approve_amount * (2 / 100));
			} else {
				$savings->end_balance = $checkDataSaving->end_balance + str_replace('.', '', $approve->approve_amount * (2 / 100));
			}
			$savings->description = "Pembayaran Tab. Pokok Pinjaman";
			$savings->created_by = auth()->user()->name;
			$savings->save();

			$contracts->save();
			$this->journal_pencairan($request->customer_id);
			DB::commit();
			return redirect()->route('contract.detail', ['id' => $request->customer_id]);
		} catch (Exception $e) {
			DB::rollback();
			return redirect()->back()->with('errors', $e->getMessage());
		}
	}

	public function contract_list(Request $request)
	{
		return view('customer.contract.list');
	}
	public function cclistJson(Request $request)
	{
		$users = User::with('companies')->where('id', auth()->user()->id)->first();
		$companyID = $users->companies[0]->id;
		if ($request->ajax()) {
			$customer = CustomerContract::join('customer', 'customer.id', '=', 'customer_contract.customer_id')
				->where('customer.branch', $companyID)
				->where('customer.name', '!=', '')
				->select('customer.member_number', 'customer.name', 'customer_contract.contract_date', 'customer_contract.contract_number', 'customer_contract.customer_id')
				->get();
			return Datatables::of($customer)->make(true);
		}
	}

	public function contract_signature()
	{
		return view('customer.contract.signature');
	}

	public function getContractData(Request $request)
	{
		if ($request->ajax()) {
			$contracts = Customer::join('customer_contract as contract', 'contract.customer_id', '=', 'customer.id')
				->where('customer.branch', $request->get('branch'))
				->select('customer.id', 'customer.name', 'customer.member_number', 'customer.branch', 'customer.avatar', 'contract.*');
			return datatables()->of($contracts)

				->filter(function ($instance) use ($request) {
					if (
						$request->get('branch') == 1 || $request->get('branch') == 2 ||
						$request->get('branch') == 3 || $request->get('branch') == 4 ||
						$request->get('branch') == 5 || $request->get('branch') == 6
					) {
						$instance->where('customer.branch', $request->get('branch'));
					}
				})
				->editColumn('customer.name', function ($row) {
					return $row->name;
				})
				->editColumn('customer.member_number', function ($row) {
					return $row->member_number;
				})
				->make(true);
		}
	}

	public function signature_upload(Request $request)
	{
		$folderPath = public_path('uploads/');
		$image_parts = explode(";base64,", $request->signed);
		$image_type_aux = explode("image/", $image_parts[0]);
		$image_type = $image_type_aux[1];
		$image_base64 = base64_decode($image_parts[1]);
		$file = $folderPath . uniqid() . '.' . $image_type;
		file_put_contents($file, $image_base64);

		return back()->with('success', 'success Full upload signature');
	}

	public function contract_print($id)
	{
		$customers = Customer::where('id', $id)->get();
		foreach ($customers as $customer) {
			$custID = $customer->id;
			$regNumber = $customer->reg_number;
		}
		$approves = CustomerApprove::where('customer_id', $id)->first();
		$handovers = DocumentHandover::where('reg_number', $regNumber)->get();
		$bilang = Terbilang::bilang($approves->approve_amount);
		$pdf = DPDF::loadView('customer.contract.print', compact('customers', 'bilang', 'handovers'))->setPaper('a4', 'potrait')->setOptions([
			'tempDir' => public_path(),
			'chroot'  => public_path('/img/logo/'),
		]);
		//GENERATE PDF-NYA
		return $pdf->stream();
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

	public function LaporanPeminjaman(Request $request, $id)
	{
		$customer = Customer::where('id', $id)->first();
		$approveCustomer = CustomerApprove::where('customer_id', $id)->where('approve', "1")->first();
		$cc = CustomerContract::where('customer_id', $id)->first();
		$memberNumber = Loan::where('customer_id', $id)->first();
		$ci = CustomerInsurance::with('customer')->where('customer_id', $id)->first();
		$document = DocumentHandover::where('reg_number', $cc->reg_number)->get();
		$saving = Savings::where('member_number', $memberNumber->member_number)->where('tipe', 'pokok')->where('status', 'setor')->first();
		if ($request->has("page")) {
			switch ($request->get("page")) {
				case "1":
					return view('customer.contract.document_two', compact('customer', 'approveCustomer', 'cc', 'saving', 'ci', 'memberNumber'));
					break;
				case "2":
					return view('customer.contract.document_three', compact('customer', 'approveCustomer', 'cc', 'saving', 'ci', 'memberNumber'));
					break;
				case "3":
					return view('customer.contract.document_four', compact('customer', 'approveCustomer', 'cc', 'saving', 'ci', 'memberNumber'));
					break;
				case "4":
					return view('customer.contract.document_five', compact('customer', 'approveCustomer', 'cc', 'saving', 'ci', 'memberNumber'));
					break;
				case "5":
					return view('customer.contract.document_additional', compact('document', 'customer', 'approveCustomer', 'cc', 'saving', 'ci', 'memberNumber'));
					break;
				case "6":
					return view('customer.contract.document_six', compact('customer', 'approveCustomer', 'cc', 'saving', 'ci', 'memberNumber'));
					break;
				default:
					return view('customer.contract.document', compact('customer', 'approveCustomer', 'cc', 'saving', 'ci', 'memberNumber'));
					break;
			}
		}
		return view('customer.contract.document', compact('customer', 'approveCustomer', 'cc', 'saving', 'ci', 'memberNumber'));
	}
	
	public function journal_pencairan($id)
	{
		$users = User::with('companies')->where('id', auth()->user()->id)->first();
		$companyID = $users->companies[0]->id;
		$customer = Customer::where('id', $id)->first();
		$approveCustomer = CustomerApprove::where('customer_id', $id)->where('approve', "1")->first();
		$cc = CustomerContract::where('customer_id', $id)->first();
		$loans = Loan::where('customer_id', $id)->first();
		$trxnumber = Transaction::max('id');
		$trxnumber = $trxnumber + 1;

		$tabPokok = $approveCustomer->approve_amount * 0.02;
		$materai = $cc->stamp;
		$provisi = $approveCustomer->approve_amount * ($cc->provision / 100);
		$insurance = $approveCustomer->approve_amount * ($cc->insurance / 100);

		$transactions = [
			[
				'trx_no' => 'TRX' . now()->format('Ymd') . $trxnumber,
				'date_trx' => now()->format('Y-m-d'),
				'account' => '310-01',
				'branch' => $companyID,
				'amount' => $tabPokok,
				'description' => 'Tab Pokok Pinjaman ' . $loans->loan_number . ' (' . $customer->name . ')',
				'status' => 'k',
				'acc_by' => auth()->user()->name,
			], [
				'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 1),
				'date_trx' => now()->format('Y-m-d'),
				'account' => '100-01',
				'branch' => $companyID,
				'amount' => $tabPokok,
				'description' => 'Tab Pokok Pinjaman ' . $loans->loan_number . ' (' . $customer->name . ')',
				'status' => 'd',
				'acc_by' => auth()->user()->name,
			], [
				'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 2),
				'date_trx' => now()->format('Y-m-d'),
				'account' => '190-05',
				'branch' => $companyID,
				'amount' => $materai,
				'description' => 'Materai Pinjaman ' . $loans->loan_number . ' (' . $customer->name . ')',
				'status' => 'k',
				'acc_by' => auth()->user()->name,
			], [
				'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 3),
				'date_trx' => now()->format('Y-m-d'),
				'account' => '100-01',
				'branch' => $companyID,
				'amount' => $materai,
				'description' => 'Materai Pinjaman ' . $loans->loan_number . ' (' . $customer->name . ')',
				'status' => 'd',
				'acc_by' => auth()->user()->name,
			], [
				'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 4),
				'date_trx' => now()->format('Y-m-d'),
				'account' => '200-05',
				'branch' => $companyID,
				'amount' => $insurance,
				'description' => 'Asuransi Pinjaman ' . $loans->loan_number . ' (' . $customer->name . ')',
				'status' => 'k',
				'acc_by' => auth()->user()->name,
			], [
				'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 5),
				'date_trx' => now()->format('Y-m-d'),
				'account' => '100-01',
				'branch' => $companyID,
				'amount' => $insurance,
				'description' => 'Asuransi Pinjaman ' . $loans->loan_number . ' (' . $customer->name . ')',
				'status' => 'd',
				'acc_by' => auth()->user()->name,
			], [
				'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 6),
				'date_trx' => now()->format('Y-m-d'),
				'account' => '420-01',
				'branch' => $companyID,
				'amount' => $provisi,
				'description' => 'Provisi Pinjaman ' . $loans->loan_number . ' (' . $customer->name . ')',
				'status' => 'k',
				'acc_by' => auth()->user()->name,
			], [
				'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 7),
				'date_trx' => now()->format('Y-m-d'),
				'account' => '100-01',
				'branch' => $companyID,
				'amount' => $provisi,
				'description' => 'Provisi Pinjaman ' . $loans->loan_number . ' (' . $customer->name . ')',
				'status' => 'd',
				'acc_by' => auth()->user()->name,
			], [
				'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 8),
				'date_trx' => now()->format('Y-m-d'),
				'account' => '140-01',
				'branch' => $companyID,
				'amount' => $approveCustomer->approve_amount,
				'description' => 'Pencairan Pinjaman ' . $loans->loan_number . ' (' . $customer->name . ')',
				'status' => 'd',
				'acc_by' => auth()->user()->name,
			], [
				'trx_no' => 'TRX' . now()->format('Ymd') . ($trxnumber + 9),
				'date_trx' => now()->format('Y-m-d'),
				'account' => '100-01',
				'branch' => $companyID,
				'amount' => $approveCustomer->approve_amount,
				'description' => 'Pencairan Pinjaman ' . $loans->loan_number . ' (' . $customer->name . ')',
				'status' => 'k',
				'acc_by' => auth()->user()->name,
			]
		];
		Transaction::insert($transactions);
		try {
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
							$accountBalance->end_balance = $accountBalance->end_balance + $transactions[$i]['amount'];
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
							$accountBalance->end_balance = (int) $accountBalance->end_balance + $transactions[$i]['amount'];
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
							$accountBalance->end_balance = (int) $accountBalance->end_balance - $transactions[$i]['amount'];
							$accountBalance->updated_at = now();
							$accountBalance->save();
						}
					}
				}
			}
		} catch (\Exception $e) {
			return redirect()->back()->with('errors', $e->getMessage());
		}
	}
}
