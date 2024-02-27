<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimulationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');;
    }

    public function simulation(Request $request)
    {	
		switch ($request->get('metode')) 
		{
			case 1:
				//$hasil = $this->metode_flat($request->jumlahKredit, $request->jangkaWaktu, $request->bungaPertahun);
				//response($hasil, $_metode);
				//$res['data'] = $data;
			    //$res['metode'] = $metode;
		//	    $results = json_encode($hasil);
				$hasil = 1;
				return view('simulation.simulation',compact('hasil'));
				break;
		//	default:
		//		response("Invalid request.");
		//		break;
							
		}
		return view('simulation.simulation');
	}
	
	public function store(Request $request)
	{		 
		switch ($request->get('hitung')) {
			case 1:
				$hasil = $this->metode_flat($_jumlahKredit, $_jangkaWaktu, $_bungaPertahun);
				response($hasil, $_metode);
				break;

			case 2:
				$hasil = $this->metode_efektif($_jumlahKredit, $_jangkaWaktu, $_bungaPertahun);
				response($hasil, $_metode);
				break;

			case 3:
				$hasil = $this->metode_anuitas($_jumlahKredit, $_jangkaWaktu, $_bungaPertahun);
				response($hasil, $_metode);
				break;

			default:
				response("Invalid request.");
				break;
		}
	}
	
	public function response($data, $metode=0)
	{
		$res['data'] = $data;
		$res['metode'] = $metode;
		$json_response = json_encode($res);
		echo $json_response;
	}
	
	public function metode_flat($jumlahPinjaman, $jangkaWaktu, $sukuBunga) 
	{
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
