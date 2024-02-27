<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Employee</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<style type="text/css">
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
		</style>
	</head>
	<body>
		<div>
			<img src="{{public_path('/img/logo/logo-med.png')}}" alt="">
			<?php
				$companies = App\Models\Company::all();
			?>
			@foreach($companies as $company)
				<div><p align="center">{{$company->siup}}</p></div>
				<div><p align="center">{{$company->name}}</p></div>
				<div><p align="center">{{$company->address}}</p></div>
			@endforeach
		</div>
		<div>
			<h5>Daftar Karyawan</h5>
		</div>
		<hr>
		<table class="table table-responsive-sm table-striped">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Branch</th>
					</tr>
				</thead>
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
						<td>{{$user->name}}</td>
						<td>{{$user->email}}</td>
						<td>							
							@foreach ($branchs as $brox)
							{{$brox->name}}
							@endforeach
						</td>						
					</tr>
					@empty
					<tr>
					</tr>
					@endforelse
				</tbody>
			</table>
		
	</body>
</html>