<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Mutasi</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">		
		<style>
			@page {
				margin-top: 50px;
            }
			body {
                margin-top: 3cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 2cm;
            }
			header{				
				position: fixed; 
				top: -50px; 
				left: 0px; 
				right: 0px; 
				padding: 10px; 
				text-align: center; 
				font-weight: bold;
			}
			
			footer {
				position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;
			}

			main {
				flex: 1;
			}
			
			.tableMutasi {
			  border: 1px solid black;
			}

			.tableMutasi thead th {
			  border-top: 1px solid #000!important;
			  border-bottom: 1px solid #000!important;
			  border-left: 1px solid #000;
			  border-right: 1px solid #000;
			}

			.tableMutasi td {
			  border-left: 1px solid #000;
			  border-right: 1px solid #000;
			  border-top: none!important;
			}

		</style>
	</head>
	<body>
		<header>	
		@foreach($customers as $customer)
		<?php
			$companies = App\Models\Company::where('id',$customer->branch)->get();
		?>
		@endforeach
		
		<table width="100%">
			<thead>
			@foreach($companies as $company)
			<?php 
				$provinsi = App\Models\Provinsi::where('id',$company->provinsi)->first();
				$kabupaten = App\Models\Kabupaten::where('id',$company->kabupaten)->first();
				$kecamatan = App\Models\Kecamatan::where('id',$company->kecamatan)->first();
				$kelurahan = App\Models\Kelurahan::where('id',$company->kelurahan)->first();
			?>
				<tr>
					<th width="20%" class="text-center" rowspan="3">
						<img src="{{public_path('/img/logo/logo-med.png')}}" width="80px" height="80px">
					</th>
					<th width="80%" class="center"><strong style="font-size: 20px;">{{$company->name}}</strong></th>						
				</tr>
				<tr>
					<td width="80%" align="center">{{$company->siup}}</td>
				</tr>
				<tr>
					<td width="80%" align="center">{{$company->address}}, {{$kelurahan->nama}} {{$company->zip_code}} {{$kecamatan->nama}} {{$kabupaten->nama}} - {{$provinsi->nama}}</td>						
				</tr>				
			@endforeach
			</thead>
		</table>
		
		<hr>
		</header>
		<main>
			<table class="table table-responsive-sm table-striped">				
				<tbody>
					<tr>
						<th align="left">{{trans('installment.customer_number')}}</p></th>
						<td>:</td>
						<td>{{$nasabah->member_number}}</td>
					</tr>
					<tr>
						<th align="left">{{trans('installment.customer_name')}}</p></th>
						<td>:</td>
						<td>{{$nasabah->name}}</td>
					</tr>
					<tr>
						<th align="left">{{trans('general.address')}}</p></th>
						<td>:</td>
						<td>{{$nasabah->address}}</td>
					</tr>
					<tr>
						<th align="left">{{trans('loan.company_name')}}</p></th>
						<td>:</td>
						<td>{{$nasabah->company_name}}</td>
					</tr>
					<!--tr>
						<th align="left">Wajib</p></th>
						<td>:</td>
						<td>Rp. {{ number_format($wajib, 0, ',' , '.') }}</td>
					</tr>
					<tr>
						<th align="left">Pokok</p></th>
						<td>:</td>
						<td>Rp. {{ number_format($pokok, 0, ',' , '.') }}</td>
					</tr>
					<tr>
						<th align="left">Sukarela</p></th>
						<td>:</td>
						<td>Rp. {{ number_format($sukarela, 0, ',' , '.') }}</td>
					</tr>
					<tr>
						<th align="left">Total</p></th>
						<td>:</td>
						<td>Rp. {{ number_format($tabungan, 0, ',' , '.') }}</td>
					</tr-->
				</tbody>
			</table>
			
			<br>
			
			<table width="100%;">
			<tr>					
				<td width="20%"></td>
				<td width="50%"></td>
				<td width="30%">Dikeluarkan di</td>
			</tr>
			<tr>  					   
				<td width="20%"></td>
				<td width="50%"></td>
				<td width="30%">{{$kecamatan->nama}}, {{ date('d-m-Y', strtotime(now()))}}</td>
			</tr>
			<tr>
				<td height="60" width="30%" align="center">NASABAH</td>
				<td height="60" width="40%"></td>
				<td height="60" width="30%" align="center">KSP PARODANA M</td>
			</tr>
			<tr>
				<td height="80" width="30%" align="center">( {{$nasabah->name}} )</td>
				<td height="80" width="40%"></td>
				<td height="80" width="30%" align="center">(....................................)</td>
			</tr>
			</table>
			
			<br>
			
			<table class="table table-small tableMutasi" width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>{{trans('installment.date')}}</th>
						<th>{{trans('general.description')}}</th>
						<th>{{trans('installment.debet')}}</th>
						<th>{{trans('installment.credit')}}</th>
						<th>{{trans('installment.balance')}}</th>
						<th>{{trans('installment.rates')}}</th>
					</tr>
				</thead>
				<tbody id="tbody">				
				@foreach($saldo as $key => $dana)
				<?php 
					//saldoharian x suku bunga x hari bulan berjalan / 365
					$hariBulan = \Carbon\Carbon::parse($dana->tr_date)->daysInMonth;
					$awal = date_create($dana->tr_date);
					$sukubunga = (\setting('bunga_tabungan') / 12) /100;										
					$start = Carbon\Carbon::parse($dana->tr_date)->startOfMonth();
					$end = Carbon\Carbon::parse($dana->tr_date)->endOfMonth();
					$akhir = date_create($end);
					$jumlah = date_diff($awal,$akhir);
					$hari = $jumlah->format("%a");
										
					//$dates = [];
					//while ($start->lte($end)) {
					//	$carbon = strtotime($start);
					//	if ($carbon->isWeekend() !=true) { 
					//		$dates[] = $start->copy()->format('Y-m-d');
					//	}
					//	$start->addDay();
					//}

					//foreach ($dates as $key => $dateval) {
					//	$test = $dateval;
						//$days = $start + $end;
					//}
					$test = min($start,$end)->format('Y-m-d');
					if ($dana->tr_date <= $dt)
					{
						//$jumlahHari = $hari;
						$bunga = round($dana->saldo * $sukubunga * $hari / 365);
					}else{
						//$jumlahHari = $hariBulan;
						$bunga = round($dana->saldo * $sukubunga * $hariBulan / 365);
					}
					
				?>
					<tr style="border-bottom:none;">
						<td align="center">{{$key+1}}</td>
						<td align="center">							
							{{ date('d-m-Y', strtotime($dana->tr_date))}}
						</td>
						<td align="center">
							{{$dana->tipe}}
						</td>
						<td align="right">
								Rp. {{ number_format($dana->kredit, 0, ',' , '.') }}
						</td>
						<td align="right">
								Rp. {{ number_format($dana->debet, 0, ',' , '.') }}
						</td>
						<td align="right">						
						
							Rp. {{ number_format($dana->saldo, 0, ',' , '.') }}
						
						</td>
						<td align="right" style="border-bottom:none;">Rp. {{ number_format($bunga, 0, ',' , '.') }}</td>
					</tr >
				@endforeach
				
				</tbody>
			</table>
		</main>
		<footer>
		</footer>
	</body>
</html>