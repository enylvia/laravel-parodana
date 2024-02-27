<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Savings;
use App\Models\Posting;
use App\Models\User;
use App\Models\Company;
use Carbon\Carbon;
use DB;
use Validator;
use Auth;

class WithdrawalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');;
    }
	
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
		
		$customers = Customer::where('branch',$companyID)->get();
		$setorans = Savings::where('posting',0)->paginate(10);
		$getTarikWajib = Savings::where('branch',$companyID)->where('tipe','wajib')->where('status','tarik')->get();
		$tarikWajib = $getTarikWajib->sum('start_balance');
		$getWajib = Savings::where('branch',$companyID)->where('tipe','wajib')->where('status','setor')->get();
		$wajib = $getWajib->sum('start_balance') - $tarikWajib = $getTarikWajib->sum('start_balance');		
		
		$getTarikPokok = Savings::where('branch',$companyID)->where('tipe','pokok')->where('status','tarik')->get();
		$tarikPokok = $getTarikPokok->sum('start_balance');
		$getPokok = Savings::where('branch',$companyID)->where('tipe','pokok')->where('status','setor')->get();
		$pokok = $getPokok->sum('start_balance') - $tarikPokok;		
		
		$getTarikSukarela = Savings::where('branch',$companyID)->where('tipe','sukarela')->where('status','tarik')->get();
		$tarikSukarela = $getTarikSukarela->sum('start_balance');
		$getSukarela = Savings::where('branch',$companyID)->where('tipe','sukarela')->where('status','setor')->get();
		$sukarela = $getSukarela->sum('start_balance') - $tarikSukarela;
		
		$getTabungan = Savings::where('branch',$companyID)->get();		
		$tabungan = $wajib + $pokok + $sukarela;
		
		$customers = Customer::all();
		$setorans = Savings::where('posting',0)->where('status','=','tarik')->paginate(10);
		//$currentDateTime = Carbon::now();
        //$newDateTime = Carbon::now()->addMonth();
        //$newDateTime = Carbon::now()->addMonths(12);
        //$dueDate = $currentDateTime->addDays(7);		
		
		return view('savings.withdrawal.index',compact('customers','setorans','wajib','pokok','sukarela','tabungan'));
	}
	
	public function create()
	{
		$customers = Customer::where('member',1)->get();
		return view('savings.withdrawal.create',compact('customers'));
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
            'tr_date' => 'required|string|max:255',
			'member_number' => 'required|string|max:255',
			'amount' => 'required|string|max:255',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			switch($request->tipe)
			{				
				case('wajib'):					
					//$cek_jumlah = Savings::select(DB::raw('SUM(start_balance) as total_simpanan'))
					$cek_jumlah = Savings::where('member_number', $request->member_number)
					->where('tipe', '=', 'wajib')
					->where('status', '=', 'setor')
					->where('branch',$companyID)->get();					
					//$saldo = $cek_jumlah->total_simpanan;
					$saldo = $cek_jumlah->sum('start_balance');
					
					$customers = Customer::where('member_number',$request->member_number)->first();
					$ambil = str_replace('.', '', $request->amount);
										
					if(empty($saldo))
					{
						return redirect()->back()->with('error', 'Saldo simpanan wajib Anda tidak cukup.');
						//return redirect()->back()->with('error', 'Saldo simpanan wajib Anda tidak cukup.');
					}elseif ($saldo < $ambil) {					
						return redirect()->back()->with('error', 'Saldo simpanan wajib Anda tidak cukup.');
					} else {
						$svg = "SVG";
						$proofNumber = $svg .$this->TabunganUnik(10);
						$tariks = new Savings();
						$tariks->proof_number = $proofNumber;
						$tariks->member_number = $request->member_number;
						$tariks->tr_date = $request->tr_date;
						$tariks->branch = $customers->branch; 
						$tariks->tipe = "wajib";
						$tariks->status = "tarik";
						$tariks->amount = $ambil;
						$tariks->start_balance = $ambil;
						$tariks->created_by = auth()->user()->name;
						$tariks->save();
						//$tariks->id;
						$getTarikWajib = Savings::where('branch',$companyID)->where('tipe',$request->tipe)->where('status','tarik')->get();
						$tarikWajib = $getTarikWajib->sum('start_balance');
						$getWajib = Savings::where('branch',$companyID)->where('tipe',$request->tipe)->where('status','setor')->get();
						$wajib = $getWajib->sum('start_balance') - $tarikWajib = $getTarikWajib->sum('start_balance');
					
						Savings::where('member_number', '=', $request->member_number)
						->where('tipe','=','wajib')
						->update(['end_balance' => $wajib]);
						
						return redirect('/withdrawal')->with('success', 'Tarik Dana Wajib Sukses.');
					}
					break;
				
				case('pokok'):
					
					$cek_jumlah = Savings::select(DB::raw('SUM(start_balance) as total_simpanan'))->where('member_number', $request->member_number)->where('tipe', $request->tipe)->first();
					$saldo = $cek_jumlah->total_simpanan;
					$ambil = str_replace('.', '', $request->amount);
					$cekAkhirSaldo = Savings::select(DB::raw('MAX(end_balance) as saldo_simpanan'))->where('member_number', $request->member_number)->where('tipe', $request->tipe)->first();
					$saldoSisa = $cekAkhirSaldo->saldo_simpanan;
					
					$customers = Customer::where('member_number',$request->member_number)->first();					
					
					if ($saldo < $ambil) {
						//if(empty($saldo)) return redirect()->back()->with('error', 'Saldo simpanan sukarela Anda tidak cukup.');
						return redirect()->back()->with('error', 'Saldo simpanan sukarela Anda tidak cukup.');
					} else {
						$svg = "SVG";
						$proofNumber = $svg .$this->TabunganUnik(10);
						$tariks = new Savings();
						$tariks->proof_number = $proofNumber;
						$tariks->member_number = $request->member_number;
						$tariks->tr_date = $request->tr_date;
						$tariks->branch = $customers->branch; 
						$tariks->tipe = "pokok";
						$tariks->status = "tarik";
						$tariks->amount = $ambil;
						$tariks->start_balance = $ambil;
						$tariks->created_by = auth()->user()->name;
						$tariks->save();
						
						$getTarikPokok = Savings::where('branch',$companyID)->where('tipe',$request->tipe)->where('status','tarik')->get();
						$tarikPokok = $getTarikPokok->sum('start_balance');
						$getPokok = Savings::where('branch',$companyID)->where('tipe',$request->tipe)->where('status','setor')->get();
						$pokok = $getPokok->sum('start_balance') - $tarikPokok;
					
						Savings::where('member_number', '=', $request->member_number)
						->where('tipe','=','pokok')
						->update(['end_balance' => $pokok]);
						
						return redirect()->back()->with('success', 'Tarik dana sukses');
					}
					
				break;
				
				case('sukarela'):
					$cek_jumlah = Savings::select(DB::raw('SUM(start_balance) as total_simpanan'))->where('member_number', $request->member_number)->where('tipe', $request->tipe)->first();
					$saldo = $cek_jumlah->total_simpanan;
					$ambil = str_replace('.', '', $request->amount);
					$cekAkhirSaldo = Savings::select(DB::raw('MAX(end_balance) as saldo_simpanan'))->where('member_number', $request->member_number)->where('tipe', $request->tipe)->first();
					$saldoSisa = $cekAkhirSaldo->saldo_simpanan;
					
					$customers = Customer::where('member_number',$request->member_number)->first();					
					
					if ($saldo < $ambil) {						
						return redirect('/withdrawal')->with('error', 'Saldo simpanan sukarela Anda tidak cukup.');
					} else {
						$svg = "SVG";
						$proofNumber = $svg .$this->TabunganUnik(10);
						$tariks = new Savings();
						$tariks->proof_number = $proofNumber;
						$tariks->member_number = $request->member_number;
						$tariks->tr_date = $request->tr_date;
						$tariks->branch = $customers->branch; 
						$tariks->tipe = "sukarela";
						$tariks->status = "tarik";
						$tariks->amount = $ambil;
						$tariks->start_balance = $ambil;
						$tariks->created_by = auth()->user()->name;
						$tariks->save();
						
						$getTarikSukarela = Savings::where('branch',$companyID)->where('tipe',$request->tipe)->where('status','tarik')->get();
						$tarikSukarela = $getTarikSukarela->sum('start_balance');
						$getSukarela = Savings::where('branch',$companyID)->where('tipe',$request->tipe)->where('status','setor')->get();
						$sukarela = $getSukarela->sum('start_balance') - $tarikSukarela;
					
						Savings::where('member_number', '=', $request->member_number)
						->where('tipe','=','sukarela')
						->update(['end_balance' => $sukarela]);
						
						return redirect('/withdrawal')->with('success', 'Tarik dana sukses');
					}
				
				break;
				
				default:
				return redirect()->back()->with('error', 'Transaction Add Unsuccessfully');
				
			}
		}
				
	}
	
	public function journal($id)
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->company_id;
			}
		}
			
		$employees = Employee::where('user_id',auth()->user()->id)->first();				
		$akuns = Operational::where('transaction_no',$id)->get();		
		
		foreach($akuns as $key => $akun) 
		{
			$groups = AccountGroup::where('account_number',$akun->account_number)->get();
			//dd($groups);
			//$data = Journal::where('transaction_no',$id)->where('id',$akun->id)->first();	
			foreach($groups as $group)
			{				
				$data = new Journal();
				$data->account_id = $group->id;
				$data->account_number = $group->account_number;	
				$data->tipe = $akun->tipe;	
				$data->proof_number = $akun->transaction_no;
				$data->transaction_date = $akun->date_time;
				$data->company_id = $companyID;
				$data->description = $akun->description;
				$data->nominal = $akun->amount;
				$data->save();
				Operational::where('transaction_no', '=', $akun->transaction_no)->update(['journal' => 1 ]);
			}					
		}
		
		return redirect('/withdrawal')->with('success', 'Journal successfully');
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
	
}
