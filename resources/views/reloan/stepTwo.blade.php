@extends('layouts.app')
@section('content')
	
	@include('error.error-notification')	
	<div class="box">
		<form method="post" action="{{URL::to('customer/reloan/storetwo')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		<div class="box-header">
			<h2>{{trans('general.Loan')}}</h2>
		</div>
		<div class="box-body">
			@foreach($customers as $customer)
				<input type="hidden" class="form-control" name="customer_id" id="customer_id" value="{{$customer->id}}">
				<input type="hidden" class="form-control" name="reg_number" id="reg_number" value="{{$customer->reg_number}}">
			@endforeach
			<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<label for="year">{{trans('loan.loan_amount')}}</label>	
				<input type="text" class="form-control" name="loan_amount" id="loan_amount" placeholder="Rp. xxx.xxx" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
			</div>
			<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<label for="year">{{trans('loan.time_period')}}</label>
				<select class="form-control" id="time_period" name="time_period" required>
					<option value="0">Please select</option>
					@foreach($tenors as $tenor)							
						<option value="{{$tenor}}">{{$tenor}} bulan</option>
					@endforeach
				</select>
			</div>			
			<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<label for="year">{{trans('loan.interest_rate')}} %</label>	
				<input type="text" class="form-control" name="interest_rate" id="interest_rate" placeholder="Tulis 36 Bulan" required>
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