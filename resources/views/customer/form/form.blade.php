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

@if ($errors->has('card_number'))
    <div class="alert alert-danger py-3" role="alert">
        <strong>Error!, Nomor KTP anda sudah terdaftar pada system kami.</strong>
    </div>
@endif

@if(session('success'))
	<div class="alert alert-success py-3">
		{{ session('success') }}
	</div>
@endif
	
		<div class="box">		
			<?php          
				//$countries = App\Models\Country::all();
				$provinsis = App\Models\Provinsi::all();
				//dd($countries);
				$customers = App\Models\Customer::orderBy('id','DESC')->get();
				//$custIds = App\Models\CustomerCompany::orderBy('customer_id','DESC')->get();
				//$long = "";
				//$lat  = "";
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
			
			<form method="post" action="{{route('customer-form.store')}}" enctype="multipart/form-data">
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
									<input name="name" class="form-control" id="company" type="text" placeholder="Enter your name" required>
								</div>
								<div class="form-group col-sm-4">
									<label for="vat">{{trans('loan.mobile_phone')}}</label>
									<input name="mobile_phone" class="form-control" id="mobile_phone" type="text" placeholder="Mobile Phone" required>
								</div>
								<div class="form-group col-sm-4">
									<label for="company">{{trans('loan.birth_place')}}</label>
									<input name="birth_place" class="form-control" id="birth_place" type="text" placeholder="Enter your birth place" required>
								</div>
								<div class="form-group col-sm-4">
									<label for="company">{{trans('loan.date_of_place')}}</label>
									<input name="date_birth" class="form-control" id="date_place" type="date" required>
								</div>							
								<div class="form-group col-sm-4">
									<label for="vat">{{trans('loan.family_card_number')}}</label>
									<input name="family_card_number" class="form-control" id="family_card_number" type="text" placeholder="Family Card Number" required>
								</div>
								<div class="form-group col-sm-4">
									<label for="vat">{{trans('loan.card_number')}}</label>
									<input name="card_number" class="form-control" id="card_number" type="text" placeholder="Card Number" required>
								</div>		
								<div class="form-group col-sm-4">
									<label for="vat">{{trans('loan.mother_maiden_name')}}</label>
									<input name="mother_maiden_name" class="form-control" id="mother_maiden_name" type="text" placeholder="Mother Maiden Name" required>
								</div>
								<div class="form-group col-sm-4">
									<label for="city">{{trans('loan.gender')}}</label>
									<select class="form-control" id="gender" name="gender" required>
										<option value="0">Please select</option>
										<option value="1">Laki-laki</option>
										<option value="2">Perempuan</option>
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="provinsi">{{trans('loan.province')}}</label>
									<select class="input select2 select2-hidden-accessible" onchange="provinsi" style="width: 100%;" aria-hidden="true" name="provinsi" id="provinsi" required>
										<option value="">=== Pilih Provinsi ===</option>
										@foreach($provinsis as $provinsi)
										  <option value="{{$provinsi->id}}">{{ $provinsi->nama }}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kabupaten">{{trans('loan.regency')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="kabupaten" id="kabupaten" required>
										<option value="">=== Pilih Kabupaten ===</option>
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kecamatan">{{trans('loan.districts')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="kecamatan" id="kecamatan" required>
										<option value="">=== Pilih Kecamatan ===</option>	
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kelurahan">{{trans('loan.vilage')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="kelurahan" id="kelurahan" required>
										<option value="">=== Pilih Kelurahan ===</option>	
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label for="roles">{{trans('loan.education')}}</label>								
									<select name="education" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" required>
										<option value="">-- Education --</option>
										@foreach($educations as $education)
											<option value="{{ $education->id }}">{{ $education->code }} - {{ $education->name }}</option>
										@endforeach
									</select>
								</div>					
								<div class="form-group col-sm-4">
									<label for="roles">{{trans('loan.religion')}}</label>								
									<select name="religion" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" required>
										<option value="">-- Religion --</option>
										@foreach($religions as $religion)
											<option value="{{ $religion->id }}">{{ $religion->name }}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group col-sm-8">
									<label for="street">{{trans('loan.address')}}</label>
									<input name="address" class="form-control" id="street" type="text" placeholder="Enter street name" required>
								</div>
								<div class="form-group col-sm-4">
									<label for="postal-code">{{trans('loan.postal_code')}}</label>
									<input name="zip_code" class="form-control" id="zip_code" type="text" placeholder="Postal Code">
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
									<input name="company_name" class="form-control" id="company_name" type="text" placeholder="Company Name">
								</div>				
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.department')}}</label>
									<input name="department" class="form-control" id="department" type="text" placeholder="Department">
								</div>					
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.part')}}</label>
									<input name="part" class="form-control" id="part" type="text" placeholder="Part">
								</div>					
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.kpk_number')}}</label>
									<input name="kpk_number" class="form-control" id="kpk_number" type="text" placeholder="No. KPK">
								</div>					
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.personalia_name')}}</label>
									<input name="personalia_name" class="form-control" id="personalia_name" type="text" placeholder="Personalia Name / HRD">
								</div>			
							</div>
							
							<div class="box-header">
								<strong>{{trans('loan.income_per_month')}}</strong> 
								<small>{{trans('loan.form')}}</small>
							</div>
							<div class="box-body">
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.net_salary')}}</label>
									<input name="net_salary" class="form-control" type="text" placeholder="Net Salary" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
								</div>					
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.gros_salary')}}</label>
									<input name="gross_salary" class="form-control" type="text" placeholder="Gross Salary" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
								</div>								
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.payday_date')}}</label>
									<!--input name="payday_date" class="form-control" id="payday_date" type="number" placeholder="Payday Date"-->
									<input type="text" name="payday_date" class="form-control" placeholder="pisahkan dengan ',' ">
								</div>									
								<div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.bank_name')}}</label>
									<input name="bank_name" class="form-control" id="bank_name" type="text" placeholder="Bank Name">
								</div>								
								<div class="form-group col-sm-6">
									<label for="bank_number">Nomor Rekening</label>
									<input name="bank_number" class="form-control" id="bank_number" type="text" placeholder="No. Rekening">
								</div>								
								<div class="form-group col-sm-6">
									<label for="bank_pin">Bank Pin</label>
									<input name="bank_pin" class="form-control" id="bank_pin" type="text" placeholder="Bank Pin">
								</div>								
								<!--div class="form-group col-sm-6">
									<label for="postal-code">{{trans('loan.bank_pin')}}</label>
									<input name="bank_pin" class="form-control" id="bank_pin" type="text" placeholder="Pin Number">
								</div-->														
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
										@foreach($maritials as $maritial)
											<option value="{{ $maritial->id }}">{{ $maritial->name }}</option>
										@endforeach
									</select>
								</div>			
								<div id="pasangan">
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.husband_wife')}}</label>
										<input name="husband_wife" class="form-control" id="husband_wife" type="text" placeholder="Husband / Wife">
									</div>					
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.alias_husband_wife')}}</label>
										<input name="alias_husband_wife" class="form-control" id="alias_husband_wife" type="text" placeholder="Alias Husband / Wife Name">
									</div>
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.husband_wife_profession')}}</label>
										<input name="husband_wife_profession" class="form-control" id="husband_wife_profession" type="text" placeholder="Husband / Wife Profession">
									</div>
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.husband_wife_income')}}</label>
										<input name="husband_wife_income" class="form-control" type="text" placeholder="Husband / Wife Income" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
									</div>
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.husband_wife_phone')}}</label>
										<input name="husband_wife_phone" class="form-control" id="husband_wife_phone" type="text" placeholder="Husband / Wife Phone">
									</div>
									<div class="form-group col-sm-12">
										<label for="postal-code">{{trans('loan.husband_wife_address')}}</label>
										<input name="husband_wife_address" class="form-control" id="husband_wife_address" type="text" placeholder="Husband / Wife Address">
									</div>
								</div>
								<!--div class="form-group col-sm-3">
									<label for="provinsi">{{trans('loan.province')}}</label>
									<select class="input select2 select2-hidden-accessible" onchange="husband_wife_provinsi" style="width: 100%;" aria-hidden="true" name="husband_wife_provinsi" id="husband_wife_provinsi" required>
										<option value="">=== Pilih Provinsi ===</option>
										@foreach($provinsis as $provinsi)
										  <option value="{{$provinsi->id}}">{{ $provinsi->nama }}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kabupaten">{{trans('loan.regency')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="husband_wife_kabupaten" id="husband_wife_kabupaten" required>
										<option value="">=== Pilih Kabupaten ===</option>
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kecamatan">{{trans('loan.districts')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="husband_wife_kecamatan" id="husband_wife_kecamatan" required>
										<option value="">=== Pilih Kecamatan ===</option>	
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kelurahan">{{trans('loan.vilage')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="husband_wife_kelurahan" id="husband_wife_kelurahan" required>
										<option value="">=== Pilih Kelurahan ===</option>	
									</select>
								</div-->								
								<div class="form-group col-sm-4">
									<label for="city">{{trans('loan.husband_wife_home_status')}}</label>
									<!--input class="form-control" id="city" type="text" placeholder="Enter your city"-->
									<select class="form-control" id="husband_wife_home_status" name="husband_wife_home_status">
										<option value="0">Please select</option>
										<option value="Milik Sendiri (tidak dijaminkan)">Milik Sendiri (tidak dijaminkan)</option>
										<option value="Milik Sendiri (dijaminkan)">Milik Sendiri (dijaminkan)</option>
										<option value="Milik Keluarga">Milik Keluarga</option>
										<option value="Kontrak / Kost">Kontrak / Kost</option>
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
									<input name="family_father" class="form-control" id="family_father" type="text" placeholder="Father Name">
								</div>
								<div class="form-group col-sm-4">
									<label for="postal-code">{{trans('loan.family_mother')}}</label>
									<input name="family_mother" class="form-control" id="family_mother" type="text" placeholder="Mother Name">
								</div>
								<div class="form-group col-sm-4">
									<label for="postal-code">{{trans('loan.family_phone')}}</label>
									<input name="family_phone" class="form-control" id="family_phone" type="text" placeholder="Phone">
								</div>
								<!--div class="form-group col-sm-3">
									<label for="provinsi">{{trans('loan.province')}}</label>
									<select class="input select2 select2-hidden-accessible" onchange="family_provinsi" style="width: 100%;" aria-hidden="true" name="family_provinsi" id="family_provinsi" required>
										<option value="">=== Pilih Provinsi ===</option>
										@foreach($provinsis as $provinsi)
										  <option value="{{$provinsi->id}}">{{ $provinsi->nama }}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kabupaten">{{trans('loan.regency')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="family_kabupaten" id="family_kabupaten" required>
										<option value="">=== Pilih Kabupaten ===</option>
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kecamatan">{{trans('loan.districts')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="family_kecamatan" id="family_kecamatan" required>
										<option value="">=== Pilih Kecamatan ===</option>	
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kelurahan">{{trans('loan.vilage')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="family_kelurahan" id="family_kelurahan" required>
										<option value="">=== Pilih Kelurahan ===</option>	
									</select>
								</div-->
								<div class="form-group col-sm-12">
									<label for="postal-code">{{trans('loan.family_address')}}</label>
									<input name="family_address" class="form-control" id="family_address" type="text" placeholder="Family Address">
								</div>				
								<div id="mertua" style="">
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.in_law_father')}}</label>
										<input name="in_law_father" class="form-control" id="in_law_father" type="text" placeholder="Father Name">
									</div>
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.in_law_mother')}}</label>
										<input name="in_law_mother" class="form-control" id="in_law_mother" type="text" placeholder="Mother Name">
									</div>
									<div class="form-group col-sm-4">
										<label for="postal-code">{{trans('loan.in_law_phone')}}</label>
										<input name="in_law_phone" class="form-control" id="in_law_phone" type="text" placeholder="Phone">
									</div>
									<!--div class="form-group col-sm-3">
										<label for="provinsi">{{trans('loan.province')}}</label>
										<select class="input select2 select2-hidden-accessible" onchange="in_law_provinsi" style="width: 100%;" aria-hidden="true" name="in_law_provinsi" id="in_law_provinsi" required>
											<option value="">=== Pilih Provinsi ===</option>
											@foreach($provinsis as $provinsi)
											  <option value="{{$provinsi->id}}">{{ $provinsi->nama }}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group col-sm-3">
										<label for="kabupaten">{{trans('loan.regency')}}</label>
										<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="in_law_kabupaten" id="in_law_kabupaten" required>
											<option value="">=== Pilih Kabupaten ===</option>
										</select>
									</div>
									<div class="form-group col-sm-3">
										<label for="kecamatan">{{trans('loan.districts')}}</label>
										<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="in_law_kecamatan" id="in_law_kecamatan" required>
											<option value="">=== Pilih Kecamatan ===</option>	
										</select>
									</div>
									<div class="form-group col-sm-3">
										<label for="kelurahan">{{trans('loan.vilage')}}</label>
										<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="in_law_kelurahan" id="in_law_kelurahan" required>
											<option value="">=== Pilih Kelurahan ===</option>	
										</select>
									</div-->
									<div class="form-group col-sm-12">
										<label for="postal-code">{{trans('loan.in_law_address')}}</label>
										<input name="in_law_address" class="form-control" id="in_law_address" type="text" placeholder="Address">
									</div>
								</div>
								
								<div class="form-group col-sm-12">
									<p>Dalam keadaan darurat, anggota keluarga yang bisa di hubungi :</p>
								</div>
								<div class="form-group col-sm-4">
									<label for="postal-code">{{trans('loan.connection_name')}}</label>
									<input name="connection_name" class="form-control" id="connection_name" type="text" placeholder="Name">
								</div>
								<div class="form-group col-sm-4">
									<label for="postal-code">{{trans('loan.connection_alias_name')}}</label>
									<input name="connection_alias_name" class="form-control" id="connection_alias_name" type="text" placeholder="Alias Name">
								</div>
								<div class="form-group col-sm-4">
									<label for="postal-code">{{trans('loan.connection_phone')}}</label>
									<input name="connection_phone" class="form-control" id="connection_phone" type="text" placeholder="Phone">
								</div>
								<!--div class="form-group col-sm-3">
									<label for="provinsi">{{trans('loan.province')}}</label>
									<select class="input select2 select2-hidden-accessible" onchange="connection_provinsi" style="width: 100%;" aria-hidden="true" name="connection_provinsi" id="connection_provinsi" required>
										<option value="">=== Pilih Provinsi ===</option>
										@foreach($provinsis as $provinsi)
										  <option value="{{$provinsi->id}}">{{ $provinsi->nama }}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kabupaten">{{trans('loan.regency')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="connection_kabupaten" id="connection_kabupaten" required>
										<option value="">=== Pilih Kabupaten ===</option>
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kecamatan">{{trans('loan.districts')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="connection_kecamatan" id="connection_kecamatan" required>
										<option value="">=== Pilih Kecamatan ===</option>	
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="kelurahan">{{trans('loan.vilage')}}</label>
									<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="connection_kelurahan" id="connection_kelurahan" required>
										<option value="">=== Pilih Kelurahan ===</option>	
									</select>
								</div-->
								<div class="form-group col-sm-12">
									<label for="postal-code">{{trans('loan.connection_address')}}</label>
									<input name="connection_address" class="form-control" id="connection_address" type="text" placeholder="Address">
								</div>
								<div class="form-group col-sm-4">
									<label for="city">{{trans('loan.family_connection')}}</label>
									<select class="form-control" id="family_connection" name="family_connection">
										<option value="0">Please select</option>
										<option value="Orang Tua">Orang Tua</option>
										<option value="Saudara">Saudara</option>
										<option value="Sahabat">Sahabat</option>
										<option value="Rekan Kerja">Rekan Kerja</option>
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
									<input class="form-control" placeholder="Rp. 0" name="loan_amount" type="text" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
								</div>
								<div class="form-group col-sm-4">
									<label for="city">{{trans('survey.loan_to')}}</label>
									<select class="form-control" id="loan_to" name="loan_to" required>
										<option value="0">Please select</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label>{{trans('survey.time_period')}}</label> <span>*/Bulan</span>
									<select class="form-control" id="time_period" name="time_period" required>
										<option value="0">Please select</option>
										<option value="1">1 Bulan</option>
										<option value="3">3 Bulan</option>
										<option value="6">6 Bulan</option>
										<option value="9">9 Bulan</option>
										<option value="12">12 Bulan</option>
										<option value="15">15 Bulan</option>
										<option value="18">18 Bulan</option>
										<option value="21">21 Bulan</option>
										<option value="24">24 Bulan</option>
										<option value="27">27 Bulan</option>
										<option value="30">30 Bulan</option>
										<option value="33">33 Bulan</option>
										<option value="36">36 Bulan</option>
									</select>
								</div>
								<!--div class="form-group col-sm-4">
									<label>{{trans('survey.installments_month')}}</label>
									<input class="form-control" placeholder="Rp. 0" name="installments_month" type="text" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
								</div-->
								<div class="form-group col-sm-4">
									<label for="city">{{trans('survey.necessity_for')}}</label>
									<select class="form-control" id="necessity_for" name="necessity_for" required>
										<option value="0">Please select</option>
										<option value="Renovasi Rumah">Renovasi Rumah</option>
										<option value="Modal Usaha">Modal Usaha</option>
										<option value="Biaya Sekolah">Biaya Sekolah</option>
										<option value="Lainnya">Lainnya</option>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label>{{trans('survey.survey_plan')}}</label>
									<input type="date" id="survey_plan" name="survey_plan" class="form-control">
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
								
								<!--div class="form-group col-md-12">
									<label>{{trans('survey.note')}}</label>
									<input type="text" id="reason" name="reason" class="form-control">
									<textarea id="reason" name="reason"></textarea>
								</div-->
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
												 src="{{ asset('/uploads/noimage.jpg') }}"
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
				<button type="submit" class="btn btn-primary">{{trans('general.submit')}}</button>
			</div>
			</form>
						
		</div>	
	
@endsection

@section('js')
<script>
$(document).ready(function () {
    $("#date_birth").datepicker({ dateFormat: "dd/mm/yyyy" });
});
</script>
<script type="text/javascript">						    
	CKEDITOR.replace('reason', {
		"filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
	});						    
</script>

<!-- <script>
	//redirect to specific tab
	$(document).ready(function () {
	$('#tabMenu a[href="#{{ old('tab') }}"]').tab('show')
	});
</script> -->

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