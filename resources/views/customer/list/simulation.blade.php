<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Simulasi Kredit</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">		
		
		<style>
			@page {
				margin-top: 30px;
            }
			body {
                margin-top: 3cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 2cm;
            }
			header{				
				position: fixed; 
				top: -20px; 
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
					<th width="80%" class="center"><strong style="font-size: 25px;">{{$company->name}}</strong></th>						
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
			<p align="center" style = "font-size:24px">SIMULASI KREDIT</p>				
					
			<table class="table">
				<tbody>
				@foreach($customers as $customer)
				<?php
					$contracts = App\Models\CustomerContract::where('customer_id',$customer->id)->get();
					$approves = App\Models\CustomerApprove::where('customer_id',$customer->id)->first();
					$rates = App\Models\InterestRate::where('id','=',3)->first();
					$plafon = $customer->loan_amount;
					$tempo = $customer->time_period;
					$bunga_pertahun = $rates->rate;
					$no=1;
					$bunga_perbulan=$bunga_pertahun/12;
					$bunga_rp = $plafon/$bunga_pertahun;
					$angsuran_bunga=$plafon*$bunga_perbulan/100;
					$angsuran_pokok = $plafon/$tempo;
					$total_angsuran = $angsuran_pokok+$angsuran_bunga;
				?>
					<tr>
						<th align="left">{{trans('general.name')}}</th>
						<td>:</td>
						<td>{{$customer->name}}</td>
					</tr>
					<tr>
						<th align="left">{{trans('loan.loan_amount')}}</th>
						<td>:</td>
						<td>Rp. {{ number_format($customer->loan_amount, 0, ',' , '.') }}</td>
					</tr>
					<tr>
						<th align="left">{{trans('loan.time_period')}}</th>
						<td>:</td>
						<td>{{$customer->time_period}} Bulan</td>
					</tr>
					<tr>
						<th align="left">{{trans('loan.interest_rate')}}</th>
						<td>:</td>
						<td>{{$rates->rate}} %</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			<table class="table" border="1" width="100%">	
				<thead>
				<tr>
					<th>Angsuran ke -</th>
					<th>Angsuran Pokok</th>
					<th>Angsuran Bunga</th>
					<th>Total Angsuran</th>
					<th>Sisa Pokok</th>
				</tr>
				</thead>
				<tbody>																
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>Rp. {{ number_format($plafon, 0, ',' , '.') }}</td>
					</tr>
					@for($i = 1; $i <= $tempo; $i++)
						<tr>
							<?php 
							$sisa_pokok = $plafon - ($angsuran_pokok * $i);
							?>
							<td align="center">{{$no++}}</td>
							<td align="right">Rp. {{ number_format($angsuran_pokok, 0, ',' , '.') }}</td>
							<td align="right">Rp. {{ number_format($angsuran_bunga, 0, ',' , '.') }}</td>
							<td align="right">Rp. {{ number_format($total_angsuran, 0, ',' , '.') }}</td>
							<td align="right">Rp. {{ number_format($sisa_pokok, 0, ',' , '.') }}</td>
						</tr>
					@endfor	
				</tbody>
			</table>					
		</main>
		<footer>
		</footer>
	</body>
</html>