<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Neraca Saldo</title>
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
		</style>
	</head>
	<body>
		<header>			
		
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
			<table class="table table-striped text-center">
				<tr>
					<th class="text-center" >No</th>
					<th class="text-center" >Akun</th>
					<th class="text-center" >Saldo Awal</th>
					<th class="text-center" >Debet</th>
					<th class="text-center" >Kredit</th>
					<th class="text-center" >Saldo Akhir</th>
				</tr>
				<?php $i = 1 ?>				
				@foreach($data as $item)				
				<tr>
					<td class="text-center">{{ $i++ }}</td>
					<td align="left">{{ $item['account_name'] }}</td>
					<td align="right">
						Rp. {{ number_format($item['beginning_balance'], 0, ',', '.') }},-
					</td>
					<td align="right">
					  Rp. {{ number_format($item['debet'], 0, ',', '.') }},-
					</td>
					<td align="right">
					  Rp. {{ number_format($item['kredit'], 0, ',', '.') }},-
					</td>
					<td align="right">
						Rp. {{ number_format($item['ending_balance'], 0, ',', '.') }},-
					</td>
				</tr>
				@endforeach
				<tr>
					<th colspan="4" class="text-center">Total</th>
					<th class="text-center">Rp. {{ number_format($total_saldo_debet, 0, ',', '.') }},-</th>
					<th class="text-center">Rp. {{ number_format($total_saldo_kredit, 0, ',', '.') }},-</th>
				</tr>

				<tr>
					<th colspan="4" class="text-center">TERBILANG</th>
					<th class="text-center"> <em> {{ ucwords(App\Helper\Terbilang::bilang($total_saldo_debet)) }} Rupiah</em> </th>
					<th class="text-center"> <em> {{ ucwords(App\Helper\Terbilang::bilang($total_saldo_kredit)) }} Rupiah</em></th>
				</tr>

			</table>
		</main>
		
	</body>
</html>