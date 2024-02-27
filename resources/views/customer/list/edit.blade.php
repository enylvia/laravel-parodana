@extends('layouts.app')
@section('content')
<style type="text/css">

    input[type=file]{

      display: inline;

    }

    #image_preview{

      border: 1px solid black;

      padding: 10px;

    }

    #image_preview img{

      width: 200px;

      padding: 5px;

    }

</style>

	@include('error.error-notification');
	
		<div class="box">
		@foreach($customers as $customer)
			<?php          
				//$countries = App\Models\Country::all();
				$provinsis = App\Models\Provinsi::all();
				//dd($countries);
				$customers = App\Models\Customer::orderBy('id','DESC')->get();
			?>
			<div class="box-header">
				<ul class="nav nav-pills" id="tabMenu">
					<li class="nav-item"><a class="nav-link" href="#personal_data" data-toggle="tab"><strong>{{trans('loan.personal_data')}}</strong></a></li>
					<li class="nav-item"><a class="nav-link" href="#company_data" data-toggle="tab"><strong>{{trans('loan.company_data')}}</strong></a></li>
					<li class="nav-item"><a class="nav-link" href="#maritial_status" data-toggle="tab"><strong>{{trans('loan.maritial_status')}}</strong></a></li>
					<li class="nav-item"><a class="nav-link" href="#family_data" data-toggle="tab"><strong>{{trans('loan.family_data')}}</strong></a></li>
					<li class="nav-item"><a class="nav-link" href="#submission" data-toggle="tab"><strong>{{trans('loan.submission')}}</strong></a></li>
					<li class="nav-item"><a class="nav-link" href="#photo" data-toggle="tab"><strong>{{trans('loan.photo')}}</strong></a></li>					
				</ul>
			</div>
			
			<form method="post" action="{{URL::to('/customer/list/update', $customer->id)}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="box-body">				
					<div class="tab-content">						
						<div class="tab-pane active" id="personal_data">
						
							<div class="box-header">
								<strong>{{trans('loan.personal_data')}}</strong> 
								<small>{{trans('loan.form')}}</small>
							</div>
							<div class="box-body">			
								<div class="form-group col-sm-4">
									<label for="company">{{trans('loan.name')}}</label>
									<input name="name" class="form-control" id="company" type="text" value="{{$customer->name}}" required>
								</div>
								<!--div class="form-group col-sm-6 {!! $errors->has('email') ? 'has-error' : '' !!} required ">
									<label for="email" class="control-label">{{ trans('general.email') }}</label>
									<input class="form-control" name="email" type="email" placeholder="{{trans('general.email')}}">
									@if ($errors->first('email'))
									<span class="help-block">{!! $errors->first('email') !!}</span>
									@endif
								</div-->
								<div class="form-group col-sm-4">
									<label for="vat">{{trans('loan.mobile_phone')}}</label>
									<input name="mobile_phone" class="form-control" id="mobile_phone" type="text" value="{{$customer->mobile_phone}}" required>
								</div>
								<div class="form-group col-sm-4">
									<label for="company">{{trans('loan.birth_place')}}</label>
									<input name="birth_place" class="form-control" id="birth_place" type="text" value="{{$customer->birth_place}}" required>
								</div>
								<div class="form-group col-sm-4">
									<label for="company">{{trans('loan.date_of_place')}}</label>
									<input name="date_birth" class="form-control" id="date_place" type="date" value="{{$customer->date_of_birth}}" required>
								</div>							
								<div class="form-group col-sm-4">
									<label for="vat">{{trans('loan.family_card_number')}}</label>
									<input name="family_card_number" class="form-control" id="family_card_number" type="text" value="{{$customer->family_card_number}}" required>
								</div>
								<div class="form-group col-sm-4">
									<label for="vat">{{trans('loan.card_number')}}</label>
									<input name="card_number" class="form-control" id="card_number" type="text" value="{{$customer->card_number}}" required>
								</div>		
								<div class="form-group col-sm-4">
									<label for="vat">{{trans('loan.mother_maiden_name')}}</label>
									<input name="mother_maiden_name" class="form-control" id="mother_maiden_name" type="text" value="{{$customer->mother_maiden_name}}" required>
								</div>
								<div class="form-group col-sm-4">
									<label for="city">{{trans('loan.gender')}}</label>
									<select class="form-control" id="gender" name="gender">
										@if ($customer->gender === 'Lelaki')							
											<option value="1" selected="true">Lelaki</option>
											<option value="2">Perempuan</option>							
										@elseif($customer->gender === 'Perempuan')	
											<option value="2" selected="true">Perempuan</option>
											<option value="1">Lelaki</option>	
										@else
											<option value="1">Lelaki</option>
											<option value="2">Perempuan</option>
										@endif
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="provinsi">{{trans('loan.province')}}</label>
									<select class="input select2 select2-hidden-accessible" onchange="provinsi" style="width: 100%;" aria-hidden="true" name="provinsi" id="provinsi" required>
										<?php
											$roll = [];
											$provinsis = App\Models\Provinsi::All();                        
											$roll[] = $customer->provinsi;
										?>
										@foreach($provinsis as $provinsi)
											@if(in_array($provinsi->id, $roll))
											  <option value="{{ $provinsi->id }}" selected="true">{{ $provinsi->nama }}</option>
											@else
											  <option value="{{ $provinsi->id }}">{{ $provinsi->nama }}</option>
											@endif 
										@endforeach  
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kabupaten">{{trans('loan.regency')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="kabupaten" id="kabupaten" required>
										<?php
											$roll = [];
											$kabupatens = App\Models\Kabupaten::where('provinsi_id',$customer->provinsi)->get();                       
											$roll[] = $customer->kabupaten;
										?>
										@foreach($kabupatens as $kabupaten)
											@if(in_array($kabupaten->id, $roll))
											  <option value="{{ $kabupaten->id }}" selected="true">{{ $kabupaten->nama }}</option>
											@else
											  <option value="{{ $kabupaten->id }}">{{ $kabupaten->nama }}</option>
											@endif 
										@endforeach	
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kecamatan">{{trans('loan.districts')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="kecamatan" id="kecamatan" required>
										<?php
											$roll = [];
											$kecamatans = App\Models\Kecamatan::where('kabupaten_id',$customer->kabupaten)->get();                       
											$roll[] = $customer->kecamatan;
										?>
										@foreach($kecamatans as $kecamatan)
											@if(in_array($kecamatan->id, $roll))
											  <option value="{{ $kecamatan->id }}" selected="true">{{ $kecamatan->nama }}</option>
											@else
											  <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama }}</option>
											@endif 
										@endforeach
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kelurahan">{{trans('loan.vilage')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="kelurahan" id="kelurahan" required>
										<?php
											$roll = [];
											$kelurahans = App\Models\Kelurahan::where('kecamatan_id',$customer->kecamatan)->get();                       
											$roll[] = $customer->kelurahan;
										?>
										@foreach($kelurahans as $kelurahan)
											@if(in_array($kelurahan->id, $roll))
											  <option value="{{ $kelurahan->id }}" selected="true">{{ $kelurahan->nama }}</option>
											@else
											  <option value="{{ $kelurahan->id }}">{{ $kelurahan->nama }}</option>
											@endif 
										@endforeach
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label for="roles">{{trans('loan.education')}}</label>								
									<select name="education" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" required>
										<?php
											$roll = [];								                       
											$roll[] = $customer->education;
										?>
										@foreach($educations as $education)
											@if(in_array($education->id, $roll))
											  <option value="{{ $education->id }}" selected="true">{{ $education->name }}</option>
											@else
											  <option value="{{ $education->id }}">{{ $education->name }}</option>
											@endif 
										@endforeach
									</select>
								</div>					
								<div class="form-group col-sm-4">
									<label for="roles">{{trans('loan.religion')}}</label>								
									<select name="religion" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" required>
										<option value="">-- Religion --</option>
										<?php
											$roll = [];								                       
											$roll[] = $customer->religion;
										?>
										@foreach($religions as $religion)
											@if(in_array($religion->id, $roll))
											  <option value="{{ $religion->id }}" selected="true">{{ $religion->name }}</option>
											@else
											  <option value="{{ $religion->id }}">{{ $religion->name }}</option>
											@endif 
										@endforeach
									</select>
								</div>
								<div class="form-group col-sm-8">
									<label for="street">{{trans('loan.address')}}</label>
									<input name="address" class="form-control" id="street" type="text" value="{{$customer->address}}" required>
								</div>
								<div class="form-group col-sm-4">
									<label for="postal-code">{{trans('loan.postal_code')}}</label>
									<input name="zip_code" class="form-control" id="zip_code" type="text" value="{{$customer->zip_code}}">
								</div>			
							</div>
							
						</div>
									
						<div class="tab-pane" id="company_data">
						
							<div class="box-header">
								<strong>{{trans('loan.company_data')}}</strong> 
								<small>{{trans('loan.form')}}</small>
							</div>
							<div class="box-body">	
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.company_name')}}</label>									
									<input name="company_name" class="form-control" id="company_name" type="text" value="{{$customer->company_name}}">
								</div>				
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.department')}}</label>
									<input name="department" class="form-control" id="department" type="text" value="{{$customer->department}}">
								</div>					
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.part')}}</label>
									<input name="part" class="form-control" id="part" type="text" value="{{$customer->part}}">
								</div>					
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.kpk_number')}}</label>
									<input name="kpk_number" class="form-control" id="kpk_number" type="text" value="{{$customer->kpk_number}}">
								</div>					
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.personalia_name')}}</label>
									<input name="personalia_name" class="form-control" id="personalia_name" type="text" value="{{$customer->personalia_name}}">
								</div>			
							</div>
							
							<div class="box-header">
								<strong>{{trans('loan.income_per_month')}}</strong> 
								<small>{{trans('loan.form')}}</small>
							</div>
							<div class="box-body">
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.net_salary')}}</label>
									<input name="net_salary" class="form-control" type="text" value="{{$customer->net_salary}}" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
								</div>					
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.gros_salary')}}</label>
									<input name="gross_salary" class="form-control" type="text" value="{{$customer->gross_salary}}" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
								</div>								
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.payday_date')}}</label>
									<input name="payday_date" class="form-control" id="payday_date" type="number" value="{{$customer->payday_date}}">
								</div>									
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.bank_name')}}</label>
									<input name="bank_name" class="form-control" id="bank_name" type="text" value="{{$customer->bank_name}}">
								</div>
							</div>
							
							
						</div>
						
						<div class="tab-pane" id="maritial_status" disabled>
						
							<div class="box-header">
								<strong>{{trans('loan.maritial_status')}}</strong> 
								<small>{{trans('loan.form')}}</small>
							</div>
							<div class="box-body">
								<div class="form-group col-sm-4">
									<label for="roles">{{trans('loan.maritial_status')}}</label>								
									<select name="maritial" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" id="maritial" required>
										<option value="">-- Maritial --</option>
										<?php
											$roll = [];								                       
											$roll[] = $customer->maritial;
										?>
										@foreach($maritials as $maritial)
											@if(in_array($maritial->id, $roll))
											  <option value="{{ $maritial->id }}" selected="true">{{ $maritial->name }}</option>
											@else
											  <option value="{{ $maritial->id }}">{{ $maritial->name }}</option>
											@endif 
										@endforeach
									</select>
								</div>			
								<div id="pasangan">
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.husband_wife')}}</label>
										<input name="husband_wife" class="form-control" id="husband_wife" type="text" value="{{$customer->husband_wife}}">
									</div>					
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.alias_husband_wife')}}</label>
										<input name="alias_husband_wife" class="form-control" id="alias_husband_wife" type="text" value="{{$customer->alias_husband_wife}}">
									</div>
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.husband_wife_profession')}}</label>
										<input name="husband_wife_profession" class="form-control" id="husband_wife_profession" type="text" value="{{$customer->husband_wife_profession}}">
									</div>
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.husband_wife_income')}}</label>
										<input name="husband_wife_income" class="form-control" type="text" value="{{$customer->husband_wife_income}}" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
									</div>
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.husband_wife_phone')}}</label>
										<input name="husband_wife_phone" class="form-control" id="husband_wife_phone" type="text" value="{{$customer->husband_wife_phone}}">
									</div>
									<div class="form-group col-sm-12">
										<label for="postal-code">{{trans('loan.husband_wife_address')}}</label>
										<input name="husband_wife_address" class="form-control" id="husband_wife_address" type="text" value="{{$customer->husband_wife_address}}">
									</div>
								</div>							
								<div class="form-group col-sm-4">
									<label for="city">{{trans('loan.husband_wife_home_status')}}</label>
									<!--input class="form-control" id="city" type="text" placeholder="Enter your city"-->
									<select class="form-control" id="husband_wife_home_status" name="husband_wife_home_status">
										<?php
											$roll = [];
											$employees = ['Milik Sendiri (tidak dijaminkan)','Milik Sendiri (dijaminkan)','Milik Keluarga','Kontrak','Kost','KPR'];                        
											$roll[] = $customer->home_status;
										?>
										@foreach($employees as $employee)
											@if(in_array($employee, $roll))
											  <option value="{{ $employee }}" selected="true">{{ $employee }}</option>
											@else
											  <option value="{{ $employee }}">{{ $employee }}</option>
											@endif 
										@endforeach
									</select>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="family_data">
						
							<div class="box-header">
								<strong>{{trans('loan.family_data')}}</strong> 
								<small>{{trans('loan.form')}}</small>
							</div>
							<div class="box-body">
								<div class="form-group col-sm-4">
									<label for="postal-code">{{trans('loan.family_father')}}</label>
									<input name="family_father" class="form-control" id="family_father" type="text" value="{{$customer->family_father}}">
								</div>
								<div class="form-group col-sm-4">
									<label for="postal-code">{{trans('loan.family_mother')}}</label>
									<input name="family_mother" class="form-control" id="family_mother" type="text" value="{{$customer->family_mother}}">
								</div>
								<div class="form-group col-sm-4">
									<label for="postal-code">{{trans('loan.family_phone')}}</label>
									<input name="family_phone" class="form-control" id="family_phone" type="text" value="{{$customer->family_phone}}">
								</div>
								<div class="form-group col-sm-12">
									<label for="postal-code">{{trans('loan.family_address')}}</label>
									<input name="family_address" class="form-control" id="family_address" type="text" value="{{$customer->family_address}}">
								</div>				
								<div id="mertua" style="">
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.in_law_father')}}</label>
										<input name="in_law_father" class="form-control" id="in_law_father" type="text" value="{{$customer->in_law_father}}">
									</div>
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.in_law_mother')}}</label>
										<input name="in_law_mother" class="form-control" id="in_law_mother" type="text" value="{{$customer->in_law_mother}}">
									</div>
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.in_law_phone')}}</label>
										<input name="in_law_phone" class="form-control" id="in_law_phone" type="text" value="{{$customer->in_law_phone}}">
									</div>
									<div class="form-group col-sm-12">
										<label for="postal-code">{{trans('loan.in_law_address')}}</label>
										<input name="in_law_address" class="form-control" id="in_law_address" type="text" value="{{$customer->in_law_address}}">
									</div>
								</div>
								
								<div class="form-group col-sm-12">
									<p>Dalam keadaan darurat, anggota keluarga yang bisa di hubungi :</p>
								</div>
								<div class="form-group col-sm-4">
									<label for="postal-code">{{trans('loan.connection_name')}}</label>
									<input name="connection_name" class="form-control" id="connection_name" type="text" value="{{$customer->connection_name}}">
								</div>
								<div class="form-group col-sm-4">
									<label for="postal-code">{{trans('loan.connection_alias_name')}}</label>
									<input name="connection_alias_name" class="form-control" id="connection_alias_name" type="text" value="{{$customer->connection_alias_name}}">
								</div>
								<div class="form-group col-sm-4">
									<label for="postal-code">{{trans('loan.connection_phone')}}</label>
									<input name="connection_phone" class="form-control" id="connection_phone" type="text" value="{{$customer->connection_phone}}">
								</div>
								<div class="form-group col-sm-12">
									<label for="postal-code">{{trans('loan.connection_address')}}</label>
									<input name="connection_address" class="form-control" id="connection_address" type="text" value="{{$customer->connection_address}}">
								</div>
								<div class="form-group col-sm-4">
									<label for="city">{{trans('loan.family_connection')}}</label>
									<select class="form-control" id="family_connection" name="family_connection">
										@if ($customer->family_connection === 'Orang Tua')							
											<option value="Orang Tua" selected="true">Lelaki</option>
											<option value="Saudara">Saudara</option>
											<option value="Sahabat">Sahabat</option>
											<option value="Rekan Kerja">Rekan Kerja</option>							
										@elseif($customer->family_connection === 'Saudara')	
											<option value="Orang Tua">Lelaki</option>
											<option value="Saudara" selected="true">Saudara</option>
											<option value="Sahabat">Sahabat</option>
											<option value="Rekan Kerja">Rekan Kerja</option>
										@elseif($customer->family_connection === 'Sahabat')	
											<option value="Orang Tua">Lelaki</option>
											<option value="Saudara">Saudara</option>
											<option value="Sahabat" selected="true">Sahabat</option>
											<option value="Rekan Kerja">Rekan Kerja</option>
										@elseif($customer->family_connection === 'Rekan Kerja')	
											<option value="Orang Tua">Lelaki</option>
											<option value="Saudara">Saudara</option>
											<option value="Sahabat">Sahabat</option>
											<option value="Rekan Kerja" selected="true">Rekan Kerja</option>
										@else
											<option value="Orang Tua">Orang Tua</option>
											<option value="Saudara">Saudara</option>
											<option value="Sahabat">Sahabat</option>
											<option value="Rekan Kerja">Rekan Kerja</option>
										@endif
									</select>
								</div>				
							</div>
							
						</div>
						
						<div class="tab-pane" id="submission">
						
							<div class="box-header">
								<strong>{{trans('loan.submission')}}</strong> 
								<small>{{trans('loan.form')}}</small>
							</div>
							<div class="box-body">
								<div class="form-group col-sm-4">
									<label>{{trans('survey.loan_amount')}}</label>
									<input class="form-control" value="{{$customer->loan_amount}}" name="loan_amount" type="text" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
								</div>
								<div class="form-group col-sm-4">
									<label for="city">{{trans('survey.loan_to')}}</label>
									<select class="form-control" id="loan_to" name="loan_to" required>										
										<?php
											$roll = [];
											$loansto = ['1','2','3','4','5','6'];                        
											$roll[] = $customer->loan_to;
										?>
										@foreach($loansto as $loanto)
											@if(in_array($loanto, $roll))
											  <option value="{{ $loanto }}" selected="true">{{ $loanto }}</option>
											@else
											  <option value="{{ $loanto }}">{{ $loanto }}</option>
											@endif 
										@endforeach
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label>{{trans('survey.time_period')}}</label> <span>*/Bulan</span>
									<select class="form-control" id="time_period" name="time_period" required>																				
										<?php
											$roll = [];
											$tenors = ['1','3','6','9','12','15','18','21','24','27','30','33','36'];                        
											$roll[] = $customer->time_period;
										?>
										@foreach($tenors as $tenor)
											@if(in_array($tenor, $roll))
											  <option value="{{ $tenor }}" selected="true">{{ $tenor }} Bulan</option>
											@else
											  <option value="{{ $tenor }}">{{ $tenor }} Bulan</option>
											@endif 
										@endforeach
									</select>
								</div>
								<!--div class="form-group col-sm-4">
									<label>{{trans('survey.installments_month')}}</label>
									<input class="form-control" placeholder="Rp. 0" name="installments_month" type="text" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
								</div-->
								<div class="form-group col-sm-4">
									<label for="city">{{trans('survey.necessity_for')}}</label>
									<select class="form-control" id="necessity_for" name="necessity_for" required>										
										@if ($customer->necessity_for === 'Renovasi Rumah')	
											<option value="Renovasi Rumah" selected="true">Renovasi Rumah</option>
											<option value="Modal Usaha">Modal Usaha</option>
											<option value="Biaya Sekolah">Biaya Sekolah</option>
											<option value="Lainnya">Lainnya</option>							
										@elseif($customer->necessity_for === 'Modal Usaha')	
											<option value="Renovasi Rumah">Renovasi Rumah</option>
											<option value="Modal Usaha" selected="true">Modal Usaha</option>
											<option value="Biaya Sekolah">Biaya Sekolah</option>
											<option value="Lainnya">Lainnya</option>
										@elseif($customer->necessity_for === 'Biaya Sekolah')	
											<option value="Renovasi Rumah">Renovasi Rumah</option>
											<option value="Modal Usaha">Modal Usaha</option>
											<option value="Biaya Sekolah" selected="true">Biaya Sekolah</option>
											<option value="Lainnya">Lainnya</option>
										@else
											<option value="Renovasi Rumah">Renovasi Rumah</option>
											<option value="Modal Usaha">Modal Usaha</option>
											<option value="Biaya Sekolah">Biaya Sekolah</option>
											<option value="Lainnya">Lainnya</option>
										@endif
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label>{{trans('survey.survey_plan')}}</label>
									<input type="date" id="survey_plan" name="survey_plan" class="form-control" value="{{$customer->survey_plan}}">
								</div>
								<!--div class="form-group col-sm-4">
									<label>{{trans('survey.surveyor_name')}}</label><input type="text" id="surveyor_name" name="surveyor_name" class="form-control">
								</div-->								
								<!--div class="form-group col-sm-12">
									<label>Location/City/Address</label>									
									<input type="text" name="autocomplete" id="autocomplete" class="form-control" placeholder="Choose Location">
								</div>
								<div class="form-group col-sm-6" id="latitudeArea">
									<label>Latitude</label>
									<input type="text" id="latitude" name="latitude" class="form-control">
								</div>

								<div class="form-group col-sm-6" id="longtitudeArea">
									<label>Longitude</label>
									<input type="text" name="longitude" id="longitude" class="form-control">
								</div-->
								
								<div class="form-group col-md-12">
									<label>{{trans('survey.note')}}</label>									
									<textarea id="reason" name="reason">{{$customer->reason}}</textarea>
								</div>
							</div>
							
						</div>
																		
						<div class="tab-pane" id="photo">
							<div class="box">
								<div class="box-header">
								</div>
								<div class="box-body">
									<div class="form-group col-sm-12">
										<label for="image" class="control-label">Avatar</label>
										<div class="controls">
											<img id="preview"
												 src="{{asset($customer->avatar!='' ?'uploads/photo/'.$customer->avatar:'uploads/photo/noimage.jpg')}}"
												 height="200px" width="200px"/>
											<input class="form-control" style="display:none" name="avatar" type="file" id="image">
											<br/>
											<a href="javascript:changeProfile();">Upload</a> |
											<a style="color: red" href="javascript:removeImage()">Remove</a>
											<input type="hidden" style="display: none" value="0" name="remove" id="remove">
										</div>
									</div>
								</div>
								<div class="box-footer">
									
								</div>
							</div>
						</div>
						
					</div>					
			</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-primary">{{trans('general.update')}}</button>
				<a href="{{ route('customer')}}" class="btn btn-danger">
					<span class="cil-close"></span> {{trans('general.close')}}
				</a>
			</div>
			</form>
						
		</div>		
	@endforeach
@endsection

@section('js')
<script type="text/javascript">						    
	CKEDITOR.replace('reason', {
		"filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
	});						    
</script>

<script>
	//redirect to specific tab
	$(document).ready(function () {
	$('#tabMenu a[href="#{{ old('tab') }}"]').tab('show')
	});
</script>

<script>
        
	function changeProfile() {
		$('#image').click();
	}
	$('#image').change(function () {
		var imgPath = $(this)[0].value;
		var ext = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
		if (ext == "gif" || ext == "png" || ext == "jpg" || ext == "jpeg")
			readURL(this);
		else
			alert("Please select image file (jpg, jpeg, png).")
	});
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.readAsDataURL(input.files[0]);
			reader.onload = function (e) {
				$('#preview').attr('src', e.target.result);
				$('#remove').val(0);
			}
		}
	}
	function removeImage() {
		$('#preview').attr('src', '{{url('uploads/noimage.jpg')}}');
		$('#remove').val(1);
	}
</script>
	
<script>
	$(document).ready(function(){
		$('#provinsi').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/provinsi')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#kabupaten').empty();
				$.each(data, function(index, subcatObj){
					$('#kabupaten').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#kabupaten').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/kabupaten')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#kecamatan').empty();
				$.each(data, function(index, subcatObj){
					$('#kecamatan').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#kecamatan').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/kecamatan')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#kelurahan').empty();
				$.each(data, function(index, subcatObj){
					$('#kelurahan').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#husband_wife_provinsi').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/provinsi')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#husband_wife_kabupaten').empty();
				$.each(data, function(index, subcatObj){
					$('#husband_wife_kabupaten').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#husband_wife_kabupaten').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/kabupaten')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#husband_wife_kecamatan').empty();
				$.each(data, function(index, subcatObj){
					$('#husband_wife_kecamatan').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#husband_wife_kecamatan').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/kecamatan')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#husband_wife_kelurahan').empty();
				$.each(data, function(index, subcatObj){
					$('#husband_wife_kelurahan').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#family_provinsi').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/provinsi')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#family_kabupaten').empty();
				$.each(data, function(index, subcatObj){
					$('#family_kabupaten').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#family_kabupaten').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/kabupaten')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#family_kecamatan').empty();
				$.each(data, function(index, subcatObj){
					$('#family_kecamatan').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#family_kecamatan').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/kecamatan')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#family_kelurahan').empty();
				$.each(data, function(index, subcatObj){
					$('#family_kelurahan').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#in_law_provinsi').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/provinsi')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#in_law_kabupaten').empty();
				$.each(data, function(index, subcatObj){
					$('#in_law_kabupaten').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#in_law_kabupaten').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/kabupaten')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#in_law_kecamatan').empty();
				$.each(data, function(index, subcatObj){
					$('#in_law_kecamatan').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#in_law_kecamatan').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/kecamatan')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#in_law_kelurahan').empty();
				$.each(data, function(index, subcatObj){
					$('#in_law_kelurahan').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#connection_provinsi').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/provinsi')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#connection_kabupaten').empty();
				$.each(data, function(index, subcatObj){
					$('#connection_kabupaten').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#connection_kabupaten').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/kabupaten')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#connection_kecamatan').empty();
				$.each(data, function(index, subcatObj){
					$('#connection_kecamatan').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#connection_kecamatan').on('change',function(e){
			//console.log(e);
			var id = e.target.value;
			$.get('{{url('/address/kecamatan')}}/' + id, function(data){
				//console.log(id);
				//console.log(data);
				$('#connection_kelurahan').empty();
				$.each(data, function(index, subcatObj){
					$('#connection_kelurahan').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
				});
			});
		});
	});
</script>

<script type="text/javascript">

  $("#uploadFile").change(function(){
     $('#image_preview').html("");
     var total_file=document.getElementById("uploadFile").files.length;
     for(var i=0;i<total_file;i++)
     {
      $('#image_preview').append("<img src='"+URL.createObjectURL(event.target.files[i])+"'>");
     }
  });
  $('form').ajaxForm(function() 
   {
    alert("Uploaded SuccessFully");
   }); 

</script>

<script type="text/javascript">
	$(document).ready(function(){
		$('#maritial').on('change',function(e){
		  var status = $('#maritial option:selected').val();
		  //alert(status);
		  var foo = document.getElementById('mertua');
		  var pret = document.getElementById('pasangan');
		  if(status == 1) {
			  foo.style.display = 'block';
			  pret.style.display = 'block';
		  } else {
			  foo.style.display = 'none';
			  pret.style.display = 'none';
		  }		  
		});
	});
</script>

@endsection