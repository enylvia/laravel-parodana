@extends('layouts.app')
@section('content')
<style>
.gallery-title
{
    font-size: 36px;
    color: #42B32F;
    text-align: center;
    font-weight: 500;
    margin-bottom: 70px;
}
.gallery-title:after {
    content: "";
    position: absolute;
    width: 7.5%;
    left: 46.5%;
    height: 45px;
    border-bottom: 1px solid #5e5e5e;
}
.filter-button
{
    font-size: 18px;
    border: 1px solid #42B32F;
    border-radius: 5px;
    text-align: center;
    color: #42B32F;
    margin-bottom: 30px;

}
.filter-button:hover
{
    font-size: 18px;
    border: 1px solid #42B32F;
    border-radius: 5px;
    text-align: center;
    color: #ffffff;
    background-color: #42B32F;

}
.btn-default:active .filter-button:active
{
    background-color: #42B32F;
    color: white;
}

.port-image
{
    width: 100%;
}

.gallery_product
{
    margin-bottom: 30px;
}
</style>
	<div class="container-fluid">
	@include('error.error-notification')
	@foreach($customers as $customer)
		<?php 
			$provinsi = App\Models\Provinsi::where('id',$customer->provinsi)->first();
			$kabupaten = App\Models\Kabupaten::where('id',$customer->kabupaten)->first();
			$kecamatan = App\Models\Kecamatan::where('id',$customer->kecamatan)->first();
			$kelurahan = App\Models\Kelurahan::where('id',$customer->kelurahan)->first();
			$religion = App\Models\Religion::where('id',$customer->religion)->first();
			$education = App\Models\Education::where('id',$customer->education)->first();
			$maritial = App\Models\Maritial::where('id',$customer->maritial)->first();
			$long = "";
			$lat  = "";
		?>
		<div class="row">
			<div class="col-md-3">
				<div class="box box-primary box-outline">
					<div class="box-body box-profile">
						<div class="text-center">
						  <img class="profile-user-img img-fluid img-circle" src="{{asset($customer->avatar!='' ?'uploads/photo/'.$customer->avatar:'uploads/photo/noimage.jpg')}}" alt="User profile picture">
						</div>

						<h3 class="profile-username text-center">{{$customer->name}}</h3>

						<p class="text-muted text-center"></p>

						<ul class="list-group list-group-unbordered mb-3">
							<li class="list-group-item">
								<b>Saldo</b> <a class="float-right">0</a>
							</li>
							<li class="list-group-item">
								<b>{{trans('survey.loan_to')}}</b> <a class="float-right">{{$customer->loan_to}}</a>
							</li>
							<li class="list-group-item">
								<b>{{trans('survey.time_period')}}</b> <a class="float-right">{{$customer->time_period}}</a>
							</li>
							<li class="list-group-item">
								<b>{{trans('survey.loan_amount')}}</b> <a class="float-right">Rp. {{ number_format($customer->loan_amount, 0, ',' , '.') }}</a>
							</li>
						</ul>

						<!--a href="#" class="btn btn-primary btn-block"><b>Follow</b></a-->
					</div>            
				</div>
				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title">About Me</h3>
					</div>
				  
					<div class="box-body">
						<strong><i class="fa fa-id-card mr-1"></i> {{trans('loan.card_number')}}</strong>
						<p class="text-muted">
						{{$customer->card_number}}
						</p>
						<strong><i class="fa fa-vcard-o mr-1"></i> {{trans('loan.family_card_number')}}</strong>
						<p class="text-muted">
						{{$customer->family_card_number}}
						</p>
						<strong><i class="fa fa-mortar-board mr-1"></i> {{trans('loan.education')}}</strong>
						<p class="text-muted">
						@if (empty($education->code))
							<p>Data not found</p>
						@else
						{{$education->code}} - {{$education->name}}
						@endif
						</p>
						<strong><i class="fa fa-envelope mr-1"></i> Email</strong>
						<p class="text-muted">
						{{$customer->email}}
						</p>
						<strong><i class="fa fa-intersex mr-1"></i> {{trans('loan.gender')}}</strong>
						<p class="text-muted">
						{{$customer->gender}}
						</p>
						<strong><i class="fa fa-female mr-1"></i> {{trans('loan.mother_maiden_name')}}</strong>
						<p class="text-muted">
						{{$customer->mother_maiden_name}}
						</p>
						<strong><i class="fa fa-home mr-1"></i> {{trans('loan.religion')}}</strong>
						<p class="text-muted">
						@if (empty($religion->name))
							<p>Data not found</p>
						@else
						{{$religion->name}}
						@endif
						</p>
						<strong><i class="fa fa-mobile mr-1"></i> {{trans('loan.mobile_phone')}}</strong>
						<p class="text-muted">
						{{$customer->mobile_phone}}
						</p>
						<strong><i class="fa fa-bell mr-1"></i> {{trans('loan.date_of_place')}}</strong>
						<p class="text-muted">
						{{$customer->birth_place}}, {{ date('l, d-m-y', strtotime($customer->date_of_birth))}}
						</p>
						<strong><i class="fa fa-map-marker mr-1"></i> Location</strong>
						<p class="text-muted">
						@if (empty($customer->address))
							<p>Data not found</p>
						@else
						{{$customer->address}}, {{$kelurahan->nama}} {{$customer->zip_code}}, {{$kecamatan->nama}} {{$kabupaten->nama}}, {{$provinsi->nama}}
						@endif
						</p>
					</div>
				</div>
			</div>
			
			<div class="col-md-9">
				<div class="box">
					<div class="box-header">
						<ul class="nav nav-pills">
							<li class="nav-item"><a class="nav-link active" href="#company_data" data-toggle="tab">{{trans('loan.company_data')}}</a></li>
							<li class="nav-item"><a class="nav-link" href="#maritial_status" data-toggle="tab">{{trans('loan.maritial_status')}}</a></li>
							<li class="nav-item"><a class="nav-link" href="#family_data" data-toggle="tab">{{trans('loan.family_data')}}</a></li>
							<li class="nav-item"><a class="nav-link" href="#survey" data-toggle="tab">Survey</a></li>
							<li class="nav-item"><a class="nav-link" href="#submission" data-toggle="tab">{{trans('loan.submission')}}</a></li>
							<li class="nav-item"><a class="nav-link" href="#financial_analysis" data-toggle="tab">{{trans('loan.financial_analysis')}}</a></li>
							<li class="nav-item"><a class="nav-link" href="#approve" data-toggle="tab">{{trans('loan.approve')}}</a></li>
						</ul>
					</div>
					<div class="box-body">
						<div class="tab-content">
							<div class="tab-pane active" id="company_data">
								<div class="box-header">
									<strong>{{trans('loan.company_data')}}</strong> 
									<small>{{trans('loan.form')}}</small>
								</div>
								<div class="box-body">
									<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
										<label for="postal-code">{{trans('loan.company_name')}}</label>									
										<input name="company_name" class="form-control" id="company_name" type="text" value="{{$customer->company_name}}">
									</div>				
									<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
										<label for="postal-code">{{trans('loan.department')}}</label>
										<input name="department" class="form-control" id="department" type="text" value="{{$customer->department}}">
									</div>					
									<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
										<label for="postal-code">{{trans('loan.position')}}</label>
										<input name="part" class="form-control" id="part" type="text" value="{{$customer->part}}">
									</div>					
									<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
										<label for="postal-code">{{trans('loan.kpk_number')}}</label>
										<input name="kpk_number" class="form-control" id="kpk_number" type="text" value="{{$customer->kpk_number}}">
									</div>					
									<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
										<label for="postal-code">{{trans('loan.personalia_name')}}</label>
										<input name="personalia_name" class="form-control" id="personalia_name" type="text" value="{{$customer->personalia_name}}">
									</div>			
								</div>
								
								<div class="box-header">
									<strong>{{trans('loan.income_per_month')}}</strong> 
									<small>{{trans('loan.form')}}</small>
								</div>
								<div class="box-body">	
									<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
										<label for="postal-code">{{trans('loan.net_salary')}}</label>
										<input name="net_salary" class="form-control" id="net_salary" type="text" value="Rp. {{ number_format($customer->net_salary, 0, ',' , '.') }}">
									</div>					
									<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
										<label for="postal-code">{{trans('loan.gros_salary')}}</label>
										<input name="gross_salary" class="form-control" id="gross_salary" type="text" value="Rp. {{ number_format($customer->gross_salary, 0, ',' , '.') }}">
									</div>								
									<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
										<label for="postal-code">{{trans('loan.payday_date')}}</label>
										<input name="payday_date" class="form-control" id="payday_date" type="text" value="{{$customer->payday_date}}">
									</div>									
									<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
										<label for="postal-code">{{trans('loan.bank_name')}}</label>
										<input name="bank_name" class="form-control" id="bank_name" type="text" value="{{$customer->bank_name}}">
									</div>								
									<!--div class="form-group col-sm-6">
										<label for="postal-code">{{trans('loan.bank_pin')}}</label>
										<input name="bank_pin" class="form-control" id="bank_pin" type="text" value="{{$customer->pin_number}}">
									</div-->														
								</div>
							</div>
							
							<div class="tab-pane" id="maritial_status" disabled>
								<div class="box-header">
									<strong>{{trans('loan.maritial_status')}}</strong> 
									<small>{{trans('loan.form')}}</small>
								</div>
								<div class="box-body">
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="roles">{{trans('loan.maritial_status')}}</label>
										@if (empty($maritial->name))
											<p>Data not found</p>
										@else
										<input name="maritial" class="form-control" id="maritial" type="text" value="{{$maritial->name}}">
										@endif
									</div>					
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.husband_wife')}}</label>
										<input name="husband_wife" class="form-control" id="husband_wife" type="text" value="{{$customer->husband_wife}}">
									</div>					
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.alias_husband_wife')}}</label>
										<input name="alias_husband_wife" class="form-control" id="alias_husband_wife" type="text" value="{{$customer->alias_husband_wife}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.husband_wife_profession')}}</label>
										<input name="husband_wife_profession" class="form-control" id="husband_wife_profession" type="text" value="{{$customer->husband_wife_profession}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.husband_wife_income')}}</label>
										<input name="husband_wife_income" class="form-control" id="husband_wife_income" type="text" value="Rp. {{ number_format($customer->husband_wife_income, 0, ',' , '.') }}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.husband_wife_phone')}}</label>
										<input name="husband_wife_phone" class="form-control" id="husband_wife_phone" type="text" value="{{$customer->husband_wife_phone}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.husband_wife_address')}}</label>
										<input name="husband_wife_address" class="form-control" id="husband_wife_address" type="text" value="{{$customer->husband_wife_address}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="city">{{trans('loan.husband_wife_home_status')}}</label>										
										<input type="text" class="form-control" name="husband_wife_home_status" value="{{$customer->husband_wife_home_status}}">											
									</div>
								</div>						
							</div>
							
							<div class="tab-pane" id="family_data">
								<div class="box-header">
									<strong>{{trans('loan.family_data')}}</strong> 
									<small>{{trans('loan.form')}}</small>
								</div>
								<div class="box-body">
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.family_father')}}</label>
										<input name="family_father" class="form-control" id="family_father" type="text" value="{{$customer->family_father}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.family_mother')}}</label>
										<input name="family_mother" class="form-control" id="family_mother" type="text" value="{{$customer->family_mother}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.family_phone')}}</label>
										<input name="family_phone" class="form-control" id="family_phone" type="text" value="{{$customer->family_phone}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.family_address')}}</label>
										<input name="family_address" class="form-control" id="family_address" type="text" value="{{$customer->family_address}}">
									</div>				
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.in_law_father')}}</label>
										<input name="in_law_father" class="form-control" id="in_law_father" type="text" value="{{$customer->in_law_father}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.in_law_mother')}}</label>
										<input name="in_law_mother" class="form-control" id="in_law_mother" type="text" placeholder="{{$customer->in_law_mother}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.in_law_phone')}}</label>
										<input name="in_law_phone" class="form-control" id="in_law_phone" type="text" value="{{$customer->in_law_phone}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.in_law_address')}}</label>
										<input name="in_law_address" class="form-control" id="in_law_address" type="text" value="{{$customer->in_law_address}}">
									</div>
									
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<p>Dalam keadaan darurat, anggota keluarga yang bisa di hubungi :</p>
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.connection_name')}}</label>
										<input name="connection_name" class="form-control" id="connection_name" type="text" value="{{$customer->connection_name}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.connection_alias_name')}}</label>
										<input name="connection_alias_name" class="form-control" id="connection_alias_name" type="text" value="{{$customer->connection_alias_name}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.connection_phone')}}</label>
										<input name="connection_phone" class="form-control" id="connection_phone" type="text" value="{{$customer->connection_phone}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="postal-code">{{trans('loan.connection_address')}}</label>
										<input name="connection_address" class="form-control" id="connection_address" type="text" value="{{$customer->connection_address}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="city">{{trans('loan.family_connection')}}</label>
										<input class="form-control" id="family_connection" type="text" value="{{$customer->family_connection}}">
									</div>				
								</div>
							</div>
							
							<div class="tab-pane" id="submission">
								<div class="box-header">
									<strong>{{trans('loan.submission')}}</strong> 
									<small>{{trans('loan.form')}}</small>
								</div>
								<div class="box-body">
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label>{{trans('survey.loan_amount')}}</label>
										<input class="form-control" value="Rp. {{ number_format($customer->loan_amount, 0, ',' , '.') }}" name="loan_amount" type="text" id="loan_amount">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="city">{{trans('survey.loan_to')}}</label>
										<input class="form-control" value="{{$customer->loan_to}}" name="loan_to" type="text" id="loan_to">									
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label>{{trans('survey.time_period')}}</label> <span>*/Bulan</span>
										<input class="form-control" value="{{$customer->time_period}}" name="time_period" type="text" id="time_period">
									</div>
									<!--div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label>{{trans('survey.installments_month')}}</label>
										<input class="form-control" placeholder="Rp. 0" name="installments_month" type="text" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
									</div-->
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="city">{{trans('survey.necessity_for')}}</label>
										<input type="text" class="form-control" value="{{$customer->necessity_for}}" name="necessity_for" required>											
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label>{{trans('survey.survey_plan')}}</label>
										<input type="text" value="{{ date('l, d-m-y', strtotime($customer->survey_plan))}}" name="survey_plan" class="form-control">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label>{{trans('survey.surveyor_name')}}</label>
										<input type="text" id="surveyor_name" name="surveyor_name" class="form-control" value="{{$customer->surveyor_name}}">
									</div>
									<!--div class="form-group col-sm-4">
										<label>{{trans('survey.reason')}}</label>
										<input type="text" id="reason" name="reason" class="form-control">
									</div-->																		
									<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<?php
										$rates = App\Models\InterestRate::where('id','=',3)->first();										
										$plafon = $customer->loan_amount;
										$tempo = $customer->time_period;
										$bunga_pertahun = $rates->rate;
										$bunga_perbulan = 0;
										$angsuran_pokok = 0;
										$angsuran_bunga = 0;
										$no=1;
										if(!empty($bunga_perbulan) ? $bunga_pertahun/12 : '');
										if(!empty($bunga_rp) ? $plafon/$bunga_pertahun : 0);
										if(!empty($angsuran_bunga) ? $plafon*$bunga_perbulan/100 : 0);
										if(!empty($angsuran_pokok) ? $plafon/$tempo : 0);										
										if(!empty($total_angsuran) ? $angsuran_pokok+$angsuran_bunga : 0);							
										
									?>
										<div class="table-responsive">
											<table class="table table-responsive-sm table-striped">
												<thead>
												<tr>
													<th>Angsuran ke</th>
													<th>Angsuran Pokok</th>
													<th>Angsuran Bunga</th>
													<th>Total Angsuran</th>
													<th>Sisa Pokok</th>
												</tr>
												</thead>
												<tbody>																
													<tr>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td>Rp. {{ number_format($customer->loan_amount, 0, ',' , '.') }}</td>
													</tr>
													@for($i = 1; $i <= $tempo; $i++)
														<tr>
															<?php 
															$sisa_pokok = $plafon - ($angsuran_pokok * $i);
															?>
															<td align="center">{{$no++}}</td>
															<td align="right">Rp. {{ number_format($angsuran_pokok, 0, ',' , '.') }}</td>
															<td align="right">Rp. {{ number_format($angsuran_bunga, 0, ',' , '.') }}</td>
															<td align="right">Rp. {{ !empty($total_angsuran) ? number_format($total_angsuran, 0, ',' , '.') : '' }}</td>
															<td align="right">Rp. {{ number_format($sisa_pokok, 0, ',' , '.') }}</td>
														</tr>
													@endfor
												</tbody>
											</table>
										</div>
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<iframe frameborder="0" height="400" scrolling="no" src="//maps.google.com/maps?q={{$customer->address}}&amp;num=1&amp;t=m&amp;ie=UTF8&amp;z=14&amp;output=embed" width="650">
										</iframe>									
									</div>
								</div>
							</div>
							<div class="tab-pane" id="survey">
								<div class="box-header">
									<strong>Survey</strong> 
									<small>{{trans('loan.form')}}</small>
								</div>
								<div class="box-body">
									<?php 
										$surveys = App\Models\CustomerSurvey::where('customer_id',$customer->id)->first();
									?>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label>{{trans('survey.environment_condition')}}</label>
										<input type="text" id="environment_condition" name="environment_condition" class="form-control" value="{{$surveys->environment_condition}}">																			
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label>{{trans('survey.viability')}}</label>
										<input type="text" id="viability" name="viability" class="form-control" value="{{$surveys->viability}}">
									</div>	
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label>{{trans('survey.other_income')}}</label>
										<input type="text" name="other_income" class="form-control" value="{{$surveys->other_income}}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label>{{trans('survey.child_fee')}}</label>
										<input type="text" name="child_fee" class="form-control" value="{{ $surveys->child_fee }}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label>{{trans('survey.electricity_cost')}}</label>
										<input type="text" name="electricity_cost" class="form-control" value="{{ $surveys->electricity_cost }}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label>{{trans('survey.water_cost')}}</label>
										<input type="text" name="water_cost" class="form-control" value="{{ $surveys->water_cost }}">
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label>{{trans('survey.other_installment')}}</label>
										<input type="text" name="other_installment" class="form-control" value="{{ $surveys->other_installment }}">
									</div>
									<div class="form-group col-md-12 col-lg-12">
										<label>{{trans('survey.note')}}</label>									
										<textarea id="note" name="note">{{ $surveys->note }}</textarea>
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<iframe frameborder="0" height="400" scrolling="no" src="//maps.google.com/maps?q={{$customer->address}},
											&amp{{ !empty($kelurahan->nama) ? '' : '$kelurahan->nama' }},
											&amp{{ !empty($kecamatan->nama) ? '' : '$kecamatan->nama' }},
											&amp{{ !empty($kabupaten->nama) ? '' : '$kabupaten->nama' }},
											&amp{{ !empty($provinsi->nama)  ? '' : '$kecamatan->nama' }}&amp;
											num=1&amp;t=m&amp;ie=UTF8&amp;z=14&amp;output=embed" width="100%">
										</iframe>
									</div>
								</div>
							</div>
							
							<div class="tab-pane" id="financial_analysis">
								<div class="box-header">
									<strong>{{trans('loan.financial_analysis')}}</strong> 
									<small>{{trans('loan.form')}}</small>
								</div>
								<div class="box-body">
									<table class="table table-responsive-sm table-striped">
										<thead>
											<tr>
												<th>PENDAPATAN</th>
												<th>JUMLAH</th>
												<th>PENGELUARAN</th>
												<th>JUMLAH</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Gaji Bersih</td>
												<td align="right">Rp. {{ number_format($customer->net_salary, 0, ',' , '.') }}</td>
												<td>Biaya Anak</td>
												@if(!$surveys)
													<td>Data Not found</td>
												@else
												<td align="right"> Rp. {{ number_format($surveys->child_fee, 0, ',' , '.') }}</td>
												@endif
											</tr>
											<tr>
												<td>Gaji Kotor</td>
												<td align="right">Rp. {{ number_format($customer->gross_salary, 0, ',' , '.') }}</td>
												<td>Biaya Listrik</td>
												@if(!$surveys)
													<td>Data Not found</td>
												@else
												<td align="right">Rp. {{ number_format($surveys->electricity_cost, 0, ',' , '.') }}</td>
												@endif
											</tr>
											<tr>
												<td>Pendapatan Istri/Suami</td>
												<td align="right">Rp. {{ number_format($customer->husband_wife_income, 0, ',' , '.') }}</td>
												<td>Biaya Air</td>
												@if(!$surveys)
													<td>Data Not found</td>
												@else
												<td align="right">Rp. {{ number_format($surveys->water_cost, 0, ',' , '.') }}</td>
												@endif
											</tr>
											<tr>
												<td>Pendapatan Lain</td>
												<td align="right">Rp. {{ !empty($surveys->other_income) ? number_format($surveys->other_income, 0, ',' , '.') : '0' }}</td>
												@if(!$surveys)
													<td>Data Not found</td>
												@endif
												<td>Angsuran Lain</td>
												<td align="right">Rp. {{ number_format($surveys->other_installment, 0, ',' , '.') }}</td>
											</tr>
											<tr>
												<td>

												</td>
												<td></td>
												<td align="right">Biaya Hidup</td>												
												<td align="right">Rp. {{ !empty($surveys->cost_of_living) ? number_format($surveys->cost_of_living, 0, ',' , '.') : '0' }}</td>
											</tr>
										</tbody>
										<tfoot>
											<?php 
												if(!$surveys)
												{
													$pendapatan = 0;
													$pengeluaran = 0;
													$total = $pendapatan - $pengeluaran;
												}else{
													$pendapatan = $customer->net_salary + $customer->husband_wife_income + $surveys->other_income;
													$pengeluaran = $surveys->child_fee + $surveys->electricity_cost + $surveys->water_cost + $surveys->other_installment+$surveys->cost_of_living;
													$total = $pendapatan - $pengeluaran;
												}
											?>
											<tr>
												<td align="right" colspan="2" style="font-weight: bold;">Total Pendapatan</td> <td colspan="2" align="right" style="font-weight: bold;">Rp. {{ number_format($pendapatan, 0, ',' , '.') }}</td>
											</tr>
											<tr>
												<td align="right" colspan="2" style="font-weight: bold;">Total Pengeluaran</td> <td colspan="2" align="right" style="font-weight: bold;">Rp. {{ number_format($pengeluaran, 0, ',' , '.') }}</td>
											</tr>
											<tr>
												<td align="right" colspan="2" style="font-weight: bold;">Total </td> <td colspan="2" align="right" style="font-weight: bold;">Rp. {{ number_format($total, 0, ',' , '.') }}</td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
							
							<div class="tab-pane" id="approve">
								<div class="box-header">
									<strong>{{trans('loan.approve')}}</strong> 
									<small>{{trans('loan.form')}}</small>
								</div>
								<form method="post" action="{{route('approve.store')}}" enctype="multipart/form-data">
								{{ csrf_field() }}
								<div class="box-body">
									<div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
										<label for="kecamatan">{{trans('loan.interest_rate')}}</label>
										<!--input type="text" class="form-control" name="interest_rate" placeholder="% /Month"-->
										<?php 
											$rates = App\Models\InterestRate::all();
										?>
										<select class="form-control" id="interest_rate" name="interest_rate" required>
											<option value="0">Please select</option>
											@foreach($rates as $rate)
												<option value="{{$rate->rate}}">{{$rate->name}} - {{$rate->rate}}</option>
											@endforeach
										</select>
									</div>									
									<div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
										<label for="kecamatan">{{trans('loan.time_period')}}</label>
										<input type="number" class="form-control" name="time_period" placeholder="0 /Month" id="times_period" required>
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
										<label for="kecamatan">{{trans('loan.approve_amount')}}</label>
										<input type="hidden" class="form-control" name="customer_id" value="{{$customer->id}}">
										<input type="hidden" class="form-control" name="reg_number" value="{{$customer->reg_number}}">
										<input type="text" class="form-control" name="approve_amount" placeholder="Rp. 0" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
										<label for="kecamatan">{{trans('loan.installments_month')}}</label>
										<input type="text" class="form-control" name="installment" placeholder="Rp. 0 /Month" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
									</div>									
									<div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
										<label for="tab_wajib">Tabungan Wajib</label>
										<input type="text" class="form-control" name="m_saving" placeholder="Rp. 0 /Month" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
									</div>									
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<label for="city">{{trans('loan.approve')}}</label>
										<select class="form-control" id="approve" name="approve" required>
											<option value="0">Please select</option>
											<option value="1">Approve</option>
											<option value="2">UnApprove</option>
										</select>
									</div>
								</div>
								<div class="box-footer">
									<button type="submit" class="btn btn-primary">{{trans('general.submit')}}</button>
								</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- <div class="box">
					<div class="box-header">
						<div align="center">
							<button class="btn btn-default filter-button" data-filter="all">All</button>
							<button class="btn btn-default filter-button" data-filter="home">Home</button>
							<button class="btn btn-default filter-button" data-filter="identity">Identity</button>
							<button class="btn btn-default filter-button" data-filter="letter">Letter</button>
							<button class="btn btn-default filter-button" data-filter="image">Image</button>
						</div>
					</div>
					<div class="box-body">
					@foreach($documents as $document)
						<div class="gallery_product col-lg-4 col-md-4 col-sm-12 col-xs-12 filter {{$document->document_category}}">
							<a id="View" data-target="#View-{{$document->id}}" data-toggle="modal" class="btn btn-default">
								<img src="{{asset('uploads/documents/' .$document->document_file)}}" class="img-responsive">
							</a>
						</div>
					@endforeach						
					</div>
				</div> -->
			</div>				
		</div>						
	@endforeach
	</div>
	
	@foreach($documents as $document)
	<div id="View-{{$document->id}}" class="modal fade" aria-labelledby="my-modalLabel" aria-hidden="true" tabindex="-1" role="dialog">
		<div class="modal-dialog" data-dismiss="modal">
			<div class="modal-content"  >              
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<a id="View" data-target="#View-{{$document->id}}" data-toggle="modal" class="btn btn-default">
						<img src="{{asset('uploads/documents/' .$document->document_file)}}" class="img-responsive" style="width: 100%;">
					</a>
				</div> 
			</div>
		</div>
	</div>
	@endforeach
	
@endsection

@section('js')

<script type="text/javascript">						    
	CKEDITOR.replace('note', {
		"filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
	});						    
</script>

<script>
	$(document).ready(function(){

		$(".filter-button").click(function(){
			var value = $(this).attr('data-filter');
			
			if(value == "all")
			{
				//$('.filter').removeClass('hidden');
				$('.filter').show('1000');
			}
			else
			{
	//            $('.filter[filter-item="'+value+'"]').removeClass('hidden');
	//            $(".filter").not('.filter[filter-item="'+value+'"]').addClass('hidden');
				$(".filter").not('.'+value).hide('3000');
				$('.filter').filter('.'+value).show('3000');
				
			}
		});
		
		if ($(".filter-button").removeClass("active")) {
	$(this).removeClass("active");
	}
	$(this).addClass("active");

	});
</script>
<script type="text/javascript">
 window.onload = function(){
	$("input[name=installment]").click(function () {
		var ambilBunga = $('#interest_rate option:selected').val();	 
		var ambilTenor = document.getElementById('times_period').value;
		var ambilPlafon = $("input[name=approve_amount]").val();		
		var plafon = ambilPlafon.split('.').join('');		
		var bunga_perbulan = ambilBunga / 12;		
		var bunga_rp = plafon / ambilBunga;		
		var angsuran_bunga = plafon * bunga_perbulan / 100;		
		var angsuran_pokok =  plafon / ambilTenor;
		var total_angsuran = angsuran_pokok + angsuran_bunga;
		var angsuran = Math.ceil(total_angsuran / 1000) * 1000;
		var	number_string = angsuran.toString(),
			sisa 	= number_string.length % 3,
			rupiah 	= number_string.substr(0, sisa),
			ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
				
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		$("input[name=installment]").val(rupiah);
     //$("#customer_name").val(ambilNama);
	 //$("#customer_address").val(ambilAlamat);
   });
}	
</script>

@endsection