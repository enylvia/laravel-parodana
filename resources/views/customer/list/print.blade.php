<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Surat Permohonan Pengajuan Kredit | SPPK</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">		
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
		@foreach($customers as $customer)		
			<p align="center" style = "font-size:20px">SURAT PERMOHONAN PENGAJUAN KREDIT</p>
			<p align="center" style = "font-size:14px; text-decoration: underline;">No. REG : {{$customer->reg_number}} </p>
				
		<?php
			$contracts = App\Models\CustomerContract::where('customer_id',$customer->id)->get();
			$approves = App\Models\CustomerApprove::where('customer_id',$customer->id)->first();
			$customer_provinsi = App\Models\Provinsi::where('id',$customer->provinsi)->first();
			$customer_kabupaten = App\Models\Kabupaten::where('id',$customer->kabupaten)->first();
			$customer_kecamatan = App\Models\Kecamatan::where('id',$customer->kecamatan)->first();
			$customer_kelurahan = App\Models\Kelurahan::where('id',$customer->kelurahan)->first();
			$maritials = App\Models\Maritial::where('id',$customer->maritial)->first();
		?>
			<table class="table" width="100%">
				<tbody>
					<tr>
						<td width="35%" align="left" style = "font-size:14px; text-decoration: underline; font-weight:bold">DATA PRIBADI</td>
					</tr>
					<tr>
						<th></th>
					</tr>
					<tr>
						<th width="25%" align="left">Nama</th>
						<td width="5%">:</td>
						<td width="70%">{{$customer->name}}</td>
					</tr>
					<tr>
						<th align="left">Tempat/Tgl Lahir</th>
						<td>:</td>
						<td>{{$customer->birth_place}} / {{ date('l, d-m-Y', strtotime($customer->date_of_birth))}}</td>
					</tr>
					<tr>
						<th align="left">Alamat</th>
						<td>:</td> 
						<td align="center">{{ !empty($customer->address) ? $customer->address : '' }}, {{ !empty($customer_kelurahan->nama) ? $customer_kelurahan->nama : '' }} {{ !empty($customer->zip_code) ? $customer->zip_code : '' }} </td>
					</tr>
					<tr>
						<th></th>
						<td></td>
						<td>{{ !empty($customer_kecamatan->nama) ? $customer_kecamatan->nama : '' }} {{ !empty($customer_kabupaten->nama) ? $customer_kabupaten->nama : '' }} - {{ !empty($customer_provinsi->nama) ? $customer_provinsi->nama : '' }}</td>
					</tr>
					<tr>
						<th align="left">KTP</th>
						<td>:</td>
						<td>{{$customer->card_number}}</td>
					</tr>
					<tr>
						<th align="left">No. KK</th>
						<td>:</td>
						<td>{{$customer->family_card_number}}</td>
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
					<tr>
						<th></th>
					</tr>
					<tr>
						<td align="left" style = "font-size:14px; text-decoration: underline; font-weight:bold">DATA PERUSAHAAN</td>
					</tr>
					<tr>
						<th></th>
					</tr>
					<tr>
						<th align="left">Nama Perusahaan</th>
						<td>:</td>
						<td>{{$customer->company_name}}</td>
					</tr>
					<tr>
						<th align="left">Departemen</th>
						<td>:</td>
						<td>{{$customer->department}}</td>
					</tr>
					<tr>
						<th align="left">Jabatan</th>
						<td>:</td>
						<td>{{$customer->part}}</td>
					</tr>
					<tr>
						<th align="left">Nomor KPK</th>
						<td>:</td>
						<td>{{$customer->kpk_number}}</td>
					</tr>
					<tr>
						<th align="left">Nama Personalia</th>
						<td>:</td>
						<td>{{$customer->personalia_name}}</td>
					</tr>										
					<tr>
						<th></th>
					</tr>
					<tr>
						<td align="left" style = "font-size:14px; text-decoration: underline; font-weight:bold">PENGHASILAN PERBULAN</td>
					</tr>
					<tr>
						<th></th>
					</tr>
					<tr>
						<th align="left">Gaji Bersih</th>
						<td>:</td>
						<td>{{$customer->net_salary}}</td>
					</tr>
					<tr>
						<th align="left">Gaji Kotor</th>
						<td>:</td>
						<td>{{$customer->gross_salary}}</td>
					</tr>
					<tr>
						<th align="left">Tanggal Gajian</th>
						<td>:</td>
						<td>{{$customer->payday_date}}</td>
					</tr>
					<tr>
						<th align="left">Nama Bank</th>
						<td>:</td>
						<td>{{$customer->bank_name}}</td>
					</tr>
					<tr>
						<th></th>
					</tr>
					<tr>
						<td align="left" style = "font-size:14px; text-decoration: underline; font-weight:bold">STATUS PERKAWINAN</td>
					</tr>
					<tr>
						<th></th>
					</tr>
					<tr>
						<th align="left">Status</th>
						<td>:</td>
						<td>{{ !empty($maritials->name) ? $maritials->name : '' }}</td>
					</tr>
					<tr>
						<th align="left">Suami/Istri</th>
						<td>:</td>
						<td>{{$customer->husband_wife}}</td>
					</tr>
					<tr>
						<th align="left">Nama Alias Suami/Istri</th>
						<td>:</td>
						<td>{{$customer->alias_husband_wife}}</td>
					</tr>
					<tr>
						<th align="left">Telpon</th>
						<td>:</td>
						<td>{{$customer->husband_wife_phone}}</td>
					</tr>
					<tr>
						<th align="left">Alamat</th>
						<td>:</td>
						<td>{{$customer->husband_wife_address}}</td>
					</tr>
					<tr>
						<th align="left">Pekerjaan</th>
						<td>:</td>
						<td>{{$customer->husband_wife_profession}}</td>
					</tr>
					<tr>
						<th align="left">Pemasukan</th>
						<td>:</td>
						<td>{{$customer->husband_wife_income}}</td>
					</tr>
					<tr>
						<th align="left">Status Rumah</th>
						<td>:</td>
						<td>{{$customer->husband_wife_home_status}}</td>
					</tr>
					<tr>
						<th></th>
					</tr>
					<tr>
						<td align="left" style = "font-size:14px; text-decoration: underline; font-weight:bold">DATA KELUARGA</td>
					</tr>
					<tr>
						<th></th>
					</tr>
					<tr>
						<th align="left">Ayah</th>
						<td>:</td>
						<td>{{$customer->family_father}}</td>
					</tr>
					<tr>
						<th align="left">Ibu</th>
						<td>:</td>
						<td>{{$customer->family_mother}}</td>
					</tr>
					<tr>
						<th align="left">Alamat</th>
						<td>:</td>
						<td>{{$customer->family_address}}</td>
					</tr>
					<tr>
						<th align="left">Ayah Mertua</th>
						<td>:</td>
						<td>{{$customer->in_law_father}}</td>
					</tr>
					<tr>
						<th align="left">Ibu Mertua</th>
						<td>:</td>
						<td>{{$customer->in_law_mother}}</td>
					</tr>
					<tr>
						<th align="left">Telpon</th>
						<td>:</td>
						<td>{{$customer->in_law_phone}}</td>
					</tr>
					<tr>
						<th align="left">Alamat</th>
						<td>:</td>
						<td>{{$customer->in_law_address}}</td>
					</tr>
					<tr>
						<th></th>
					</tr>
					<tr>
						<td colspan="3" align="left" style = "font-size:14px; font-weight:bold"><span>Dalam kondisi darurat, anggota keluarga yang bisa di hubungi:</span></td>
					</tr>
					<tr>
						<th></th>
					</tr>
					<tr>
						<th align="left">Nama</th>
						<td>:</td>
						<td>{{$customer->connection_name}}</td>
					</tr>
					<tr>
						<th align="left">Nama Alias</th>
						<td>:</td>
						<td>{{$customer->connection_alias_name}}</td>
					</tr>
					<tr>
						<th align="left">Telpon</th>
						<td>:</td>
						<td>{{$customer->connection_phone}}</td>
					</tr>
					<tr>
						<th align="left">Status</th>
						<td>:</td>
						<td>{{$customer->family_connection}}</td>
					</tr>
					<tr>
						<th align="left">Alamat</th>
						<td>:</td>
						<td>{{$customer->connection_address}}</td>
					</tr>
					
				</tbody>
			</table>			
		@endforeach		
		</main>
		<footer>
			CopyRight : PARODANA-M
		</footer>
	</body>
</html>