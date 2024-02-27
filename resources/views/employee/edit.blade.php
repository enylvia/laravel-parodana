@extends('layouts.app')
@section('content')

	<div class="box">
		@foreach($users as $user)
		<form method="post" action="{{URL::to('/employee/update', $user->id)}}" enctype="multipart/form-data">
		{{ csrf_field() }}
			<?php          
			  //$countries = App\Models\Country::all();
			  $provinsis = App\Models\Provinsi::all();
			  //dd($countries);
			  $employees = App\Models\Employee::where('user_id',$user->id)->get();
			?>
			@foreach($employees as $employee)
			<div class="box-header"><strong>{{trans('employee.employee')}}</strong> <small>Form</small></div>
			<div class="box-body">
				<div class="row">
					<div class="form-group col-sm-4">
						<label for="city">{{trans('employee.branch')}}</label>
						<!--input class="form-control" id="city" type="text" placeholder="Enter your city"-->
						<select class="form-control" onchange="branch" style="width: 100%;" aria-hidden="true" name="branch" id="branch" required>
							<option value="">=== Pilih Branch ===</option>
							<?php
								$roll = [];
								$companies = App\Models\Company::All();                        
								$roll[] = $employee->branch;
							?>
							@foreach($companies as $company)
								@if(in_array($company->id, $roll))
								  <option value="{{ $company->id }}" selected="true">{{ $company->name }}</option>
								@else
								  <option value="{{ $company->id }}">{{ $company->name }}</option>
								@endif 
							@endforeach
						</select>
					</div>				
					<div class="form-group col-sm-8">
						<label for="company">Name</label>
						<input name="name" class="form-control" id="company" type="text" value="{{$user->name}}" required>
					</div>
				</div>				
				<div class="row">
					<div class="form-group col-sm-4 {!! $errors->has('email') ? 'has-error' : '' !!} required ">
						<label for="email" class="control-label">{{ trans('general.email') }}</label>
						<input class="form-control" name="email" type="email" value="{{$user->email}}" required>
						@if ($errors->first('email'))
						<span class="help-block">{!! $errors->first('email') !!}</span>
						@endif
					</div>				
					<!--div class="form-group col-sm-4 {!! $errors->has('password') ? 'has-error' : '' !!} required ">
						<label for="password" class="control-label">{{ trans('general.password') }}</label>
						<input class="form-control" name="password" type="password" placeholder="{{trans('general.password')}}" required>
						@if ($errors->first('password'))
						<span class="help-block">{!! $errors->first('password') !!}</span>
						@endif
					</div>				
					<div class="form-group col-sm-4 {!! $errors->has('password-confirm') ? 'has-error' : '' !!} required">
						<label for="password-confirm" class="control-label">{{ __('Confirm Password') }}</label>					
						<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
					</div-->
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						<label for="vat">Mobile Phone</label>
						<input name="mobile_phone" class="form-control" id="mobile_phone" type="text" value="{{$user->mobile_phone}}" required>
					</div>
					<div class="form-group col-sm-4">
						<label for="company">Birth Place</label>
						<input name="birth_place" class="form-control" id="birth_place" type="text" value="{{$employee->birth_place}}">
					</div>
					<div class="form-group col-sm-4">
						<label for="company">Date Of Place</label>
						<input name="date_birth" class="form-control" id="date_place" type="date" value="{{$employee->date_of_birth}}" required>
					</div>
				</div>
				<div class="row">
					<!--div class="form-group col-sm-4">
						<label for="city">Card Type</label>
						<select class="form-control" id="card_type" name="card_type" required>
							<option value="0">Please select</option>
							@foreach($cards as $card)
							  <option value="{{$card->id}}">{{ $card->code }} - {{ $card->name }}</option>
							@endforeach
							<?php
								$roll = [];
								$cards = App\Models\CardType::All();                        
								$roll[] = $employee->card_type;
							?>
							@foreach($cards as $card)
								@if(in_array($card->id, $roll))
								  <option value="{{ $card->id }}" selected="true">{{ $card->name }}</option>
								@else
								  <option value="{{ $card->id }}">{{ $card->name }}</option>
								@endif 
							@endforeach
						</select>
					</div-->
					<div class="form-group col-sm-4">
						<label for="vat">Population Card</label>
						<input name="population_card" class="form-control" id="population_card" type="text" value="{{$employee->population_card}}" required>
					</div>
					<div class="form-group col-sm-4">
						<label for="vat">Card Number</label>
						<input name="family_card" class="form-control" id="family_card" type="text" value="{{$employee->family_card}}" required>
					</div>
					<div class="form-group col-sm-4">
						<label for="city">Gender</label>
						<!--input class="form-control" id="city" type="text" placeholder="Enter your city"-->
						<select class="form-control" id="gender" name="gender">
							@if ($employee->gender === 'Lelaki')							
								<option value="1" selected="true">Lelaki</option>
								<option value="2">Perempuan</option>							
							@elseif($employee->gender === 'Perempuan')	
								<option value="2" selected="true">Perempuan</option>
								<option value="1">Lelaki</option>	
							@else
								<option value="1">Lelaki</option>
								<option value="2">Perempuan</option>
							@endif
						</select>
						
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						<label for="roles">Education</label>								
						<select name="education" class="form-control" style="width: 100%;" required>
							<option value="">-- Education --</option>
							<?php
								$roll = [];								                       
								$roll[] = $employee->education;
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
						<label for="roles">Maritial</label>								
						<select name="maritial" class="form-control" style="width: 100%;" required>
							<option value="">-- Maritial --</option>
							<?php
								$roll = [];								                       
								$roll[] = $employee->maritial;
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
					<div class="form-group col-sm-4">
						<label for="roles">Religion</label>								
						<select name="religion" class="form-control" style="width: 100%;" required>
							<option value="">-- Religion --</option>
							<?php
								$roll = [];								                       
								$roll[] = $employee->religion;
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
				</div>
				<div class="row">
					<div class="form-group col-sm-8">
						<label for="street">Street</label>
						<input name="address" class="form-control" id="street" type="text" value="{{$employee->address}}">
					</div>
					<div class="form-group col-sm-4">
						<label for="postal-code">Postal Code</label>
						<input name="zip_code" class="form-control" id="postal-code" type="text" value="{{$employee->zip_code}}">
					</div>
				</div>
				<div class="row">
					<!--div class="form-group col-sm-6">
						<label for="roles">Role</label>								
						<select name="roles" class="form-control" style="width: 100%;">							
							<?php								
								//$roll = [];	
								//$roll[] = $user->roles->first()->id;
							?>
							@foreach($roles as $role)
								@if(in_array($role->id, $roll))
								  <option value="{{ $role->id }}" selected="true">{{ $role->name }}</option>
								@else
								  <option value="{{ $role->id }}">{{ $role->name }}</option>
								@endif 
							@endforeach
						</select>
					</div-->
					<div class="form-group col-sm-4">
						<label for="postal-code">Payroll Bank</label>
						<input name="payroll_bank" class="form-control" id="payroll_bank" type="text" value="{{$employee->payroll_bank}}">
					</div>
					<div class="form-group col-sm-4">
						<label for="postal-code">Account Number</label>
						<input name="account_number" class="form-control" id="account_number" type="text" value="{{$employee->account_number}}">
					</div>
					<div class="form-group col-sm-4">
						<label for="postal-code">Position</label>
						<input name="position" class="form-control" id="position" type="text" value="{{$employee->position}}">
					</div>
					<div class="form-group col-sm-4">
						<label for="postal-code">ID Card</label>
						<input name="id_card" class="form-control" id="id_card" type="text" value="{{$employee->id_card}}">
					</div>					
					<div class="form-group col-sm-4">
						<label for="vat">Mother Name</label>
						<input name="mother_name" class="form-control" id="mother_name" type="text" value="{{$employee->mother_name}}" required>
					</div>
					<div class="form-group col-sm-4">
						<label for="vat">Mother Phone</label>
						<input name="mother_phone" class="form-control" id="mother_phone" type="text" value="{{$employee->mother_phone}}" required>
					</div>
					<div class="form-group col-sm-4">
						<label for="vat">Father Name</label>
						<input name="father_name" class="form-control" id="father_name" type="text" value="{{$employee->father_name}}" required>
					</div>
					<div class="form-group col-sm-4">
						<label for="vat">Father Phone</label>
						<input name="father_phone" class="form-control" id="father_phone" type="text" value="{{$employee->father_phone}}" required>
					</div>
					<div class="form-group col-sm-4">
						<label for="city">{{trans('loan.husband_wife_home_status')}}</label>
						<select class="form-control" id="home_status" name="home_status">							
							<?php
								$roll = [];
								$employees = ['Milik Sendiri (tidak dijaminkan)','Milik Sendiri (dijaminkan)','Milik Keluarga','Kontrak','Kost','KPR'];                        
								$roll[] = $employee->home_status;
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
				<div class="form-group col-sm-12">
                	<label for="image" class="control-label">Avatar</label>
	                <div class="controls">
	                    <img id="preview"
	                         src="{{asset($user->avatar!='' ?'uploads/photo/'.$user->avatar:'uploads/photo/noimage.jpg')}}"
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
				<button class="btn btn-success" type="submit">{{trans('general.submit')}}</button>				
				<span class="new-button">
					<a href="{{ route('employee')}}" class="btn btn-danger">
						<span class="cil-close"></span> {{trans('general.close')}}
					</a>
				</span>
			</div>
			@endforeach
		</form>		
		@endforeach
	</div>
	
@endsection

@section('js')
<script src="{{ asset ('/js/jquery.min.js') }}"></script>

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

@endsection