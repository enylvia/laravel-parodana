<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Pencairan Dana | PD</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
		<style>
			@page {
                margin: 0cm 0cm;
            }
			body {
                margin-top: 3cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 2cm;
            }
			header{
				position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
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
		<p align="center" style = "font-size:24px">KUITANSI PENCAIRAN DANA</p>
		@foreach($customers as $customer)
			<?php 
				$handovers = App\Models\DocumentHandover::where('reg_number',$customer->reg_number)->first();
				$approves = App\Models\CustomerApprove::where('customer_id',$customer->id)->first();
				$contracts = App\Models\CustomerContract::where('customer_id',$customer->id)->first();
			?>			
			<br/>
				<table width="100%" style="border: 0px solid #000;">  
					<tr>  					   
						<td> No </td>  
						<td align="center"> :  </td>
						<td> {{$transNumber}} </td>					   
					</tr>  
					<tr>  
						<td> Nasabah </td>  
						<td align="center"> :  </td>
						<td> {{$customer->name}} </td>
					</tr> 
					<tr>  
						<td> Pinjaman </td>  
						<td align="center"> :  </td>
						<td align="right" > Rp. {{ number_format($approves->approve_amount, 0, ',' , '.') }} </td>
					</tr>
					<tr>  
						<td> Provisi </td>  
						<td align="center"> :   </td>
						<td align="right" > Rp. {{ number_format($provisi, 0, ',' , '.') }} </td>
					</tr>
					<tr>  
						<td> Materai </td>  
						<td align="center"> :   </td>
						<td align="right" > Rp. {{ number_format($materai, 0, ',' , '.') }} </td>
					</tr>
					<tr>  
						<td> Asuransi </td>  
						<td align="center"> :   </td>
						<td align="right"> Rp. {{ number_format($asuransi, 0, ',' , '.') }} </td>
					</tr>
					<tr>  
						<td> Terima Bersih </td>  
						<td align="center"> :  </td>
						<td align="right" > Rp. {{ number_format($jumlah, 0, ',' , '.') }} </td>
					</tr>
					<tr>
						<td colspan="3" style="font-style:italic;" align="right"> Terbilang : {{ ucwords(App\Helper\Terbilang::bilang($jumlah)) }} Rupiah </td>
					</tr>
					<tr>  					   
						<td height="50" colspan="3" align="right"> {{ $kecamatan->nama }}, {{ date('d-m-Y', strtotime($transDate))}} </td>  
					</tr>
					<tr>
						<td height="100" align="center">Materai</td>
						<td height="100"></td>
						<td height="100" align="center"></td>
					</tr>
					<tr>
						<td height="100" align="center">( {{$customer->name}} )</td>
						<td height="100"></td>
						<td height="100" align="center">(....................................)</td>
					</tr>
				 </table>  			
		@endforeach
		</main>
	</body>
</html>