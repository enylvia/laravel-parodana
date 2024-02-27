<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Employee</title>
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
	</head>
	<body>
				
		@foreach($users as $user)
		<?php
			$employees = App\Models\Employee::where('user_id',$user->id)->first();			
			$companies = App\Models\Company::where('id',$employees->branch)->get();
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
		
		<center>
			<h5>Data Karyawan</h5>
			<center>
			
			<img src="{{public_path('/uploads/photo/'.$user->avatar)}}" alt="" style="width: 80px; height: 90px;">
			
			</center>
			<br>
			<table>
				<tbody>
				@forelse($users as $user)	
					<?php 
						$employees = App\Models\Employee::where('user_id',$user->id)->get();
						foreach($employees as $employee)
						{
							$cabang = $employee->branch;
						}
						$branchs = App\Models\Company::where('id',$cabang)->get();
					?>
					<tr>
						<td>Nama Lengkap</td>
						<td>:</td>
						<td>{{$user->name}}</td>
					</tr>
					<tr>
						<td>Email</td>
						<td>:</td>
						<td>{{$user->email}}</td>
					</tr>
					<tr>
						<td>Telpon</td>
						<td>:</td>
						<td>{{$user->mobile_phone}}</td>
					</tr>
					@foreach($employees as $employee)
						<tr>
							<td>KTP</td>
							<td>:</td>
							<td>{{$employee->population_card}}</td>
						</tr>
					@endforeach
				@empty
					<tr>
					</tr>
				@endforelse
				</tbody>
			</table>
		</center>
	</body>
</html>