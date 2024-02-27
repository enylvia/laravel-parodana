<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	use HasFactory;
	protected $table = 'customer';

	public function setEmailAttribute($value)
	{
		if (empty($value)) {
			$this->attributes['email'] = NULL;
		} else {
			$this->attributes['email'] = $value;
		}
	}

	public function customerContract()
	{
		return $this->hasMany(CustomerContract::class, 'customer_id');
	}

	public function customerApprove()
	{
		return $this->hasMany(CustomerApprove::class, 'customer_id');
	}

	public function survey()
	{
		return $this->hasMany(CustomerSurvey::class, 'customer_id');
	}

	public function document()
	{
		return $this->hasMany(CustomerDocument::class, 'customer_id');
	}

	public function insurance()
	{
		return $this->hasMany(CustomerInsurance::class, 'customer_id');
	}

	public function memberNumber($companyCode, $customerID)
	{
		$time = now()->format('Ymd');
		return $companyCode . $time . $customerID;
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
	}
}
