<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerContract;
use App\Models\User;
use App\Models\BalanceAccount;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\TransactionType;
use App\Models\Purchase;
use App\Helper\Terbilang;
use Carbon\Carbon;
use Validator;
use DB;
use DPDF;
use TPDF;
use Notification;
use Auth;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$users = User::with('companies')->where('id',auth()->user()->id)->get();
			foreach($users as $user)
			{
				foreach($user->companies as $company)
				{
					$companyID = $company->id;
				}
			}
		
			if(Auth::user()->hasRole('superadmin','pengawas')) 
		    {
				$purchases = Purchase::all();
			}else{
				$purchases = Purchase::where('branch',$companyID)
                     ->get();
			}
            return datatables()->of($purchases)
				->editColumn('trans_date', function ($user) 
				{
					return date('d-m-Y', strtotime($user->trans_date) );
				})
				->editColumn('btnEdit', function ($row) {   
					if($row->journal == 1) {
						return '<a href="/transaction/purchase/edit/'.$row->trans_code.'" class="btn btn-xs btn-info" target="_blank" style="display:none;"><i class="fa fa-edit" title="Edit"></i></a>';
					}elseif($row->journal == 0){
						return '<a href="/transaction/purchase/edit/'.$row->trans_code.'" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-edit" title="Edit"></i></a>';
					}
                })
				->editColumn('btnDelete', function ($row) {                    
                    //return '<a href="/transaction/purchase/delete/'.$row->trans_code.'" class="btn btn-xs btn-danger" target="_blank"><i class="fa fa-trash-o" title="Delete"></i></a>';
					//return '<a id="#" data-target="#Delete-'.$row->id.'" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" title="delete"></i></a>';
					$delete = trans('general.delete');
					if($row->journal == 1) {
						return '<a id="#" data-target="#Delete-'.$row->id.'" data-toggle="modal" class="btn btn-xs btn-danger" style="display:none;"><i class="fa fa-trash" title="'.$delete.'"></i></a>';
					}elseif($row->journal == 0){
						return '<a id="#" data-target="#Delete-'.$row->id.'" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" title="'.$delete.'"></i></a>';
					}
                })
                ->editColumn('btnPrint', function ($row) {
					$print = trans('general.print');
                    return '<a href="/transaction/purchase/print/'.$row->trans_code.'" class="btn btn-xs btn-default" target="_blank"><i class="fa fa-print" title="'.$print.'"></i></a>';					
                })
				->rawColumns(['btnEdit','btnDelete','btnPrint'])
				->make(true);
        }
		
		return view('purchase.index');
	}
	
	public function create()
	{
		$types = TransactionType::all();
		return view('purchase.create',compact('types'));
	}
	
	public function store(Request $request)
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
				$companyCode = $company->company_id;
			}
		}
		$validation = Validator::make($request->all(), [            
			'quantity' => 'required',
			'price' => 'required',
        ]);			
				
        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			// cek kode
			//$query = mysqli_query($conn, "SELECT max(right(kode_transaksi, 4)) AS kode FROM tb_transaksi WHERE DATE(tanggal) = CURDATE()");
			$query = \DB::table('purchase')->whereDate('trans_date', $request->trans_date)
				->where('branch',$companyID)
				->select(\DB::raw('SUBSTRING(trans_code, 4, 4) as kode'))
				->get();			
			//dd($query);
			if ($query->count() > 0) {
				foreach ($query as $q) {
					//$no = ((int)$q['kode'])+1;
					$no = $q->kode+1;
					$kd = sprintf("%04s", $no);
				}
			} else {
				$kd = "0001";
			}
			
			$pay = "PCS";
			//$stockAwal = Purchase::where('')->first;
			$code = $companyCode;
			$date = date("Y-m-d");
			$tahun = substr($date, 0, 4);
			$bulan = substr($date, 5, 2);
			$hari = substr($date, 8, 2);
			$unik = $this->kodeUnik(10);
			
			$transCode = $pay .$kd .$bulan .$tahun .$code;
			
			$purchase = new Purchase();
			$purchase->trans_code = $transCode;
			$purchase->trans_date = $request->trans_date;
			$purchase->to = $request->to_customer;
			$purchase->trans_type = $request->transaction_type;
			$purchase->branch = $companyID;
			$purchase->description = $request->description;
			$purchase->unit = $request->unit;
			$purchase->qty = $request->quantity;				
			if (!empty($request->input('price'))){
				$purchase->amount = str_replace('.', '', $request->price);
			} else {
				$purchase->amount = 0;
			}
			$totPrice = str_replace('.', '', $request->price);
			$purchase->total = $totPrice * $request->quantity;
			$purchase->created_by = auth()->user()->id;
			$purchase->save();
			
			Purchase::where('trans_type', '=', $request->transaction_type)->update(['stock' => $request->quantity]);
			
			return redirect()->back()->with('success', 'Add successfully');
		}
	}
	
	public  function kodeUnik($length) 
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomOrder = '';
        for ($i = 0; $i < $length; $i++) {
            $randomOrder .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomOrder;
    }
	
	public function edit($id)
	{
		$purchases = Purchase::where('trans_code',$id)->get();
		//dd($purchases);
		//$types = TransactionType::all();
		return view('purchase.edit',compact('purchases'));
	}
	
	public function update(Request $request, $id)
	{
		$users = User::with('companies')->where('id',auth()->user()->id)->get();
		foreach($users as $user)
		{
			foreach($user->companies as $company)
			{
				$companyID = $company->id;
				$companyCode = $company->company_id;
			}
		}
		$validation = Validator::make($request->all(), [            
			'quantity' => 'required',
			'price' => 'required',
        ]);			
				
        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			
			//$stockAwal = Purchase::where('')->first;
			$code = $companyCode;
			$date = date("Y-m-d");
			$tahun = substr($date, 2, 2);
			$bulan = substr($date, 5, 2);
			$hari = substr($date, 8, 2);
			$unik = $this->kodeUnik(10);
			$transCode = $code .$unik .$bulan .$tahun;
			
			$purchase = Purchase::where('trans_code',$id)->first();
			//$purchase->trans_code = $transCode;
			$purchase->trans_date = $request->trans_date;
			$purchase->to = $request->to_customer;
			$purchase->trans_type = $request->transaction_type;
			$purchase->branch = $companyID;
			$purchase->description = $request->description;
			$purchase->unit = $request->unit;
			$purchase->qty = $request->quantity;				
			if (!empty($request->input('price'))){
				$purchase->amount = str_replace('.', '', $request->price);
			} else {
				$purchase->amount = 0;
			}
			$totPrice = str_replace('.', '', $request->price);
			$purchase->total = $totPrice * $request->quantity;
			$purchase->created_by = auth()->user()->id;
			$purchase->save();
			
			Purchase::where('trans_type', '=', $request->transaction_type)->update(['stock' => $request->quantity]);
			
			return redirect('/transaction/purchase')->with('success', 'Update successfully');
		}
	}
	
	public function delete($id)
    {
      Purchase::where('trans_code',$id)->delete();
      return redirect()->back()->with("success","Berhasil di hapus !");
    }
	
	public function print($id)
    {
		//if(empty($id)) return redirect('customer/balance');
		
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
		
        $pdf::Ln();

        // Neraca Saldo
        $pdf::SetFont('Arial', 'B', 14);
        $pdf::Cell(0, 10, "KWITANSI PEMBELIAN", 0, 2, 'C');
		$pdf::Ln();
		
		$purchase = Purchase::where('trans_code',$id)->first();
		
		$pdf::SetFont('Arial', 'B', 12);
		$pdf::MultiCell(40, 10, "Tanggal", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, date('d-m-Y', strtotime($purchase->trans_date)) , 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::Ln();
		$pdf::MultiCell(40, 10, "Kepada", 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(10, 10, ":", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		$pdf::MultiCell(130, 10, $purchase->to, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');		
        $pdf::Ln();

        $pdf::SetFont('Arial', 'B', 12);
        $pdf::Cell(10, 8, "NO", 1, 0, 'C');
        $pdf::Cell(45, 8, "NO. TRANSAKSI", 1, 0, 'C');
        $pdf::Cell(100, 8, "KETERANGAN", 1, 0, 'C');
        $pdf::Cell(15, 8, "QTY", 1, 0, 'C');
		$pdf::Cell(20, 8, "SATUAN", 1, 0, 'C');
		$pdf::Cell(40, 8, "HARGA", 1, 0, 'C');
		$pdf::Cell(40, 8, "TOTAL", 1, 0, 'C');
        $pdf::Ln();        
		
		$purchases = Purchase::where('trans_code',$id)->orderBy('id', 'asc')->get();
		//dd($pendaptans);
		foreach($purchases as $key => $item)
		{			
        	$pdf::SetFont('Arial', '', 12);
	        $pdf::Cell(10, 8, $key+1, 1, 0, 'C');
	        $pdf::Cell(45, 8, $item->trans_code, 1, 0, 'C');
	        //$pdf::Cell(30, 8, date('d-m-Y', strtotime($item->trans_date)), 1, 0, 'C');
	        $pdf::Cell(100, 8, $item->description, 1, 0, 'L');
			$pdf::Cell(15, 8, $item->qty, 1, 0, 'L');
			$pdf::Cell(20, 8, $item->unit, 1, 0, 'L');
	        $pdf::Cell(40, 8, "Rp. ".number_format($item->amount, 0, ',', '.').",-", 1, 0, 'R');
			$pdf::Cell(40, 8, "Rp. ".number_format($item->total, 0, ',', '.').",-", 1, 0, 'R');
	        $pdf::Ln();    			
			
			$pdf::SetFont('Arial', 'B', 10);
			$pdf::Cell(270, 8, "Rp. ".number_format($item->total, 0, ',', '.').",-", 1, 0, 'R');
			$pdf::Ln();
			
			$pdf::SetFont('Arial', 'B', 10);
			$pdf::Cell(270, 8, "TERBILANG :", 1, 0, 'L');
			$pdf::Ln();
			
			$pdf::SetFont('Arial', 'B', 8);
			$pdf::Cell(270, 10, strtoupper(Terbilang::bilang($item->total)) . "RUPIAH", 1, 0, 'R');
			$pdf::Ln();
		}
		
        // Footer
        $pdf::SetY(179);
        $pdf::SetX(165);
        $pdf::SetFont('Arial','I',8);
        $pdf::Cell(0,10,"Dicetak Oleh Akuntan : ". $profileName ." Pada ".date("d-m-Y H:i:s")
        ." WIB", 0, 0, 'C');
		
		ob_end_clean();
		return $pdf::Output('pembelian.pdf','I');
	}
	
}
