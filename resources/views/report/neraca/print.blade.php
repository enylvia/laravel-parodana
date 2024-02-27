<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Laporan Neraca Saldo</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<!--style type="text/css">
			{
				font-family: Verdana, Arial, sans-serif;
			}
			table{
				font-size: x-small;
				border-spacing: 0;
			}
			tfoot tr td{
				font-weight: bold;
				font-size: x-small;	
				border: 1px solid #020202;				
			}
			.gray {
				background-color: lightgray
			}
			img {
			  display: block;
			  top: 0;
			  left: 0;
			  right: 0;
			  bottom: 0;
			  width: 80px;
			  height: 80px;
			  background: url('./img/logo/logo-med.png') no-repeat center;
			}
		</style-->
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
			footer .pagenum:before {
				  content: counter(page);
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
			<p align="center" style="font-size:24px">NERACA SALDO {{$periode}}</p>		
		
			<table width="100%">
				<thead>
					<tr>
						<th width="5%">No</th>
						<th align="left" width="45%">Akun</th>
						<th width="25%">Debet</th>
						<th width="25%">Kredit</th>
					</tr>
				</thead>
				<tbody>
					@foreach($id as $i => $account_number){
						<?php													
							$daftar_buku[$i] = App\Models\Journal::whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('company_id',$companyID)->where('account_id', $i)->get();			
							$total_debet[$i] = App\Models\Journal::where('tipe', 'd')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $i)->where('company_id',$companyID)->sum('nominal');            
							$total_kredit[$i] = App\Models\Journal::where('tipe', 'k')->whereMonth('transaction_date', $bulan)->whereYear('transaction_date', $tahun)->orderBy('transaction_date', 'asc')->where('account_id', $i)->where('company_id',$companyID)->sum('nominal');            
						
							$akun[$i] = App\Models\AccountGroup::findOrFail($i);
							
							if( substr($akun[$i]->account_number, 0, 1) === '1' ||  substr($akun[$i]->account_number, 0, 1) === '4'){
								$debet[$i] = $total_debet[$i] - $total_kredit[$i];
								$kredit[$i] = 0;
							}elseif( substr($akun[$i]->account_number, 0, 1) === '2' ||  substr($akun[$i]->account_number, 0, 1) === '3' || substr($akun[$i]->account_number, 0, 1) === '5'){
								$kredit[$i] = $total_kredit[$i] - $total_debet[$i];
								$debet[$i] = 0;
							}
														
							$data[$i] = [
								'account_name' => $akun[$i]->account_name,
								'debet' => $debet[$i],
								'kredit' => $kredit[$i],
							];
							
							$total_saldo_debet += $data[$i]['debet']; 
							$total_saldo_kredit += $data[$i]['kredit'];
						?>
					<tr>
					<p>
						<td>{{ $i }}</td>
						<td>{{ $data[$i]['account_name'] }}</td>
						<td align="right">Rp. {{ number_format($data[$i]['debet'], 0, ',', '.') }}</td>
						<td align="right">Rp. {{ number_format($data[$i]['kredit'], 0, ',', '.') }}</td>
					</P>
					</tr>					
					@endforeach
					<tr>
						<td colspan="4">Total</td>
						<td>Rp. {{ number_format($total_saldo_debet, 0, ',', '.') }}</td>
						<td>Rp. {{ number_format($total_saldo_kredit, 0, ',', '.') }}</td>
					</tr>
					<tr>
						<td colspan="4">Terbilang</td>
						<td>{{ strtoupper(App\Helper\Terbilang::bilang($total_saldo_debet)) }}</td>
						<td>{{ strtoupper(App\Helper\Terbilang::bilang($total_saldo_debet)) }}</td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
		</main>
		<footer>
			@foreach($companies as $company)
			<p>Dicetak Oleh Akuntan : {{$company->name}} Pada {{date("d-m-Y H:i:s")}} WIB"</p>
			@endforeach
			<div class="pagenum-container">Page <span class="pagenum"></span></div>
		</footer>
	</body>
</html>