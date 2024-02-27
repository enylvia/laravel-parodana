<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Serah Terima Berkas | STB</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
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
				bottom: 0px;
			}

			main {
				flex: 1;
			}
			.page_break { page-break-after: always; }
			.fa {
				display: inline;
				font-style: normal;
				font-variant: normal;
				font-weight: normal;
				font-size: 14px;
				line-height: 1;
				font-family: FontAwesome;
				font-size: inherit;
				text-rendering: auto;
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
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
				
				$customer_provinsi = App\Models\Provinsi::where('id',$customer->provinsi)->first();
				$customer_kabupaten = App\Models\Kabupaten::where('id',$customer->kabupaten)->first();
				$customer_kecamatan = App\Models\Kecamatan::where('id',$customer->kecamatan)->first();
				$customer_kelurahan = App\Models\Kelurahan::where('id',$customer->kelurahan)->first();
			?>
				<tr>
					<th width="20%" class="text-center" rowspan="3">
						<img src="{{public_path('/img/logo/logo-med.png')}}" width="80px" height="80px">
					</th>
					<th width="80%" class="center"><strong style="font-size: 20px;">{{$company->name}}</strong></th>						
				</tr>
				<tr>
					<td width="80%" align="center" style="font-size: 14px;">{{$company->siup}}</td>
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
		<p align="center" style = "font-size:24px">SERAH TERIMA BERKAS</p>
		@foreach($customers as $customer)
			<?php 
				$handovers = App\Models\DocumentHandover::where('reg_number',$customer->reg_number)->get();
			?>
			<table>
				<tbody>
					<tr>
						<th align="left">Nama</th>
						<td>:</td>
						<td>{{$customer->name}}</td>
					</tr>
					<tr>
						<th align="left">No. KTP</th>
						<td>:</td>
						<td>{{$customer->card_number}}</td>
					</tr>
					<tr>
						<th align="left">Tempat, Tgl/Lahir</th>
						<td>:</td>
						<td>{{$customer->birth_place}}, {{ date('d-m-Y', strtotime($customer->date_of_birth))}}</td>
					</tr>
					<tr>
						<th align="left">Alamat Lengkap</th>
						<td>:</td>
						<td>{{$customer->address}}</td>
					</tr>
					<tr>
						<th align="left">No. Telpon</th>
						<td>:</td>
						<td>{{$customer->mobile_phone}}</td>
					</tr>
					
				</tbody>
			</table>
			<br/>
			<table border="1" cellpadding="4" cellspacing="4">			
			<tr>
				<th rowspan="2"> NO. </th>
				<th rowspan="2" width="200"> Berkas </th>
				<th colspan="2" width="60"> Status </th>
				<th rowspan="2" width="110"> Keterangan </th>			
			</tr>
			<tr>
				<th>Asli</th>
				<th>Copy</th>
			</tr>	
			@foreach($handovers as $key => $handover)
			<tr>
				<td align="center">{{$key+1}}</td>		
				<td width="200">{{$handover->berkas}}</td>
				<td align="center" width="25">
					@if($handover->status=='asli')
						<div style="font-family: ZapfDingbats, sans-serif;">4</div>
					@endif
				</td>
				<td align="center" width="25">
					@if($handover->status=='copy')
						<div style="font-family: ZapfDingbats, sans-serif;">4</div>
					@endif
				</td>
				<td width="110">{{$handover->keterangan}}</td>
			</tr>
			@endforeach
			</table>
			<br/>
			<table border="1">	
				<thead>
					<tr>
						<th colspan="6">SERAH TERIMA BERKAS</th>
					</tr>
					<tr>
						<th colspan="2" width="120"> ANGGOTA </th>
						<th colspan="2" width="120"> ADMIN </th>
						<th colspan="2" width="120"> PIMPINAN </th>			
					</tr>	
				</thead>
				<tbody>
					<tr>		
						<td colspan="2" rowspan="10" width="150">  </td>
						<td colspan="2" rowspan="10" width="150">  </td>
						<td colspan="2" rowspan="10" width="150">  </td>	
					</tr>
					<tr>
					</tr>
					<tr>
					</tr>
					<tr>
					</tr>
					<tr>
					</tr>
					<tr>
					</tr>
					<tr>
					</tr>
					<tr>
					</tr>
					<tr>
					</tr>
					<tr>
					</tr>
				</tbody>
				<tfoot>					
					<tr>
						<th colspan="2" width="150"> {{$customer->name}} </th>
						<th colspan="2" width="150">  </th>
						<th colspan="2" width="150">  </th>			
					</tr>
				</tfoot>
			</table>
		@endforeach
		</main>
	</body>
</html>