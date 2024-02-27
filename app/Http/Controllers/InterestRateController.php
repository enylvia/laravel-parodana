<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InterestRate;
use App\Setting\Setting;
use DB;
use Validator;

class InterestRateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index()
	{
		DB::statement("SET @count = 0;");
        DB::statement("UPDATE interest_rate SET `id` = @count:= @count + 1;");
        DB::statement("ALTER TABLE `interest_rate` AUTO_INCREMENT = 1;");
		$rates = InterestRate::all();
		
		return view('interestrate.index',compact('rates'));
	}
	
	public function edit($id)
	{
		return view('interestrate.create');
	}
	
	public function store(Request $request)
	{
		// aturan Validasi //
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255', 
			'rate' => 'required|string|max:255',
        ]);

        // Cek apa ada yang salah  klo ada tampilkan pesan//
        if( $validation->fails() ){
         return redirect()->back()->withInput()
                          ->with('errors', $validation->errors() );
        } else {
			$rates = new InterestRate();
			$rates->name = $request->name;
			$rates->rate = $request->rate;
			$rates->save();
		}
		
		return redirect('/interest/rate')->with('success', 'Add successfully');		
		
	}
	
	public function update(Request $request, $id)
	{
		$rates = InterestRate::where('id',$id)->first();
		$rates->name = $request->name;
		$rates->rate = $request->rate;
		$rates->save();
		return redirect()->back()->with('success', 'Update successfully');
	}
	
	public function delete($id)
	{
		InterestRate::where('id',$id)->delete();
		
		return redirect()->back()->with('success', 'Delete successfully');
	}
	
	//define("HARI_BULAN", 30);
	//define("HARI_TAHUN", 360);
	//define("BULAN_TAHUN", 12);
	
	public function metode_flat($jumlahPinjaman, $jangkaWaktu, $sukuBunga) {
		$angsuran = [];
		$sukuBunga = $sukuBunga / 100;
		$pokok = $jumlahPinjaman / $jangkaWaktu;
		$bunga = $jumlahPinjaman * $sukuBunga / $jangkaWaktu;
		$sisaPinjaman = $jumlahPinjaman;
		$jumlahAngsuran = $pokok + $bunga;

		for($i = 0; $i < $jangkaWaktu; $i++) {
			$sisaPinjaman -= $pokok;
			array_push($angsuran, [
				"no"                => $i + 1,
				"pokok"             => round($pokok),
				"bunga"             => round($bunga),
				"jumlahAngsuran"    => round($jumlahAngsuran),
				"sisaPinjaman"      => round($sisaPinjaman)
			]);
		}
		return $angsuran;
	}


	public function metode_efektif($jumlahPinjaman, $jangkaWaktu, $sukuBunga) {
		$angsuran = [];
		$sukuBunga = $sukuBunga / 100;
		$sisaPinjaman = $jumlahPinjaman;
		$pokok = $jumlahPinjaman / $jangkaWaktu;
		
		for($i = 0; $i < $jangkaWaktu; $i++) {
			$bunga = $sisaPinjaman * $sukuBunga * (HARI_BULAN / HARI_TAHUN);
			$jumlahAngsuran = ( $pokok + $bunga );
			$sisaPinjaman -= $pokok;
			array_push($angsuran, [
				"no"                => $i + 1,
				"pokok"             => round($pokok),
				"bunga"             => round($bunga),
				"jumlahAngsuran"    => round($jumlahAngsuran),
				"sisaPinjaman"      => round($sisaPinjaman)
			]);
		}
		return $angsuran;
	}

	public function metode_anuitas($jumlahPinjaman, $jangkaWaktu, $sukuBunga) {
		$angsuran = [];
		$sukuBunga = $sukuBunga / 100;
		$jumlahAngsuran = Finance::pmt($sukuBunga, $jangkaWaktu, -$jumlahPinjaman);
		$sisaPinjaman = $jumlahPinjaman;

		for($i = 0; $i < $jangkaWaktu; $i++) {
			$pokok = Finance::ppmt(
				$sukuBunga,
				( $i + 1 ),
				$jangkaWaktu,
				-$jumlahPinjaman
			);
			$bunga = Finance::ipmt(
				$sukuBunga,
				( $i + 1 ),
				$jangkaWaktu,
				-$jumlahPinjaman
			);
			$sisaPinjaman -= $pokok;

			array_push($angsuran, [
				"no"                => $i + 1,
				"pokok"             => round($pokok),
				"bunga"             => round($bunga),
				"jumlahAngsuran"    => round($jumlahAngsuran),
				"sisaPinjaman"      => round($sisaPinjaman)
			]);
		}
		return $angsuran;
	}


	public function metode_floating($jumlahPinjaman, $jangkaWaktu, $sukuBunga, $sbFloating, $bulanFloating) {
		$angsuran = [];
		$sukuBunga = $sukuBunga / 100;
		$sisaPinjaman = $jumlahPinjaman;
		$pokok = $jumlahPinjaman / $jangkaWaktu;
		
		for($i = 0; $i < $jangkaWaktu; $i++) {
			// looping start from 0 so $bulanFloating should minus 1
			if ( $jangkaWaktu == ($bulanFloating - 1) ) {
				$bunga = $sisaPinjaman * $sbFloating * (HARI_BULAN / HARI_TAHUN);
			}
			$bunga = $sisaPinjaman * $sukuBunga * (HARI_BULAN / HARI_TAHUN);
			$jumlahAngsuran = ( $pokok + $bunga );
			$sisaPinjaman -= $pokok;
			array_push($angsuran, [
				"no"                => $i + 1,
				"pokok"             => round($pokok),
				"bunga"             => round($bunga),
				"jumlahAngsuran"    => round($jumlahAngsuran),
				"sisaPinjaman"      => round($sisaPinjaman)
			]);
		}
		return $angsuran;
	}
	
}
