<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Savings;
use App\Models\Loan;
use App\Models\Journal;
use DB;

class HomeController extends Controller
{
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
		$companies = Company::all();
		$member = Customer::where('status','member')->where('branch',$companyID)->get();
		//foreach($member as $nasabah)
		//{
			//$month = $nasabah->created_at;
		//	$year = $nasabah->created_at;
		//}
				
		//foreach($months as $month)
		//{
		//	$bulan = $month;
		//}
        $total_member = $member->count();
		$tabungan = Savings::where('branch',$companyID)->get();
        $total_tabungan = !empty($tabungan->amount) ? $tabungan->sum('amount') : 0;
		$pinjaman = Loan::where('company_id',$companyID)->get();
        $total_pinjaman = $pinjaman->sum('loan_amount');
		
		$bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];        
		//dd($companies);
		//$tahuns = Loan::select(DB::raw('count(id) as `data`'), DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
		//$tahuns = Loan::where('company_id',$companyID)
		//->groupBy('created_at')		
		//->first();
		//$tahun = date('Y', strtotime($tahuns->created_at));
        //$periode = date('F Y', strtotime($waktu));
		$tahun = '';
	
		$chart_pinjaman = Loan::select(DB::raw("SUM(loan_amount) as count"))
			->where('company_id',$companyID)
			->orderBy("created_at")
			->groupBy(DB::raw("year(created_at)"))
			->get()->toArray();
		$chart_pinjaman = array_column($chart_pinjaman, 'count');
		
		$chart_tabungan = Savings::select(DB::raw("SUM(amount) as count"))
			->where('branch',$companyID)
			->orderBy("created_at")
			->groupBy(DB::raw("year(created_at)"))
			->get()->toArray();
		$chart_tabungan = array_column($chart_tabungan, 'count');
		
		$chart_pinjaman = json_encode($chart_pinjaman,JSON_NUMERIC_CHECK);
		$chart_tabungan = json_encode($chart_tabungan,JSON_NUMERIC_CHECK);
		
        return view('home',compact('users','companyID','total_member','companies',
		'total_tabungan','total_pinjaman','bulan','tahun','chart_pinjaman','chart_tabungan'));		
    }
	
	public function totalBox($branch)
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		
		$companies = Company::where('id',$branch)->first();
		$member = Customer::where('status','member')->where('branch',$branch)->get();
        $total_member = $member->count();
		$total_tabungan = Savings::where('branch', $branch)
			->select(DB::raw('SUM(CAST(amount AS DECIMAL(10, 0))) as total_amount'))
			->value('total_amount');

		$pinjaman = Loan::where('company_id',$branch)->get();
        $total_pinjaman = $pinjaman->sum('loan_amount');
        $total_pendapatan = Journal::where('account_number',4)->where('company_id', $companies->company_id)->sum('nominal');
        $total_beban = Journal::where('account_number',5)->where('company_id', $companies->company_id)->sum('nominal');
        $laba_rugi = $total_pendapatan - $total_beban;
		//dd($laba_rugi);
		//$result = ['totalMember' => $total_member, 'totalTabungan' => $total_tabungan];
		//dd($result);
		
		//return $result;
		//return response()->json(["totalMember" => $total_member, "totalTabungan" => $total_tabungan]);
		return response()->json(array('result' => 'success', 
								'totalMember' => $total_member, 
								'totalTabungan' => $total_tabungan, 
								'totalPinjaman' => $total_pinjaman,
								'totalLaba' 	=> $laba_rugi));
	}
	
	public function chartjs()
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
				$getBranch = $company->company_id;
			}
		}
		$companies = Company::where('id',$branch)->first();
		//$member = Customer::where('status','member')->where('branch',$branch)->get();
        //$total_member = $member->count();
		//$tabungan = Savings::where('branch',$branch)->get();
        //$total_tabungan = $tabungan->sum('amount');
		//$pinjaman = Loan::where('company_id',$branch)->get();
        //$total_pinjaman = $pinjaman->sum('loan_amount');
        //$total_pendapatan = Journal::where('account_number',4)->where('company_id', $companies->company_id)->sum('nominal');
        //$total_beban = Journal::where('account_number',5)->where('company_id', $companies->company_id)->sum('nominal');
        //$laba_rugi = $total_pendapatan - $total_beban;
		
		$chart_pinjaman = Loan::select(DB::raw("SUM(loan_amount) as count"))
			->where('company_id',$companyID)
			->orderBy("created_at")
			->groupBy(DB::raw("year(created_at)"))
			->get()->toArray();
		$chart_pinjaman = array_column($chart_pinjaman, 'count');
		
		$chart_tabungan = Savings::select(DB::raw("SUM(amount) as count"))
			->where('company_id',$companyID)
			->orderBy("created_at")
			->groupBy(DB::raw("year(created_at)"))
			->get()->toArray();
		$chart_tabungan = array_column($chart_tabungan, 'count');
		
		return view('home')
				->with('chart_pinjaman',json_encode($chart_pinjaman,JSON_NUMERIC_CHECK))
				->with('chart_tabungan',json_encode($chart_tabungan,JSON_NUMERIC_CHECK));
				
	}
	
}
