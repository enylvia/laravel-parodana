<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Pernyataan Penaggung Jawab</title>
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
			foreach($companies as $company)
			{
				$companyName = $company->name;
			}
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
			<p align="center" style = "font-size:20px">SURAT PERNYATAAN PENANGGUNG JAWAB</p>				
			
				@foreach($customers as $customer)
					<p align="justify" style = "font-size:14px">Pada hari:____________ Tanggal____ Bulan___________ Tahun ______
					Bertempat di Yang Bertanda tangan dibawah ini: </p>
					<table>
						<tbody>
							<tr>
								<th align="left" style = "font-size:14px">Nama</th>
								<td>:</td>
								<td></td>
							</tr>
							<tr>
								<th align="left" style = "font-size:14px">No. KTP</th>
								<td>:</td>
								<td></td>
							</tr>
							<tr>
								<th align="left" style = "font-size:14px">Tempat, Tgl/Lahir</th>
								<td>:</td>
								<td></td>
							</tr>
							<tr>
								<th align="left" style = "font-size:14px">Alamat Lengkap</th>
								<td>:</td>
								<td></td>
							</tr>
							<tr>
								<th align="left" style = "font-size:14px">No. Telpon</th>
								<td>:</td>
								<td></td>
							</tr>
							
						</tbody>						
					</table>
					<p align="justify" style = "font-size:14px">Hubungan dengan pemohon kredit:	
					Bertindak atas nama diri sendiri menyatakan sebagai penanggungjawab atas pengajuan pinjaman kepada {{$companyName}} yang diajukan oleh pemohon:</p>
					
					<table>
						<tbody>
							<tr>
								<th align="left" style = "font-size:14px">Nama</th>
								<td>:</td>
								<td style = "font-size:14px">{{$customer->name}}</td>
							</tr>
							<tr>
								<th align="left" style = "font-size:14px">No. KTP</th>
								<td>:</td>
								<td style = "font-size:14px">{{$customer->card_number}}</td>
							</tr>
							<tr>
								<th align="left" style = "font-size:14px">Tempat, Tgl/Lahir</th>
								<td>:</td>
								<td style = "font-size:14px">{{$customer->birth_place}}, {{ date('d-m-Y', strtotime($customer->date_of_birth))}}</td>
							</tr>
							<tr>
								<th align="left" style = "font-size:14px">Alamat Lengkap</th>
								<td>:</td>
								<td style = "font-size:14px">{{$customer->address}}</td>
							</tr>
							<tr>
								<th align="left" style = "font-size:14px">No. Telpon</th>
								<td>:</td>
								<td style = "font-size:14px">{{$customer->mobile_phone}}</td>
							</tr>
							
						</tbody>
					</table>
					
					<p align="justify" style = "font-size:14px">Hubungan dengan penanggungjawab:	
					Dengan besar pinjaman Rp.	
					Jangka waktu pembayaran	 Bulan
					Besar angsuran Rp	/Bulan</p>
					<p align="justify" style = "font-size:14px">Apabila dikemudian hari sipemohon kredit terjadi kelalaian atas pembayaran angsuran kepada pihak KOPERASI {{$companyName}} dengan alasan yang disengaja atau tidak disengaja maka tanpa diperlukan surat pemberitahuan atau surat peringatan maka saya sebagai penanggungjawab bersedia atau wajib melunasi sisa utang sipemohon baik secara berkala maupun pembayaran penuh.
					Apabila terjadi perselisihan dari perjanjian akan diselesaikan dengan jalan musyawarah dan apabila tidak ada kesepakatan antara kedua belah pihak, maka masing-masing pihak berkewajiban menyelesaikan di Kantor Pengadilan Negeri Serang-Banten.
						Demikian surat pernyataan ini saya perbuat dalam keadaan sehat jasmani dan rohani dan tanpa ada unsur paksaan dari pihak manapun.</p>					
					<table>
						<tr>  					   
							<td width="300" align="right"> {{ $kecamatan->nama }}, {{ date('d-m-Y', strtotime(now()))}} </td>  
						</tr>
						<tr>
							<td height="50" width="30" align="center">KARYAWAN</td>
							<td height="50" width="30"></td>
							<td height="50" width="30" align="center">PENANGGUNG JAWAB</td>
						</tr>
						<tr>
							<td height="60" width="30" align="center">( {{$customer->name}} )</td>
							<td height="60" width="30"></td>
							<td height="60" width="30" align="center">(....................................)</td>
						</tr>
					</table>
				@endforeach
			
		</main>
		<footer>
		</footer>
	</body>
</html>