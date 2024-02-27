@extends('layouts.app')
@section('content')

	<div class="box">
		<form method="post" action="{{route('employee.store')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			<?php          
			  //$countries = App\Models\Country::all();
			  $provinsis = App\Models\Provinsi::all();
			  //dd($countries);
			?>
			<div class="box-header"><strong>{{trans('employee.employee')}}</strong> <small>Form</small></div>
			<div class="box-body">
				<div class="row">
					<div class="form-group col-sm-4">
						<label for="city">{{trans('employee.branch')}}</label>
						<!--input class="form-control" id="city" type="text" placeholder="Enter your city"-->
						<select class="form-control" onchange="branch" style="width: 100%;" aria-hidden="true" name="branch" id="branch" required>
							@foreach($companies as $company)
							  <option value="{{$company->id}}">{{ $company->name }}</option>
							@endforeach
						</select>
					</div>				
					<div class="form-group col-sm-8">
						<label for="company">Name</label>
						<input name="name" class="form-control" id="name" type="text" placeholder="Enter your name" required>
					</div>
				</div>				
				<div class="row">
					<div class="form-group col-sm-4 {!! $errors->has('email') ? 'has-error' : '' !!} required ">
						<label for="email" class="control-label">{{ trans('general.email') }}</label>
						<input class="form-control" name="email" type="email" placeholder="{{trans('general.email')}}" required>
						@if ($errors->first('email'))
						<span class="help-block">{!! $errors->first('email') !!}</span>
						@endif
					</div>				
					<div class="form-group col-sm-4 {!! $errors->has('password') ? 'has-error' : '' !!} required ">
						<label for="password" class="control-label">{{ trans('general.password') }}</label>
						<input class="form-control" name="password" type="password" placeholder="{{trans('general.password')}}" required>
						@if ($errors->first('password'))
						<span class="help-block">{!! $errors->first('password') !!}</span>
						@endif
					</div>				
					<div class="form-group col-sm-4 {!! $errors->has('password-confirm') ? 'has-error' : '' !!} required">
						<label for="password-confirm" class="control-label">{{ __('Confirm Password') }}</label>					
						<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						<label for="vat">Mobile Phone</label>
						<input name="mobile_phone" class="form-control" id="mobile_phone" type="text" placeholder="Mobile Phone" required>
					</div>
					<div class="form-group col-sm-4">
						<label for="company">Birth Place</label>
						<input name="birth_place" class="form-control" id="birth_place" type="text" placeholder="Enter your birth place">
					</div>
					<div class="form-group col-sm-4">
						<label for="company">Date Of Place</label>
						<input name="date_birth" class="form-control" id="date_place" type="date">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						<label for="city">Population Card</label>
						<input class="form-control" id="population_card" name="population_card" placeholder="Population Card Number" required>
					</div>
					<div class="form-group col-sm-4">
						<label for="vat">Family Card</label>
						<input name="family_card" class="form-control" id="family_card" type="text" placeholder="Card Number" required>
					</div>	
					<div class="form-group col-sm-4">
						<label for="city">Gender</label>
						<select class="form-control" id="gender" name="gender">
							<option value="0">Please select</option>
							<option value="1">Laki-laki</option>
							<option value="2">Perempuan</option>
						</select>
					</div>
				</div>
				<!--div class="row">
					<div class="form-group col-sm-6">
						<label for="city">Provinsi</label>
						<select class="form-control" onchange="provinsi" style="width: 100%;" aria-hidden="true" name="provinsi" id="provinsi">
							<option value="">=== Pilih Provinsi ===</option>
							@foreach($provinsis as $provinsi)
							  <option value="{{$provinsi->id}}">{{ $provinsi->nama }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label for="postal-code">Kabupaten</label>
						<select class="form-control" id="kabupaten" name="kabupaten">
							<option value="">=== Pilih Kabupaten ===</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="country">Kecamatan</label>
						<select class="form-control" id="kecamatan" name="kecamatan">
							<option value="">=== Pilih Kecamatan ===</option>	
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label for="country">Kelurahan</label>
						<select class="form-control" id="kelurahan" name="kelurahan">
							<option value="">=== Pilih Kelurahan ===</option>	
						</select>
					</div>
				</div-->
				<div class="row">
					<div class="form-group col-sm-4">
						<label for="roles">Education</label>								
						<select name="education" class="form-control" style="width: 100%;">
							<option value="">-- Education --</option>
							@foreach($educations as $education)
								<option value="{{ $education->id }}">{{ $education->code }} - {{ $education->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-sm-4">
						<label for="roles">Maritial</label>								
						<select name="maritial" class="form-control" style="width: 100%;">
							<option value="">-- Maritial --</option>
							@foreach($maritials as $maritial)
								<option value="{{ $maritial->id }}">{{ $maritial->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-sm-4">
						<label for="roles">Religion</label>								
						<select name="religion" class="form-control" style="width: 100%;">
							<option value="">-- Religion --</option>
							@foreach($religions as $religion)
								<option value="{{ $religion->id }}">{{ $religion->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-8">
						<label for="street">Street</label>
						<input name="address" class="form-control" id="street" type="text" placeholder="Enter street name" required>
					</div>
					<div class="form-group col-sm-4">
						<label for="postal-code">Postal Code</label>
						<input name="zip_code" class="form-control" id="postal-code" type="text" placeholder="Postal Code">
					</div>
				</div>
				<div class="row">
					<!--div class="form-group col-sm-6">
						<label for="roles">Role</label>								
						<select name="roles" class="form-control" style="width: 100%;">
							<option value="">-- Role --</option>
							@foreach($roles as $role)
								<option value="{{ $role->id }}">{{ $role->name }}</option>
							@endforeach
						</select>
					</div-->
					<div class="form-group col-sm-4">
						<label for="postal-code">Payroll Bank</label>
						<input name="payroll_bank" class="form-control" id="payroll_bank" type="text" placeholder="Payroll Bank Name">
					</div>
					<div class="form-group col-sm-4">
						<label for="postal-code">Account Number</label>
						<input name="account_number" class="form-control" id="account_number" type="text" placeholder="Account Number">
					</div>
					<div class="form-group col-sm-4">
						<label for="postal-code">Postion</label>
						<input name="position" class="form-control" id="position" type="text" placeholder="Position">
					</div>
					<div class="form-group col-sm-4">
						<label for="postal-code">ID Card</label>
						<input name="id_card" class="form-control" id="id_card" type="text" placeholder="ID Card">
					</div>					
					<div class="form-group col-sm-4">
						<label for="vat">Mother Name</label>
						<input name="mother_name" class="form-control" id="mother_name" type="text" placeholder="Mother Name">
					</div>
					<div class="form-group col-sm-4">
						<label for="vat">Mother Phone</label>
						<input name="mother_phone" class="form-control" id="mother_phone" type="text" placeholder="Mother Phone">
					</div>
					<div class="form-group col-sm-4">
						<label for="vat">Father Name</label>
						<input name="father_name" class="form-control" id="father_name" type="text" placeholder="Father Name">
					</div>
					<div class="form-group col-sm-4">
						<label for="vat">Father Phone</label>
						<input name="father_phone" class="form-control" id="father_phone" type="text" placeholder="Father Phone">
					</div>
					<div class="form-group col-sm-4">
						<label for="city">{{trans('loan.husband_wife_home_status')}}</label>
						<select class="form-control" id="home_status" name="home_status">
							<option value="0">Please select</option>
							<option value="Milik Sendiri (tidak dijaminkan)">Milik Sendiri (tidak dijaminkan)</option>
							<option value="Milik Sendiri (dijaminkan)">Milik Sendiri (dijaminkan)</option>
							<option value="Milik Keluarga">Milik Keluarga</option>
							<option value="Kontrak">Kontrak</option>
							<option value="Kost">Kost</option>
							<option value="KPR">KPR</option>
						</select>
					</div>
				</div>
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
				<button class="btn btn-success" type="submit">{{trans('general.submit')}}</button>				
				<span class="new-button">
					<a href="{{ route('employee')}}" class="btn btn-danger">
						<span class="cil-close"></span> {{trans('general.close')}}
					</a>
				</span>
			</div>
		</form>
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