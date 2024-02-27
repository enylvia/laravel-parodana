@extends('layouts.app')
@section('content')
	
	@include('error.error-notification')	
	<div class="box">
		<form method="post" action="{{URL::to('customer/reloan/storeone')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		<div class="box-header">
			<h2>{{trans('general.Reloan')}}</h2>
		</div>
		<div class="box-body">
			<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<label for="customer">{{trans('general.customer')}}</label>
				<select class="input select2 select2-hidden-accessible" onchange="customer" style="width: 100%;" aria-hidden="true" name="customer" id="customer" required>
					<option value="0">Please select</option>
					@foreach($customers as $customer)
						<option value="{{$customer->id}}" data-regNumber="{{$customer->reg_number}}" data-company="{{$customer->company_name}}" data-address="{{$customer->address}}">{{$customer->name}} | {{$customer->member_number}}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<label for="customer">No. Register</label>									
				<input type="text" name="reg_number" class="form-control" id="reg_number">
			</div>
			<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<label for="customer">Perusahaan</label>									
				<input type="text" name="company_name" class="form-control" id="company_name">
			</div>
			<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<label for="customer">Alamat Rumah</label>									
				<input type="text" name="address" class="form-control" id="address">
			</div>
		</div>
		<div class="box-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>					
			<button class="btn btn-success" type="submit" id="simpan">
				<span class="cil-save"></span> {{('Next')}}
			</button>
		</div>
		</form>
	</div>

@endsection

@section('js')
<script>
	$('#customer').on('change', function () {
		var ambilId = $('#customer option:selected').val();
		//alert(ambilId);
		var regNumber = $(this).find(':selected').attr('data-regNumber');
		var companyName = $(this).find(':selected').attr('data-company');
		var address = $(this).find(':selected').attr('data-address');
		document.getElementById('reg_number').value = regNumber;
		document.getElementById('company_name').value = companyName;
		document.getElementById('address').value = address;
		//$.ajax({
			//headers: {
			//	'X-CSRF-Token': $('meta[name="_token"]').attr('content')
			//},
			//url: "{{ url('customer/reloan/fetch') }}/"+ambilId,
			//url: "{{ url('customer/reloan/fetch') }}",
			//type: 'post',
			//data: {ambilId: ambilId},
			//success: function(response){ 
			//	$.each(response, function(index, subcatObj){
			//		alert(subcatObj.name);
			//		//$('#connection_kecamatan').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
			//	});
			//},
			//error: function(error) {
			//	console.log(error);
			//}
		//});
	});
</script>
@endsection