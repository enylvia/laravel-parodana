@extends('layouts.app')
@section('content')

	<div class="box">
		@foreach($companies as $company)
		<form method="post" action="{{URL::to('/company/update', $company->id)}}" enctype="multipart/form-data">
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
					<input name="name" class="form-control" id="company" type="text" value="{{$company->name}}">
				</div>
				<div class="row">
					<div class="form-group col-sm-8">
						<label for="vat">SIUP</label>
						<input name="siup" class="form-control" id="vat" type="text" value="{{$company->siup}}">
					</div>
					<div class="form-group col-sm-4">
						<label for="city">{{trans('loan.branch')}}</label>
						<!--input class="form-control" id="city" type="text" placeholder="Enter your city"-->
						<select class="form-control" id="branch" name="branch">
							<?php
								$roll = [];								
								$roll[] = $company->branch;
							?>
						    @if(in_array("Pusat", $roll))
								<option value="1">Pusat</option>
								<option value="2">Cabang</option>
							@else
								<option value="2">Cabang</option>
								<option value="1">Pusat</option>
							@endif
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="city">{{trans('loan.province')}}</label>
						<!--input class="form-control" id="city" type="text" placeholder="Enter your city"-->
						<select class="form-control" onchange="provinsi" style="width: 100%;" aria-hidden="true" name="provinsi" id="provinsi">
							<?php
								$roll = [];
								$provinsis = App\Models\Provinsi::All();                        
								$roll[] = $company->provinsi;
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
					<div class="form-group col-sm-6">
						<label for="postal-code">{{trans('loan.regency')}}</label>
						<select class="form-control" id="kabupaten" name="kabupaten">
							<?php
								$roll = [];
								$kabupatens = App\Models\Kabupaten::where('provinsi_id',$company->provinsi)->get();                    
								$roll[] = $company->kabupaten;
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
				</div>
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="country">{{trans('loan.districts')}}</label>
						<select class="form-control" id="kecamatan" name="kecamatan">
							<?php
								$roll = [];
								$kecamatans = App\Models\Kecamatan::where('kabupaten_id',$company->kabupaten)->get();                     
								$roll[] = $company->kecamatan;
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
					<div class="form-group col-sm-6">
						<label for="country">{{trans('loan.vilage')}}</label>
						<select class="form-control" id="kelurahan" name="kelurahan">
							<?php
								$roll = [];
								$kelurahans = App\Models\Kelurahan::where('kecamatan_id',$company->kecamatan)->get();                     
								$roll[] = $company->kelurahan;
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

				</div>
				<div class="row">
					<div class="form-group col-sm-8">
						<label for="street">{{trans('loan.address')}}</label>
						<input name="address" class="form-control" id="street" type="text" value="{{$company->address}}">
					</div>
					<div class="form-group col-sm-4">
						<label for="postal-code">{{trans('loan.postal_code')}}</label>
						<input name="zip_code" class="form-control" id="postal-code" type="text" value="{{$company->zip_code}}">
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
		@endforeach
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