@extends('layouts.app')
@section('content')

	<div class="box">
		<form method="post" action="{{route('company.store')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			<?php          
			  //$countries = App\Models\Country::all();
			  $provinsis = App\Models\Provinsi::all();
			  //dd($countries);
			?>
			<div class="box-header"><strong>Company</strong> <small>Form</small></div>
			<div class="box-body">
				<div class="form-group">
					<label for="company">Company</label>
					<input name="name" class="form-control" id="company" type="text" placeholder="Enter your company name" required>
				</div>
				<div class="row">
					<div class="form-group col-sm-8">
						<label for="vat">SIUP</label>
						<input name="siup" class="form-control" id="vat" type="text" placeholder="SIUP" required>
					</div>
					<div class="form-group col-sm-4">
						<label for="city">{{trans('loan.branch')}}</label>
						<!--input class="form-control" id="city" type="text" placeholder="Enter your city"-->
						<select class="form-control" id="branch" name="branch" required>
							<option value="0">Please select</option>
							<option value="1">Pusat</option>
							<option value="2">Cabang</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="city">{{trans('loan.province')}}</label>
						<!--input class="form-control" id="city" type="text" placeholder="Enter your city"-->
						<select class="form-control" onchange="provinsi" style="width: 100%;" aria-hidden="true" name="provinsi" id="provinsi">
							<option value="">=== Pilih Provinsi ===</option>
							@foreach($provinsis as $provinsi)
							  <option value="{{$provinsi->id}}">{{ $provinsi->nama }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label for="postal-code">{{trans('loan.regency')}}</label>
						<select class="form-control" id="kabupaten" name="kabupaten">
							<option value="">=== Pilih Kabupaten ===</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="country">{{trans('loan.districts')}}</label>
						<select class="form-control" id="kecamatan" name="kecamatan">
							<option value="">=== Pilih Kecamatan ===</option>	
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label for="country">{{trans('loan.vilage')}}</label>
						<select class="form-control" id="kelurahan" name="kelurahan">
							<option value="">=== Pilih Kelurahan ===</option>	
						</select>
					</div>

				</div>
				<div class="row">
					<div class="form-group col-sm-8">
						<label for="street">{{trans('loan.address')}}</label>
						<input name="address" class="form-control" id="street" type="text" placeholder="Enter street name" required>
					</div>
					<div class="form-group col-sm-4">
						<label for="postal-code">{{trans('loan.postal_code')}}</label>
						<input name="zip_code" class="form-control" id="postal-code" type="text" placeholder="Postal Code">
					</div>
				</div>
			</div>
			<div class="box-footer">
				<button class="btn btn-success" type="submit">{{trans('general.submit')}}</button>				
				<span class="new-button">
					<a href="{{ route('company')}}" class="btn btn-danger">
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