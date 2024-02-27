<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Company;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerContract;
use App\Models\AccountGroup;
use App\Models\Journal;
use App\Models\CardType;
use App\Models\Education;
use App\Models\Maritial;
use App\Models\Religion;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Loan;
use App\Models\Installment;
use App\Models\Tempo;
use App\Helper\Terbilang;
use Carbon\Carbon;
use DPDF;
use TPDF;
use DB;
use Validator;
use Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function view_member()
	{
		return view('report.member.view');
	}
	
	public function print_member(Request $request)
	{			
		$pdf = new TPDF;        

		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->company_id;
			}
		}
		
        $profiles = Company::where('company_id',$companyID)->get();
		
		foreach($profiles as $profile)
		{
			$profileName = $profile->name;
			$profileAddress = $profile->address;
			$provinsi = Provinsi::where('id',$profile->provinsi)->first();
			$kabupaten = Kabupaten::where('id',$profile->kabupaten)->first();
			$kecamatan = Kecamatan::where('id',$profile->kecamatan)->first();
			$kelurahan = Kelurahan::where('id',$profile->kelurahan)->first();
		}
		
		$customers = Customer::join('customer_contract', 'customer_contract.customer_id', '=', 'customer.id' )
				->select('customer.*', 'customer_contract.status')
				->where('customer.branch',$companyID)
				->where('customer_contract.status',$request->fStatus)
				->get();
        
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
        $pdf::Cell(0, 10, "".$kelurahan->nama." , ".$kecamatan->nama." , ".$kabupaten->nama." , ".$provinsi->nama, 'B', 1,  'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
		//$pdf::Cell(0, 8, .$kelurahan->nama, 0, 1, 'C');
		
        $pdf::Ln();

        // Neraca Saldo
        $pdf::SetFont('Arial', 'B', 14);
        $pdf::Cell(0, 10, "DAFTAR ANGGOTA", 0, 2, 'C');

        $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(20, 8, "NO", 1, 0, 'C');
        $pdf::Cell(40, 8, "NO. ANGGOTA", 1, 0, 'C');
        $pdf::Cell(100, 8, "NAMA LENGKAP", 1, 0, 'C');
        $pdf::Ln();        
				
		dd($customers);
		foreach($customers as $customer)
		{
			$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(20, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(40, 8, $customer->member_number, 1, 0, 'L');
	        $pdf::Cell(100, 8, $customer->name, 1, 0, 'L');
	        $pdf::Ln();
		}		
		
        // Footer
        $pdf::SetY(179);
        $pdf::SetX(165);
        $pdf::SetFont('Arial','I',8);
        $pdf::Cell(0,10,"Dicetak Oleh Akuntan : ". $profileName ." Pada ".date("d-m-Y H:i:s")
        ." WIB", 0, 0, 'C');
		
		ob_end_clean();
		return $pdf::Output('laporan_member.pdf','I');
			
	}
	
	public function view_neraca()
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
			}
		}				
		//$companies = Company::where('id',$companyID)->get();
		
		$daftar_neraca = Journal::selectRaw("CONCAT(MONTH(transaction_date), '-', YEAR(transaction_date)) as waktu")->distinct()->get();        
        $total_neraca = $daftar_neraca->count();
		
		$daftar_akun = AccountGroup::pluck('account_name', 'id');
        return view('report.neraca.view',compact('daftar_neraca','total_neraca'));
		
	}
		
	public function print_neraca($waktu)
	{
		if(empty($waktu)) return redirect('report/neraca');
		
		$pdf = new TPDF;
        
        $bulan = date('m', strtotime($waktu));
        $tahun = date('Y', strtotime($waktu));
        $periode = date('F Y', strtotime($waktu));
        $periode = strtoupper($periode);

        $items = AccountGroup::join('journals','account_group.id','=','journals.account_id')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->get();        

		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->company_id;
			}
		}
		
        $profiles = Company::where('company_id',$companyID)->get();
		
		foreach($profiles as $profile)
		{
			$profileName = $profile->name;
			$profileAddress = $profile->address;
			$provinsi = Provinsi::where('id',$profile->provinsi)->first();
			$kabupaten = Kabupaten::where('id',$profile->kabupaten)->first();
			$kecamatan = Kecamatan::where('id',$profile->kecamatan)->first();
			$kelurahan = Kelurahan::where('id',$profile->kelurahan)->first();
		}
        // $total_akun = Akun::all()->count();
        $total_saldo_debet_aktiva = 0;
        $total_saldo_kredit_aktiva = 0;
        $total_saldo_debet_pasiva = 0;
        $total_saldo_kredit_pasiva = 0;
        $total_saldo_debet_modal = 0;
        $total_saldo_kredit_modal = 0;
        $total_saldo_debet_pendapatan = 0;
        $total_saldo_kredit_pendapatan = 0;
        $total_saldo_debet_beban = 0;
        $total_saldo_kredit_beban = 0;

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
        $pdf::Cell(0, 10, "".$kelurahan->nama." , ".$kecamatan->nama." , ".$kabupaten->nama." , ".$provinsi->nama, 'B', 1,  'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
		//$pdf::Cell(0, 8, .$kelurahan->nama, 0, 1, 'C');
		//{{$company->address}}, {{$kelurahan->nama}} {{$company->zip_code}} {{$kecamatan->nama}} {{$kabupaten->nama}} - {{$provinsi->nama}}
        $pdf::Ln();

        // Neraca Saldo
        $pdf::SetFont('Arial', 'B', 14);
        $pdf::Cell(0, 10, "NERACA $periode", 0, 2, 'C');

        $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(20, 8, "NO", 1, 0, 'C');
        $pdf::Cell(20, 8, "NO. AKUN", 1, 0, 'C');
        $pdf::Cell(100, 8, "NAMA AKUN", 1, 0, 'C');
        $pdf::Cell(60, 8, "DEBET", 1, 0, 'C');
        $pdf::Cell(60, 8, "KREDIT", 1, 0, 'C');
        $pdf::Ln();

        $aktivas = AccountGroup::where('account_number',1)->orderBy('account_number', 'asc')->get();
		//dd($pendaptans);
		foreach($aktivas as $key => $item)
		{
			$total_debet_aktiva = Journal::where('tipe', 'd')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');            
            $total_kredit_aktiva = Journal::where('tipe', 'k')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');                        
            //$parents = Journal::where('account_id',$item->id)->first();
        	//$this->get_akun_child($waktu, $item->id);
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(20, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(20, 8, $item->account_number, 1, 0, 'L');
	        $pdf::Cell(100, 8, $item->account_name, 1, 0, 'L');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_debet_aktiva, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_kredit_aktiva, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Ln();
			
			$total_saldo_debet_aktiva+= $total_debet_aktiva; 
	        $total_saldo_kredit_aktiva += $total_kredit_aktiva; 
    	}

        $pasivas = AccountGroup::where('account_number',2)->orderBy('account_number', 'asc')->get();
		//dd($pendaptans);
		foreach($pasivas as $key => $item)
		{
			$total_debet_pasiva = Journal::where('tipe', 'd')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');            
            $total_kredit_pasiva = Journal::where('tipe', 'k')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');                        
            //$parents = Journal::where('account_id',$item->id)->first();
        	//$this->get_akun_child($waktu, $item->id);
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(20, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(20, 8, $item->account_number, 1, 0, 'L');
	        $pdf::Cell(100, 8, $item->account_name, 1, 0, 'L');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_debet_pasiva, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_kredit_pasiva, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Ln();
			
			$total_saldo_debet_pasiva+= $total_debet_pasiva; 
	        $total_saldo_kredit_pasiva += $total_kredit_pasiva; 
    	}

        $modals = AccountGroup::where('account_number',3)->orderBy('account_number', 'asc')->get();
		//dd($pendaptans);
		foreach($modals as $key => $item)
		{
			$total_debet_modal = Journal::where('tipe', 'd')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');            
            $total_kredit_modal = Journal::where('tipe', 'k')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');                        
            //$parents = Journal::where('account_id',$item->id)->first();
        	//$this->get_akun_child($waktu, $item->id);
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(20, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(20, 8, $item->account_number, 1, 0, 'L');
	        $pdf::Cell(100, 8, $item->account_name, 1, 0, 'L');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_debet_modal, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_kredit_modal, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Ln();
			
			$total_saldo_debet_modal+= $total_debet_modal; 
	        $total_saldo_kredit_modal += $total_kredit_modal; 
    	}
		
		$pendaptans = AccountGroup::where('account_number',4)->orderBy('account_number', 'asc')->get();
		//dd($pendaptans);
		foreach($pendaptans as $key => $item)
		{
			$total_debet_pendapatan = Journal::where('tipe', 'd')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');            
            $total_kredit_pendapatan = Journal::where('tipe', 'k')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');                        
            //$parents = Journal::where('account_id',$item->id)->first();
        	//$this->get_akun_child($waktu, $item->id);
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(20, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(20, 8, $item->account_number, 1, 0, 'L');
	        $pdf::Cell(100, 8, $item->account_name, 1, 0, 'L');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_debet_pendapatan, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_kredit_pendapatan, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Ln();
			
			$total_saldo_debet_pendapatan += $total_debet_pendapatan; 
	        $total_saldo_kredit_pendapatan += $total_kredit_pendapatan; 
    	}		

        $bebans = AccountGroup::where('account_number',5)->orderBy('account_number', 'asc')->get();
		//dd($pendaptans);
		foreach($bebans as $key => $item)
		{
			$total_debet_beban = Journal::where('tipe', 'd')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');            
            $total_kredit_beban = Journal::where('tipe', 'k')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');                        
             
        	//$this->get_akun_child($waktu, $item->id);
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(20, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(20, 8, $item->account_number, 1, 0, 'L');
	        $pdf::Cell(100, 8, $item->account_name, 1, 0, 'L');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_debet_beban, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_kredit_beban, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Ln();
			
			$total_saldo_debet_beban += $total_debet_beban; 
	        $total_saldo_kredit_beban += $total_kredit_beban; 
    	}		
		
		$total_neraca_debet = $total_saldo_debet_aktiva + $total_saldo_debet_pasiva + $total_saldo_debet_modal + $total_saldo_debet_pendapatan + $total_saldo_debet_beban; 
	    $total_neraca_kredit = $total_saldo_kredit_aktiva + $total_saldo_kredit_pasiva + $total_saldo_kredit_modal + $total_saldo_kredit_pendapatan + $total_saldo_kredit_beban; 

	    $id = AccountGroup::pluck('id');

	    $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(140, 8, "TOTAL NERACA", 1, 0, 'C');
        $pdf::SetFont('Arial', '', 12);
        $pdf::Cell(60, 8, "Rp. ".number_format($total_neraca_debet, 0, ',', '.').",-", 1, 0, 'C');
        $pdf::Cell(60, 8, "Rp. ".number_format($total_neraca_kredit, 0, ',', '.').",-", 1, 0, 'C');
        $pdf::Ln();

        $pdf::SetFont('Arial', 'B', 10);
        $pdf::Cell(260, 8, "TERBILANG :", 1, 0, 'L');
		$pdf::Ln();
				
        $pdf::SetFont('Arial', 'B', 8);
		$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($total_neraca_debet)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($total_neraca_kredit)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();

        // Footer
        $pdf::SetY(179);
        $pdf::SetX(165);
        $pdf::SetFont('Arial','I',8);
        $pdf::Cell(0,10,"Dicetak Oleh Akuntan : ". $profileName ." Pada ".date("d-m-Y H:i:s")
        ." WIB", 0, 0, 'C');
		
		ob_end_clean();
		return $pdf::Output('laporan_neraca' .$periode. '.pdf','I');
		
	}
	
	public function view_cashflow()
	{
		$daftar_neraca = Journal::selectRaw("CONCAT(MONTH(transaction_date), '-', YEAR(transaction_date)) as waktu")->distinct()->get();        
        $total_neraca = $daftar_neraca->count();
		
		return view('report.cashflow.view',compact('daftar_neraca','total_neraca'));		
	}
	
	public function print_cashflow($waktu)
	{
		if(empty($waktu)) return redirect('report/profitloss');
		
		$pdf = new TPDF;
        
        $bulan = date('m', strtotime($waktu));
        $tahun = date('Y', strtotime($waktu));
        $periode = date('F Y', strtotime($waktu));
        $periode = strtoupper($periode);

        $items = AccountGroup::join('journals','account_group.id','=','journals.account_id')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->get();        

		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->company_id;
			}
		}
		
        $profiles = Company::where('company_id',$companyID)->get();
		
		foreach($profiles as $profile)
		{
			$profileName = $profile->name;
			$profileAddress = $profile->address;
			$provinsi = Provinsi::where('id',$profile->provinsi)->first();
			$kabupaten = Kabupaten::where('id',$profile->kabupaten)->first();
			$kecamatan = Kecamatan::where('id',$profile->kecamatan)->first();
			$kelurahan = Kelurahan::where('id',$profile->kelurahan)->first();
		}
        // $total_akun = Akun::all()->count();
        $id = AccountGroup::pluck('id');

        //$root = AccountGroup::where('id_parent', 0)->orderBy('account_number', 'asc')->get();

        //foreach ($root as $key) 
        //{
        //  $this->get_akun_child($waktu, $key->id);
        //}

        $total_saldo_debet_kas = 0;
        $total_saldo_kredit_kas = 0;

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
        $pdf::Cell(0, 10, "".$kelurahan->nama." , ".$kecamatan->nama." , ".$kabupaten->nama." , ".$provinsi->nama, 'B', 1,  'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
		//$pdf::Cell(0, 8, .$kelurahan->nama, 0, 1, 'C');
		//{{$company->address}}, {{$kelurahan->nama}} {{$company->zip_code}} {{$kecamatan->nama}} {{$kabupaten->nama}} - {{$provinsi->nama}}
        $pdf::Ln();

        // Neraca Saldo
        $pdf::SetFont('Arial', 'B', 14);
        $pdf::Cell(0, 10, "ARUS KAS $periode", 0, 2, 'C');

        $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(20, 8, "NO", 1, 0, 'C');
        $pdf::Cell(20, 8, "NO. AKUN", 1, 0, 'C');
        $pdf::Cell(100, 8, "NAMA AKUN", 1, 0, 'C');
        $pdf::Cell(60, 8, "DEBET", 1, 0, 'C');
        $pdf::Cell(60, 8, "KREDIT", 1, 0, 'C');
        $pdf::Ln();        
		
		//$pendaptans = AccountGroup::join('journals','account_group.id','=','journals.account_id')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->where('account_group.account_number',4)->orderBy('transaction_date', 'asc')->get();
		
		$aktivas = AccountGroup::where('account_number',1)->orderBy('account_number', 'asc')->get();
		//dd($pendaptans);
		foreach($aktivas as $key => $item)
		{
			$total_debet_kas = Journal::where('tipe', 'd')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');
            $total_kredit_kas = Journal::where('tipe', 'k')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');
            //$parents = Journal::where('account_id',$item->id)->first();
        	//$this->get_akun_child($waktu, $item->id);
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(20, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(20, 8, $item->account_number, 1, 0, 'L');
	        $pdf::Cell(100, 8, $item->account_name, 1, 0, 'L');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_debet_kas, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_kredit_kas, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Ln();
			
			$total_saldo_debet_kas += $total_debet_kas; 
	        $total_saldo_kredit_kas += $total_kredit_kas; 
    	}		
		
		$pdf::SetFont('Arial', 'B', 12);
		$pdf::Cell(140, 8, "TOTAL KAS", 1, 0, 'C');
		$pdf::SetFont('Arial', '', 12);
		$pdf::Cell(60, 8, "Rp. ".number_format($total_saldo_debet_kas, 0, ',', '.').",-", 1, 0, 'C');
		$pdf::Cell(60, 8, "Rp. ".number_format($total_saldo_kredit_kas, 0, ',', '.').",-", 1, 0, 'C');
		$pdf::Ln();

		$pdf::SetFont('Arial', 'B', 10);
		$pdf::Cell(260, 8, "TERBILANG :", 1, 0, 'L');
		$pdf::Ln();
						
		$pdf::SetFont('Arial', 'B', 8);
		$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($total_saldo_debet_kas)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($total_saldo_kredit_kas)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::Ln();
		
		
        // Footer
        $pdf::SetY(179);
        $pdf::SetX(165);
        $pdf::SetFont('Arial','I',8);
        $pdf::Cell(0,10,"Dicetak Oleh Akuntan : ". $profileName ." Pada ".date("d-m-Y H:i:s")
        ." WIB", 0, 0, 'C');
		
		ob_end_clean();
		return $pdf::Output('laporan_arus_kas' .$periode. '.pdf','I');
	}
	
	public function profitloss()
	{
		$daftar_neraca = Journal::selectRaw("CONCAT(MONTH(transaction_date), '-', YEAR(transaction_date)) as waktu")->distinct()->get();        
        $total_neraca = $daftar_neraca->count();
		return view('report.profitloss.rptProfitLoss',compact('daftar_neraca','total_neraca'));
		
	}
	
	public function print_profitloss($waktu)
	{
		if(empty($waktu)) return redirect('report/profitloss');
		
		$pdf = new TPDF;
        
        $bulan = date('m', strtotime($waktu));
        $tahun = date('Y', strtotime($waktu));
        $periode = date('F Y', strtotime($waktu));
        $periode = strtoupper($periode);

        $items = AccountGroup::join('journals','account_group.id','=','journals.account_id')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->get();        

		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->company_id;
			}
		}
		
        $profiles = Company::where('company_id',$companyID)->get();
		
		foreach($profiles as $profile)
		{
			$profileName = $profile->name;
			$profileAddress = $profile->address;
			$provinsi = Provinsi::where('id',$profile->provinsi)->first();
			$kabupaten = Kabupaten::where('id',$profile->kabupaten)->first();
			$kecamatan = Kecamatan::where('id',$profile->kecamatan)->first();
			$kelurahan = Kelurahan::where('id',$profile->kelurahan)->first();
		}
        // $total_akun = Akun::all()->count();
        $id = AccountGroup::pluck('id');

        //$root = AccountGroup::where('id_parent', 0)->orderBy('account_number', 'asc')->get();

        //foreach ($root as $key) 
        //{
        //  $this->get_akun_child($waktu, $key->id);
        //}

        $total_saldo_debet_pendapatan = 0;
        $total_saldo_kredit_pendapatan = 0;
        $total_saldo_debet_beban = 0;
        $total_saldo_kredit_beban = 0;

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
        $pdf::Cell(0, 10, "".$kelurahan->nama." , ".$kecamatan->nama." , ".$kabupaten->nama." , ".$provinsi->nama, 'B', 1,  'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
		//$pdf::Cell(0, 8, .$kelurahan->nama, 0, 1, 'C');
		//{{$company->address}}, {{$kelurahan->nama}} {{$company->zip_code}} {{$kecamatan->nama}} {{$kabupaten->nama}} - {{$provinsi->nama}}
        $pdf::Ln();

        // Neraca Saldo
        $pdf::SetFont('Arial', 'B', 14);
        $pdf::Cell(0, 10, "LABA RUGI $periode", 0, 2, 'C');

        $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(20, 8, "NO", 1, 0, 'C');
        $pdf::Cell(20, 8, "NO. AKUN", 1, 0, 'C');
        $pdf::Cell(100, 8, "NAMA AKUN", 1, 0, 'C');
        $pdf::Cell(60, 8, "DEBET", 1, 0, 'C');
        $pdf::Cell(60, 8, "KREDIT", 1, 0, 'C');
        $pdf::Ln();        
		
		//$pendaptans = AccountGroup::join('journals','account_group.id','=','journals.account_id')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->where('account_group.account_number',4)->orderBy('transaction_date', 'asc')->get();
		
		$pendaptans = AccountGroup::where('account_number',4)->orderBy('account_number', 'asc')->get();
		//dd($pendaptans);
		foreach($pendaptans as $key => $item)
		{
			$total_debet_pendapatan = Journal::where('tipe', 'd')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');            
            $total_kredit_pendapatan = Journal::where('tipe', 'k')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');                        
            //$parents = Journal::where('account_id',$item->id)->first();
        	//$this->get_akun_child($waktu, $item->id);
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(20, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(20, 8, $item->account_number, 1, 0, 'L');
	        $pdf::Cell(100, 8, $item->account_name, 1, 0, 'L');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_debet_pendapatan, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_kredit_pendapatan, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Ln();
			
			$total_saldo_debet_pendapatan += $total_debet_pendapatan; 
	        $total_saldo_kredit_pendapatan += $total_kredit_pendapatan; 
    	}
		
		$pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(140, 8, "TOTAL PENDAPATAN", 1, 0, 'C');
        $pdf::SetFont('Arial', '', 12);
        $pdf::Cell(60, 8, "Rp. ".number_format($total_saldo_debet_pendapatan, 0, ',', '.').",-", 1, 0, 'C');
        $pdf::Cell(60, 8, "Rp. ".number_format($total_saldo_kredit_pendapatan, 0, ',', '.').",-", 1, 0, 'C');
        $pdf::Ln();

        $pdf::SetFont('Arial', 'B', 10);
        $pdf::Cell(260, 8, "TERBILANG :", 1, 0, 'L');
		$pdf::Ln();
				
        $pdf::SetFont('Arial', 'B', 8);
        //$pdf::Cell(132.5, 15, strtoupper(Terbilang::bilang($total_saldo_debet)). "RUPIAH", 1, 0, 'J');
        //$pdf::Cell(132.5, 15, strtoupper(Terbilang::bilang($total_saldo_kredit)) . "RUPIAH", 1, 0, 'J');
		$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($total_saldo_debet_pendapatan)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($total_saldo_kredit_pendapatan)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();

        $bebans = AccountGroup::where('account_number',5)->orderBy('account_number', 'asc')->get();
		//dd($pendaptans);
		foreach($bebans as $key => $item)
		{
			$total_debet_beban = Journal::where('tipe', 'd')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');            
            $total_kredit_beban = Journal::where('tipe', 'k')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');                        
             
        	//$this->get_akun_child($waktu, $item->id);
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(20, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(20, 8, $item->account_number, 1, 0, 'L');
	        $pdf::Cell(100, 8, $item->account_name, 1, 0, 'L');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_debet_beban, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_kredit_beban, 0, ',', '.').",-", 1, 0, 'C');
	        $pdf::Ln();
			
			$total_saldo_debet_beban += $total_debet_beban; 
	        $total_saldo_kredit_beban += $total_kredit_beban; 
    	}
		
		$pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(140, 8, "TOTAL BEBAN", 1, 0, 'C');
        $pdf::SetFont('Arial', '', 12);
        $pdf::Cell(60, 8, "Rp. ".number_format($total_saldo_debet_beban, 0, ',', '.').",-", 1, 0, 'C');
        $pdf::Cell(60, 8, "Rp. ".number_format($total_saldo_kredit_beban, 0, ',', '.').",-", 1, 0, 'C');
        $pdf::Ln();

        $pdf::SetFont('Arial', 'B', 10);
        $pdf::Cell(260, 8, "TERBILANG :", 1, 0, 'L');
		$pdf::Ln();
				
        $pdf::SetFont('Arial', 'B', 8);
        //$pdf::Cell(132.5, 15, strtoupper(Terbilang::bilang($total_saldo_debet)). "RUPIAH", 1, 0, 'J');
        //$pdf::Cell(132.5, 15, strtoupper(Terbilang::bilang($total_saldo_kredit)) . "RUPIAH", 1, 0, 'J');
		$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($total_saldo_debet_beban)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($total_saldo_kredit_beban)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();
		
		$laba_debet = $total_saldo_debet_pendapatan - $total_saldo_debet_beban; 
	    $laba_kredit = $total_saldo_kredit_pendapatan - $total_saldo_kredit_beban; 

	    $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(140, 8, "LABA", 1, 0, 'C');
        $pdf::SetFont('Arial', '', 12);
        $pdf::Cell(60, 8, "Rp. ".number_format($laba_debet, 0, ',', '.').",-", 1, 0, 'C');
        $pdf::Cell(60, 8, "Rp. ".number_format($laba_kredit, 0, ',', '.').",-", 1, 0, 'C');
        $pdf::Ln();

        $pdf::SetFont('Arial', 'B', 10);
        $pdf::Cell(260, 8, "TERBILANG :", 1, 0, 'L');
		$pdf::Ln();
				
        $pdf::SetFont('Arial', 'B', 8);
		$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($laba_debet)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($laba_kredit)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();

        // Footer
        $pdf::SetY(179);
        $pdf::SetX(165);
        $pdf::SetFont('Arial','I',8);
        $pdf::Cell(0,10,"Dicetak Oleh Akuntan : ". $profileName ." Pada ".date("d-m-Y H:i:s")
        ." WIB", 0, 0, 'C');
		
		ob_end_clean();
		return $pdf::Output('laporan_laba_rugi' .$periode. '.pdf','I');
	}

	public function get_akun_child($waktu, $parent=0)
	{
		$pdf = new TPDF;

		$bulan = date('m', strtotime($waktu));
        $tahun = date('Y', strtotime($waktu));
        $periode = date('F Y', strtotime($waktu));
        $periode = strtoupper($periode);
        
        //$parent = AccountGroup::join('journals','account_group.id','=','journals.account_id')->where('account_group.id',$parent)->first();
        $id = AccountGroup::where('id_parent', 0)->orderBy('account_number', 'asc')->get();
        //$id = AccountGroup::pluck('id');
        
        $total_saldo_debet = 0;
        $total_saldo_kredit = 0;

        foreach($id as $i)
        {
        	

        	$menu = AccountGroup::where('id_parent',$i->id)->get();
        	foreach($menu as $key => $idih)
        	{
        		
		        $total_debet = Journal::where('tipe', 'd')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $idih->id)->sum('nominal');            
		        $total_kredit = Journal::where('tipe', 'k')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $idih->id)->sum('nominal');    		
	    		$parents = AccountGroup::where('id',$idih->id)->first();	        
	    		//$this->get_akun_child($waktu, $key->id);
	    		$pdf::SetFont('Arial', '', 12);
		        $pdf::Cell(20, 8, $key+1, 1, 0, 'C');
		        $pdf::Cell(20, 8, $parents->account_number, 1, 0, 'L');
		        $pdf::Cell(100, 8, $parents->account_name, 1, 0, 'L');
		        $pdf::Cell(60, 8, "Rp. ".number_format($total_debet, 0, ',', '.').",-", 1, 0, 'C');
		        $pdf::Cell(60, 8, "Rp. ".number_format($total_kredit, 0, ',', '.').",-", 1, 0, 'C');
		        $pdf::Ln();
	        	
	    	}	        

	        //foreach ($menu as $key) {
            //  $this->get_akun_child($waktu, $key->id);
            //}

    	}
    }

    public function installment()
	{
		$customers = Customer::all();
		$installments = Installment::all();        
        
		return view('report.installment.view',compact('customers','installments'));
		
	}
	
	public function getInstallment($id)
	{
		$customers = Customer::all();
		$installments = Installment::where('member_number',$id)->get();
		$loans = Loan::where('member_number',$id)->first();
        		
		return response()->json(array('result' => 'success', 
								'installments' => $installments,
								'pokok' => $loans->pay_principal,
								'bunga' => $loans->pay_interest));
		
	}
	
	public function printPdf(Request $request)
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->company_id;
			}
		}
		
        $profiles = Company::where('company_id',$companyID)->get();
		
		foreach($profiles as $profile)
		{
			$profileName = $profile->name;
			$profileAddress = $profile->address;
			$provinsi = Provinsi::where('id',$profile->provinsi)->first();
			$kabupaten = Kabupaten::where('id',$profile->kabupaten)->first();
			$kecamatan = Kecamatan::where('id',$profile->kecamatan)->first();
			$kelurahan = Kelurahan::where('id',$profile->kelurahan)->first();
		}
		
		$pdf = new TPDF;
		
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
        $pdf::Cell(0, 10, "".$kelurahan->nama." , ".$kecamatan->nama." , ".$kabupaten->nama." , ".$provinsi->nama, 'B', 1,  'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
		//$pdf::Cell(0, 8, .$kelurahan->nama, 0, 1, 'C');
		//{{$company->address}}, {{$kelurahan->nama}} {{$company->zip_code}} {{$kecamatan->nama}} {{$kabupaten->nama}} - {{$provinsi->nama}}
        $pdf::Ln();

        // ANGSURAN
        $pdf::SetFont('Arial', 'B', 14);
        $pdf::Cell(0, 10, "DAFTAR ANGSURAN", 0, 2, 'C');
		$pdf::Ln();
		
		$customers = Customer::where('id',$request->customer)->first();
		//$installments = Installment::where('member_number',$customers->member_number)->get();		
		$installments = Installment::join('loans', 'installment.member_number', '=' ,'loans.member_number')					
                     ->select('loans.loan_remaining','loans.loan_amount','loans.pay_principal','loans.pay_interest',
					 'loans.time_period','installment.*') 
                     ->where('loans.member_number',$customers->member_number)->get();
		$pdf::SetFont('Arial', 'B', 12);			
		$pdf::MultiCell(40, 8, "NASABAH", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
		$pdf::MultiCell(90, 8, $customers->name, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::Ln();
		$pdf::SetFont('Arial', 'B', 12);			
		$pdf::MultiCell(40, 8, "NO. MEMBER", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 8, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
		$pdf::MultiCell(90, 8, $customers->member_number, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::Ln();
		$pdf::Ln();
		
        $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(20, 8, "NO", 1, 0, 'C');
        $pdf::Cell(40, 8, "NO. TRANSAKSI", 1, 0, 'C');
        $pdf::Cell(40, 8,"TGL. TRANSAKSI", 1, 0, 'C');
        $pdf::Cell(30, 8,"CARA BAYAR", 1, 0, 'C');
        $pdf::Cell(50, 8, "POKOK", 1, 0, 'C');
		$pdf::Cell(50, 8, "BUNGA", 1, 0, 'C');
		$pdf::Cell(50, 8, "JUMLAH", 1, 0, 'C');
        $pdf::Ln();
		
		//$installments = Installment::where('member_number',$customers->member_number)->get();
		//dd($pendaptans);
		foreach($installments as $key => $installment)
		{			
			
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(20, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(40, 8, $installment->trans_number, 1, 0, 'L');
	        $pdf::Cell(40, 8, $installment->pay_date, 1, 0, 'L');
			$pdf::Cell(30, 8, $installment->pay_method, 1, 0, 'C');	
			if ($installment->pay_status == "FULL")
			{
				$pokok = $installment->pay_principal;
				$bunga = $installment->pay_interest;
				$pdf::Cell(50, 8, "Rp. ".number_format($pokok, 0, ',', '.').",-", 1, 0, 'R');
				$pdf::Cell(50, 8, "Rp. ".number_format($bunga, 0, ',', '.').",-", 1, 0, 'R');
			}elseif ($installment->pay_status == "FREE")
			{
				if ($installment->full_free == "pay_principal")
				{
					$pokok = $installment->amount;
					$pdf::Cell(50, 8, "Rp. ".number_format($pokok, 0, ',', '.').",-", 1, 0, 'R');
				} else {
					$pokok = 0;
					$pdf::Cell(50, 8, "Rp. ".number_format($pokok, 0, ',', '.').",-", 1, 0, 'R');
				}		
				if ($installment->full_free == "pay_rates")
				{
					$bunga = $installment->amount;
					$pdf::Cell(50, 8, "Rp. ".number_format($bunga, 0, ',', '.').",-", 1, 0, 'R');
				} else {
					$bunga = 0;
					$pdf::Cell(50, 8, "Rp. ".number_format($bunga, 0, ',', '.').",-", 1, 0, 'R');
				}
			}else{
				$pokok = 0;
				$bunga = 0;
				$pdf::Cell(50, 8, "Rp. ".number_format($pokok, 0, ',', '.').",-", 1, 0, 'R');
				$pdf::Cell(50, 8, "Rp. ".number_format($bunga, 0, ',', '.').",-", 1, 0, 'R');
			}
			
			$pdf::Cell(50, 8, "Rp. ".number_format($installment->amount, 0, ',', '.').",-", 1, 0, 'R');
	        $pdf::Ln();
			
			$loanAmount = $installment->loan_amount;
			$payPrincipal = $installment->pay_principal * 36 - $installment->amount;
			$payInterest = $installment->pay_interest * 36 - $installment->amount;
			$loanRemaining = $installment->loan_remaining;
    	}		
		
		$pdf::SetFont('Arial', 'B', 8);
		$pdf::MultiCell(40, 10, "PINJAMAN", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(90, 10, number_format($loanAmount) .  "RUPIAH", 1, 'R', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::Ln();
		$pdf::MultiCell(40, 10, "SISA POKOK", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(90, 10, number_format($payPrincipal) .  "RUPIAH", 1, 'R', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::Ln();
		$pdf::MultiCell(40, 10, "SISA BUNGA", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(90, 10, number_format($payInterest) .  "RUPIAH", 1, 'R', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::Ln();
		$pdf::MultiCell(40, 10, "SISA HUTANG", 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(90, 10, number_format($loanRemaining) .  "RUPIAH", 1, 'R', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::Ln();
		
		// Footer
        $pdf::SetY(179);
        $pdf::SetX(165);
        $pdf::SetFont('Arial','I',8);
        $pdf::Cell(0,10,"Dicetak Oleh Akuntan : ". $profileName ." Pada ".date("d-m-Y H:i:s")
        ." WIB", 0, 0, 'C');
		
		ob_end_clean();
		return $pdf::Output('laporan_angsuran.pdf','I');
	}
	
	public function capital_change()
	{
		$daftar_neraca = Journal::selectRaw("CONCAT(MONTH(transaction_date), '-', YEAR(transaction_date)) as waktu")->distinct()->get();        
        $total_neraca = $daftar_neraca->count();
		return view('report.capitalchange.rptCapitalChange',compact('daftar_neraca','total_neraca'));
		
	}
	
	public function print_capital_change($waktu)
	{  
		if(empty($waktu)) return redirect('report/profitloss');
		
		$pdf = new TPDF;
        
        $bulan = date('m', strtotime($waktu));
        $tahun = date('Y', strtotime($waktu));
        $periode = date('F Y', strtotime($waktu));
        $periode = strtoupper($periode);

        $items = AccountGroup::join('journals','account_group.id','=','journals.account_id')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->get();        

		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->company_id;
			}
		}
		
        $profiles = Company::where('company_id',$companyID)->get();
		
		foreach($profiles as $profile)
		{
			$profileName = $profile->name;
			$profileAddress = $profile->address;
			$provinsi = Provinsi::where('id',$profile->provinsi)->first();
			$kabupaten = Kabupaten::where('id',$profile->kabupaten)->first();
			$kecamatan = Kecamatan::where('id',$profile->kecamatan)->first();
			$kelurahan = Kelurahan::where('id',$profile->kelurahan)->first();
		}
        // $total_akun = Akun::all()->count();
        $id = AccountGroup::pluck('id');

        //$root = AccountGroup::where('id_parent', 0)->orderBy('account_number', 'asc')->get();

        //foreach ($root as $key) 
        //{
        //  $this->get_akun_child($waktu, $key->id);
        //}
        
        $total_saldo_debet_pendapatan = 0;
        $total_saldo_kredit_pendapatan = 0;
        $total_saldo_debet_beban = 0;
        $total_saldo_kredit_beban = 0;
        $total_saldo_debet_modal = 0; 
	    $total_saldo_kredit_modal = 0; 

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
        $pdf::Cell(0, 10, "".$kelurahan->nama." , ".$kecamatan->nama." , ".$kabupaten->nama." , ".$provinsi->nama, 'B', 1,  'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
		//$pdf::Cell(0, 8, .$kelurahan->nama, 0, 1, 'C');
		//{{$company->address}}, {{$kelurahan->nama}} {{$company->zip_code}} {{$kecamatan->nama}} {{$kabupaten->nama}} - {{$provinsi->nama}}
        $pdf::Ln();

        // Neraca Saldo
        $pdf::SetFont('Arial', 'B', 14);
        $pdf::Cell(0, 10, "PERUBAHAN MODAL $periode", 0, 2, 'C');

        $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(20, 8, "NO", 1, 0, 'C');
        $pdf::Cell(20, 8, "NO. AKUN", 1, 0, 'C');
        $pdf::Cell(100, 8, "NAMA AKUN", 1, 0, 'C');
        $pdf::Cell(60, 8, "DEBET", 1, 0, 'C');
        $pdf::Cell(60, 8, "KREDIT", 1, 0, 'C');
        $pdf::Ln();        
		
		//$pendaptans = AccountGroup::join('journals','account_group.id','=','journals.account_id')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->where('account_group.account_number',4)->orderBy('transaction_date', 'asc')->get();
		
		$modals = AccountGroup::where('account_number',3)->orderBy('account_number', 'asc')->get();
		//dd($pendaptans);
		foreach($modals as $key => $item)
		{
			$total_debet_modal = Journal::where('tipe', 'd')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');            
            $total_kredit_modal = Journal::where('tipe', 'k')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');                        
            //$parents = Journal::where('account_id',$item->id)->first();
        	//$this->get_akun_child($waktu, $item->id);
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(20, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(20, 8, $item->account_number, 1, 0, 'L');
	        $pdf::Cell(100, 8, $item->account_name, 1, 0, 'L');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_debet_modal, 0, ',', '.').",-", 1, 0, 'R');
	        $pdf::Cell(60, 8, "Rp. ".number_format($total_kredit_modal, 0, ',', '.').",-", 1, 0, 'R');
	        $pdf::Ln();
			
			$total_saldo_debet_modal += $total_debet_modal; 
	        $total_saldo_kredit_modal += $total_kredit_modal; 
    	}
		
		$pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(140, 8, "MODAL", 1, 0, 'C');
        $pdf::SetFont('Arial', '', 12);
        $pdf::Cell(60, 8, "Rp. ".number_format($total_saldo_debet_modal, 0, ',', '.').",-", 1, 0, 'R');
        $pdf::Cell(60, 8, "Rp. ".number_format($total_saldo_kredit_modal, 0, ',', '.').",-", 1, 0, 'R');
        $pdf::Ln();

        ///$pdf::SetFont('Arial', 'B', 10);
        //$pdf::Cell(260, 8, "TERBILANG :", 1, 0, 'L');
		//$pdf::Ln();
				
        //$pdf::SetFont('Arial', 'B', 8);
		//$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($total_saldo_debet_modal)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		//$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($total_saldo_kredit_modal)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        //$pdf::Ln();

        $pendaptans = AccountGroup::where('account_number',4)->orderBy('account_number', 'asc')->get();
		//dd($pendaptans);
		foreach($pendaptans as $key => $item)
		{
			$total_debet_pendapatan = Journal::where('tipe', 'd')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');            
            $total_kredit_pendapatan = Journal::where('tipe', 'k')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');                        
            //$parents = Journal::where('account_id',$item->id)->first();
        	//$this->get_akun_child($waktu, $item->id);        	
			
			$total_saldo_debet_pendapatan += $total_debet_pendapatan; 
	        $total_saldo_kredit_pendapatan += $total_kredit_pendapatan; 
    	}
		
        $bebans = AccountGroup::where('account_number',5)->orderBy('account_number', 'asc')->get();
		//dd($pendaptans);
		foreach($bebans as $key => $item)
		{
			$total_debet_beban = Journal::where('tipe', 'd')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');            
            $total_kredit_beban = Journal::where('tipe', 'k')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $item->id)->sum('nominal');        	
			
			$total_saldo_debet_beban += $total_debet_beban; 
	        $total_saldo_kredit_beban += $total_kredit_beban; 
    	}				
		
		$laba_debet = $total_saldo_debet_pendapatan - $total_saldo_debet_beban; 
	    $laba_kredit = $total_saldo_kredit_pendapatan - $total_saldo_kredit_beban; 

	    $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(140, 8, "LABA", 1, 0, 'C');
        $pdf::SetFont('Arial', '', 12);
        $pdf::Cell(60, 8, "Rp. ".number_format($laba_debet, 0, ',', '.').",-", 1, 0, 'R');
        $pdf::Cell(60, 8, "Rp. ".number_format($laba_kredit, 0, ',', '.').",-", 1, 0, 'R');
        $pdf::Ln();

        $grand_modal_debet = $total_saldo_debet_modal + $laba_debet; 
	    $grand_modal_kredit = $total_saldo_kredit_modal + $laba_kredit;

        //$pdf::SetFont('Arial', 'B', 10);
        //$pdf::Cell(260, 8, "TERBILANG :", 1, 0, 'L');
		//$pdf::Ln();
				
        //$pdf::SetFont('Arial', 'B', 8);
		//$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($laba_debet)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		//$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($laba_kredit)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        //$pdf::Ln();

        $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(140, 8, "TOTAL MODAL", 1, 0, 'C');
        $pdf::SetFont('Arial', '', 12);
        $pdf::Cell(60, 8, "Rp. ".number_format($grand_modal_debet, 0, ',', '.').",-", 1, 0, 'R');
        $pdf::Cell(60, 8, "Rp. ".number_format($grand_modal_kredit, 0, ',', '.').",-", 1, 0, 'R');
        $pdf::Ln();

        $pdf::SetFont('Arial', 'B', 10);
        $pdf::Cell(260, 8, "TERBILANG :", 1, 0, 'L');
		$pdf::Ln();
				
        $pdf::SetFont('Arial', 'B', 8);
		$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($grand_modal_debet)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, strtoupper(Terbilang::bilang($grand_modal_kredit)) . "RUPIAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();

        // Footer
        $pdf::SetY(179);
        $pdf::SetX(165);
        $pdf::SetFont('Arial','I',8);
        $pdf::Cell(0,10,"Dicetak Oleh Akuntan : ". $profileName ." Pada ".date("d-m-Y H:i:s")
        ." WIB", 0, 0, 'C');
		
		ob_end_clean();
		return $pdf::Output('laporan_perubahan_modal' .$periode. '.pdf','I');
	}

	public function view_history_transaction()
	{
		return view('report.transaction.history');
	}

	public function print_history_transaction($daterange)
	{
		$pdf = new TPDF;
		$date = explode('+', $daterange); //EXPLODE TANGGALNYA UNTUK MEMISAHKAN START & END		
		//DEFINISIKAN VARIABLENYA DENGAN FORMAT TIMESTAMPS
		$start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
		$end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
		
		$awal = Carbon::parse($date[0])->format('d-m-Y');
		$akhir = Carbon::parse($date[1])->format('d-m-Y');
		
		
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
			}
		}		
		
		//KEMUDIAN BUAT QUERY BERDASARKAN RANGE CREATED_AT YANG TELAH DITETAPKAN RANGENYA DARI $START KE $END
		if(Auth::user()->hasRole('superadmin','pengawas')) 
	    {
			//$query = "SELECT customer.*, loans.* FROM customer INNER JOIN loans ON loans.customer_id = customer.id';
			//$loans = DB::select($query);
			//$loans = Customer::join('loans', 'customer.id', '=', 'loans.customer_id')
			//->whereBetween('created_at', [$start, $end])
			//->get(['customer.id','customer.name','customer.member_number', 'loans.*']);
			//$customers = Customer::where('id',$request->customer)->first();
			//$installments = Installment::join('loans', 'installment.member_number', '=' ,'loans.member_number')					
            //         ->select('loans.loan_remaining','loans.loan_amount','loans.pay_principal','loans.pay_interest',
			//		 'loans.time_period','installment.*') 
			//		 ->whereBetween('installment.created_at', [$start, $end])->get();
			$installments = Installment::whereBetween('installment.pay_date', [$start, $end])->get();
		}else{
			//$loans = Customer::join('loans', 'customer.id', '=', 'loans.customer_id')
			//->where('loans.company_id',$companyID)
			//->whereBetween('created_at', [$start, $end])
			//->get(['customer.id','customer.name','customer.member_number', 'loans.*']);
			//$customers = Customer::where('id',$request->customer)->first();
			//$installments = Installment::join('loans', 'installment.member_number', '=' ,'loans.member_number')					
            //         ->select('loans.loan_remaining','loans.loan_amount','loans.pay_principal','loans.pay_interest',
			//		 'loans.time_period','installment.*') 
			//		 ->where('loans.company_id',$companyID)
			//		 ->whereBetween('installment.created_at', [$start, $end])->get();
			$installments = Installment::where('branch',$companyID)
			->whereBetween('installment.pay_date', [$start, $end])->get();
		}				

		$profiles = Company::where('company_id',$companyID)->get();
		
		//LOAD VIEW UNTUK PDFNYA DENGAN MENGIRIMKAN DATA DARI HASIL QUERY
		$pdf::AddPage('L', 'A4');
        //$pdf::AddPage();
        ob_start();
        // Header
        foreach($profiles as $profile)
		{
			$profileName = $profile->name;
			$profileAddress = $profile->address;
			$provinsi = Provinsi::where('id',$profile->provinsi)->first();
			$kabupaten = Kabupaten::where('id',$profile->kabupaten)->first();
			$kecamatan = Kecamatan::where('id',$profile->kecamatan)->first();
			$kelurahan = Kelurahan::where('id',$profile->kelurahan)->first();    
			$pdf::setJPEGQuality(90);
			$pdf::Image('img/logo/logo-small.png', 10, 10, 25, 0, 'PNG', '');
			$pdf::SetFont('Arial', 'B', 18);
	        $pdf::Cell(0, 10, $profileName, 0, 2, 'C');
	        $pdf::SetFont('Arial', 'B', 12);
			$pdf::Cell(0, 10, $profileAddress, 0, 1, 'C');
	        $pdf::Cell(0, 10, "".$kelurahan->nama." , ".$kecamatan->nama." , ".$kabupaten->nama." , ".$provinsi->nama, 'B', 1,  'C', 0, 0, '', '', true, 0, false, true, 10, 'M');		
			//$pdf::Cell(0, 8, .$kelurahan->nama, 0, 1, 'C');
			//{{$company->address}}, {{$kelurahan->nama}} {{$company->zip_code}} {{$kecamatan->nama}} {{$kabupaten->nama}} - {{$provinsi->nama}}
			$pdf::Ln();
		}        

        // ANGSURAN
        $pdf::SetFont('Arial', 'B', 14);
        $pdf::Cell(0, 10, "RIWAYAT TRANSAKSI", 0, 2, 'C');
		$pdf::Cell(0, 10, "PERIODE: ".$awal.' - '.$akhir."", 0, 2, 'C');
		$pdf::Ln();
		
		$pdf::SetFont('Arial', 'B', 8);
		$pdf::MultiCell(15, 24, "NO", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(25, 24, "NO PINJAMAN", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(35, 24, "NASABAH", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(20, 24, "NO TAB", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(20, 24, "CARA BAYAR", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(20, 24, "UANG MASUK", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(90, 8, "ANGSURAN", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(15, 24, "DENDA", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(15, 24, "TOTAL", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(15, 24, "SISA", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::Ln();
		$pdf::SetY(48);
        $pdf::SetX(145);
		$pdf::MultiCell(30, 8, "TEMPO", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(30, 8, "TABUNGAN", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(30, 8, "CICILAN", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        $pdf::Ln();
		$pdf::SetX(145);
		$pdf::MultiCell(15, 8, "BUNGA", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(15, 8, "POKOK", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(15, 8, "BUNGA", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(15, 8, "WAJIB", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(15, 8, "BUNGA", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(15, 8, "POKOK", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        $pdf::Ln();
		
		foreach($installments as $key => $installment)
		{
			$loans = Loan::where('member_number',$installment->member_number)->first();
			$getCutsId = $loans->customer_id;
			$customers = Customer::where('member_number',$installment->member_number)->first();
			$contract = CustomerContract::where('customer_id',$getCutsId)->first();
			$tempos = Tempo::where('member_number',$installment->member_number)->where('status','PAID')->first();
			$i = $key+1;
			if (empty($tempos)){;
				$bungaTempo = 0;
				$pokokTempo = 0;
				$totalTempo = 0;
			} else {
				$bungaTempo = $tempos->rate_count;
				$pokokTempo = $tempos->amount;
				$totalTempo = $bungaTempo + $pokokTempo;
			}
			
			$tabungan = str_replace('.', '', $contract->m_savings);
			$wajib = $tabungan ? $tabungan : 0;
			$total = $totalTempo + $loans->pay_month +$wajib;
			$sisa = $total - $installment->transfer_in;
			
			$pdf::SetFont('Arial', 'B', 8);
			$pdf::MultiCell(15, 8, $i, 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(25, 8, $loans->loan_number, 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(35, 8, $customers->name, 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(20, 8, $customers->member_number, 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(20, 8, $installment->pay_method, 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(20, 8, number_format($installment->transfer_in, 0, ',' , '.'), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(15, 8, number_format($bungaTempo, 0, ',' , '.'), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			//$pdf::MultiCell(15, 8, number_format($pokokTempo, 0, ',' , '.'), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(15, 8, number_format($installment->tempo, 0, ',' , '.'), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(15, 8, "", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(15, 8, number_format($installment->saving, 0, ',' , '.'), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(15, 8, number_format($installment->pay_rates, 0, ',' , '.'), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(15, 8, number_format($installment->pay_principal, 0, ',' , '.'), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(15, 8, number_format($installment->late_charge, 0, ',' , '.'), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			//$pdf::MultiCell(15, 8, number_format($total, 0, ',' , '.'), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			//$pdf::MultiCell(15, 8, number_format($sisa, 0, ',' , '.'), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(15, 8, number_format($installment->amount, 0, ',' , '.'), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::MultiCell(15, 8, number_format($installment->sisa, 0, ',' , '.'), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf::Ln();
		}
		
		ob_end_clean();
		
		return $pdf::Output('laporan_hsitory_transaksi'.$start.' - '.$end.'.pdf','I');

	}
	
}
