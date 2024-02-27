<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Kontrak</title>
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
			<p align="center" style = "font-size:24px">SURAT PERJANJIAN</p>
		
		@foreach($customers as $customer)
		<?php
			$contracts = App\Models\CustomerContract::where('customer_id',$customer->id)->get();
			$approves = App\Models\CustomerApprove::where('customer_id',$customer->id)->first();
		?>
		@foreach($contracts as $contract)
		<?php 
			$asuransi = $approves->approve_amount * $contract->insurance / 100;
			$provisi = $approves->approve_amount * $contract->provision / 100;
		?>
		<p align="justify">
			Pada hari ini, {{$contract->c_day}}. tanggal, {{$contract->c_date}} bulan, {{$contract->c_month}} tahun, {{$contract->c_year}}. bertempat di Koperasi Simpan Pinjam Parodana M, kami yang bertanda tangan di bawah ini: </p>			
			<table class="table">
				<tbody>
					<tr>
						<th align="left">Nama</p></th>
						<td>:</td>
						<td>{{$customer->name}}</td>
					</tr>
					<tr>
						<th align="left">Alamat</th>
						<td>:</td>
						<td>{{$customer->address}}</td>
					</tr>
					<tr>
						<th align="left">NIK</th>
						<td>:</td>
						<td>{{$customer->card_number}}</td>
					</tr>
					<tr>
						<th align="left">PT</th>
						<td>:</td>
						<td>{{$customer->company_name}}</td>
					</tr>
					<tr>
						<th align="left">Telpon</th>
						<td>:</td>
						<td>{{$customer->mobile_phone}}</td>
					</tr>
				</tbody>
			</table>
			<p align="justify">Untuk selanjutnya disebut PIHAK PERTAMA.</p>
			<table class="table">
				<tbody>
					@foreach($companies as $company)
					<?php 
						$provinsi = App\Models\Provinsi::where('id',$company->provinsi)->first();
						$kabupaten = App\Models\Kabupaten::where('id',$company->kabupaten)->first();
						$kecamatan = App\Models\Kecamatan::where('id',$company->kecamatan)->first();
						$kelurahan = App\Models\Kelurahan::where('id',$company->kelurahan)->first();
					?>
					<tr>
						<th align="left">Nama</th>
						<td>:</td>
						<td>{{$company->name}}</td>
					</tr>
					<tr>
						<th align="left">Alamat</th>
						<td>:</td>
						<td>{{$company->address}}, {{$kelurahan->nama}} {{$company->zip_code}} {{$kecamatan->nama}} {{$kabupaten->nama}} - {{$provinsi->nama}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			<p align="justify">Untuk selanjutnya disebut PIHAK KEDUA. </p>
			<p align="justify">PIHAK PERTAMA dan PIHAK KEDUA menerangkan terlebih dahulu bahwa PIHAK PERTAMA pada saat ini adalah Anggota Koperasi dan PIHAK KEDUA  dalam hal ini sebagai Usaha Koperasi Simpan Pinjam, untuk selanjutnya dalam perjanjian ini untuk dan atas nama koperasi.</p>
			<br/>
			<p align="center"><strong>PASAL 1</strong> <br>
			<strong>MAKSUD DAN TUJUAN</strong></p>

			<p align="justify">Dalam rangka kebutuhan modal usaha dan selaku anggota Koperasi Simpan Pinjam Parodana M, maka PIHAK PERTAMA bermaksud meminjam uang sebagai modal usahanya sebesar Rp. {{number_format($approves->approve_amount, 0, ',' , '.') }}.- ( {{ App\Helper\Terbilang::bilang($approves->approve_amount) }} rupiah ) kepada PIHAK KEDUA dan PIHAK KEDUA menyatakan bersedia untuk meminjamkan uang kepada PIHAK PERTAMA dengan suku bunga 3% perbulan flat dan wajib menabung sebesar Rp.100.000.- setiap bulan.</p>
			
			<p align="center"><strong>PASAL 2</strong><br>
			<strong>PENYERAHAN UANG</strong></p>

			<p align="justify">Pada saat perjanjian ini ditandatangani, PIHAK KEDUA akan menyerahkan uang tunai kepada PIHAK PERTAMA sebesar Rp. {{number_format($approves->approve_amount, 0, ',' , '.') }}.- ( {{ $bilang }} rupiah ) dan PIHAK PERTAMA  mengakui telah menerimanya dengan kesepakatan potongan biaya tata laksana {{$contract->provision}}% (Rp {{number_format($provisi, 0, ',' , '.') }}), potongan asuransi {{$contract->insurance}}% (Rp. {{number_format($asuransi, 0, ',' , '.') }}) dan potongan materai Rp. {{number_format($contract->stamp, 0, ',' , '.') }}  dan sudah disetujui dan ditandatangani pada kwitansi serah terima uang pinjaman.</p>
			
			<p align="center"><strong> PASAL 3</strong><br>
			<strong>PENGAKUAN HUTANG</strong></p>

			<p align="justify">Dengan telah diterimanya uang sebagaimana disebut pada pasal 2 maka PIHAK KEDUA dengan ini mengakui bahwa pinjaman tersebut menjadi hutang PIHAK PERTAMA kepada PIHAK KEDUA dengan kesepakatan PIHAK PERTAMA bersedia untuk mengikuti segala aturan ataupun prosedur sebagai berikut:
			PIHAK PERTAMA bersedia dan berjanji tidak melakukan kecurangan berupa; Pemblokiran ATM, menggunakan Internet Banking atapun SMS Banking dan Pencairan BPJS secara sepihak selama menjadi anggota koperasi.
			PIHAK PERTAMA bersedia dan berjanji untuk tidak melakukan Akad Kredit ke Koperasi atau BANK lain selama menjadi anggota koperasi, apabila terbukti PIHAK PERTAMA wajib melunasi pinjaman pada PIHAK KEDUA. Apabila dana pinjaman dari pihak lain tidak mencukupi untuk pelunasan maka PIHAK PERTAMA wajib menabung sebesar 25% dari nominal pinjaman.
			PIHAK PERTAMA setuju dan bersedia apabila suatu waktu PIHAK KEDUA melakukan pergantian PIN ATM ataupun pengecekan Mutasi Gajian dari PIHAK PERTAMA.
			PIHAK PERTAMA menyetujui bersedia untuk menonaktifkan M-Banking ATM payrool yang telah dijaminkan.</p>
			
			<p align="center"><strong>PASAL 4</strong><br>
			<strong>SISTEM PENGEMBALIAN UANG</strong</p>

			<p align="justify">Dalam rangka mengembalikan seluruh pinjaman tersebut, PIHAK PERTAMA menyatakan bersedia dengan kesepakatan sebagai berikut:
			PIHAK PERTAMA dengan ini memberi kuasa, kekuasaan dan wewenang penuh kepada KOPERASI SIMPAN PINJAM PARODANA M setiap waktu dari waktu yang ditetapkan oleh KSP PARODANA M sendiri khususnya untuk mendebet rekening PEMINJAM sebesar Rp. {{ number_format($approves->approve_amount, 2, ',' , '.') }}.- ({{ App\Helper\Terbilang::bilang($approves->approve_amount) }} rupiah) selama 24 bulan  dengan Nomor Rekening 1388 4533 200 BANK OCBC atas nama PIHAK PERTAMA, dan bersedia dipotong langsung oleh PIHAK KEDUA dimulai tanggal 05 April 2021. 
			PIHAK PERTAMA bersedia dan berjanji melunasi pinjaman terhadap PIHAK KEDUA melalui uang pesangon apabila kemudian hari terjadi pemutusan kerja dari perusahaan
			Melalui pencairan BPJS Ketenagakerjaan apabila PIHAK PERTAMA berhenti bekerja.</p>
			
			<p align="center"><strong>PASAL 5</strong><br>
			<strong>KELALAIAN</strong></p>

			<p align="justify">Apabila PIHAK PERTAMA lalai menjalankan kewajibannya, dan atau usaha PIHAK PERTAMA yang dimaksudkan dalam perjanjian ini tidak berjalan dengan baik atau tidak bekerja, dan atau PIHAK PERTAMA dinyatakan pailit dan karena sebab apapun sehingga PIHAK KEDUA mengalami kerugian, maka: 
			PIHAK KEDUA berhak menuntut dari PIHAK PERTAMA dengan segera dan sekaligus melunasi hutangnya. 
			PIHAK KEDUA berhak untuk mempertimbangkan dapat atau tidaknya dilakukan pelunasan sebagian atau seluruh jumlah kredit yang terhutang atau sesuai angsuran yang tertuang pada Pasal 4 ayat 1.
			Untuk setiap kelalaian yang ditimbulkan oleh PIHAK PERTAMA yang menyebabkan kerugian kepada PIHAK KEDUA maka PIHAK PERTAMA wajib membayarkan denda sebesar 5% / bulan dari total tunggakan pokok.</p>
			
			<p align="center"><strong>PASAL 6</strong><br>
			<strong>KEWAJIBAN AHLI WARIS</strong></p>

			<p align="justify">Dalam hal PIHAK PERTAMA meninggal dunia, dan PIHAK PERTAMA memiliki riwayat pembayaran angsuran tidak lancar, maka PENJAMIN yang sah berkewajiban untuk melunasi sisa hutang PIHAK PERTAMA kepada PIHAK KEDUA.</p>
			
			<p align="center"><strong>PASAL 7</strong><br>
			<strong>JAMINAN HUTANG</strong></p>

			<p align="justify">PIHAK PERTAMA dengan ini menyerahkan sebagai jaminan hutang berupa @foreach($handovers as $handover) {{$handover->berkas}} ({{$handover->status}}), @endforeach
			PIHAK PERTAMA memberikan kuasa kepada  PIHAK KEDUA untuk mengurus segala sesuatunya yang berkaitan erat dengan jaminan sebagaimana yang disebutkan pada ayat 1 (satu).</p>
			
			<p align="center"><strong>PASAL 8</strong><br>
			<strong>SURAT KUASA</strong></p>

			<p align="justify">Surat Kuasa yang diberikan oleh PIHAK PERTAMA kepada PIHAK KEDUA sehubungan dengan perjanjian ini, merupakan bagian yang tidak terpisahkan dan tidak dapat ditarik kembali dan juga tidak akan berakhir karena PIHAK PERTAMA meninggal dunia dan atau karena sebab apapun selama PIHAK PERTAMA masih memiliki kewajiban atau hutang kepada PIHAK KEDUA.</p>
			
			<p align="center"><strong>PASAL 9</strong><br>
			<strong>PENYELESAIAN PERSELESIHAN</strong></p>

			<p align="justify">Hal-hal yang tidak atau belum diatur dan atau karena terjadi perbedaan penafsiran terhadap ketentuan-kententuan dalam perjanjian ini yang menimbulkan perselisihan antara PIHAK PERTAMA dan PIHAK KEDUA, maka kedua belah pihak sepakat untuk menyelesaikannya secara musyawarah untuk mufakat. Apabila penyelesaian secara musyawarah dan mufakat tidak tercapai, PIHAK PERTAMA dan PIHAK KEDUA sepakat untuk menyelesaikan secara hukum dan oleh karena itu PIHAK PERTAMA dan PIHAK KEDUA sepakat memilih domisili yang umum dan tetap di kantor Panitera Pengadilan Negeri Serang Banten, dengan kesepakatan seluruh biaya ditanggung oleh PIHAK PERTAMA.</p>
			
			<p align="center"><strong>PASAL 10</strong><br>
			<strong>PENUTUP</strong></p>

			<p align="justify">Pernjanjian ini berlaku dan mengikat terhadap KSP PARODANA M, PEMINJAM dan tidak dapat diubah, diperbaharui kecuali dengan satu perjanjian.
			PIHAK PERTAMA dan PIHAK KEDUA menyatakan bahwa perjanjian ini dibuat dan ditandatangani dalam keadaan sehat jasmani dan rohani dan tidak ada tekanan serta paksaan dari pihak manapun juga.
			Perjanjian ini dibuat rangkap 2 (dua) di atas kertas bermeterai 10.000, yang keduanya mempunyai kekuatan hukum yang sama.</p>
			<table>
			<tr>  					   
				<td width="300" align="right"> {{ $kecamatan->nama }}, {{ date('d-m-Y', strtotime($contract->contract_date))}} </td>  
			</tr>
			<tr>
				<td height="60" width="30" align="center">PIHAK PERTAMA</td>
				<td height="60" width="30"></td>
				<td height="60" width="30" align="center">KSP PARODANA M</td>
			</tr>
			<tr>
				<td height="80" width="30" align="center">( {{$customer->name}} )</td>
				<td height="80" width="30"></td>
				<td height="80" width="30" align="center">(....................................)</td>
			</tr>
			</table>                                                                                 		@endforeach
		@endforeach
		</main>
		<footer>
		</footer>
	</body>
</html>