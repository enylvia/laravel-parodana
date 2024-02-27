@extends('layouts.app')
@section('content')
	@include('error.error-notification')
	<div class="box">
		<form method="post" action="{{route('withdrawal.store')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		<div class="box-header">
		</div>
		<div class="box-body">				
			<div class="row">
				<div class="form-group {!! $errors->has('tr_date') ? 'has-error' : '' !!} required col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<label for="date">{{trans('general.date')}}</label>
					<div class="input-group">
						<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
						<input class="form-control" name="tr_date" type="date">
						<input type="hidden" class="form-control" name="status">
					</div>
				</div>
				<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<label for="city">{{trans('general.customer')}}</label>
					<!--input class="form-control" id="city" type="text" placeholder="Enter your city"-->
					<select class="form-control" aria-hidden="true" name="member_number" id="customer" style="width: 100%;" required>
						<option value="0">{{ trans('general.choice') }}</option>
						@foreach($customers as $customer)
						  <option value="{{$customer->member_number}}" data-nama="{{$customer->name}}" data-alamat="{{$customer->address}}">{{ $customer->member_number }}</option>
						@endforeach
					</select>
				</div>				
				<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<label for="company">Name</label>
					<input name="name" class="form-control" id="name" type="text" placeholder="Enter your name" disabled>
				</div>
				<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<label for="company">Name</label>
					<input name="address" class="form-control" id="address" type="text" placeholder="Enter your address" disabled>
				</div>
				<div class="form-group {!! $errors->has('tipe') ? 'has-error' : '' !!} required col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<label for="tipe" class="control-label">{{ trans('installment.savings_type') }}</label>
					<div class="input-group">
						<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
						<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="tipe" id="tipe" required>
							<option value="wajib">WAJIB</option>
							<option value="pokok">POKOK</option>
							<option value="sukarela">SUKARELA</option>
						</select>
					</div>
				</div>
				<div class="form-group {!! $errors->has('amount') ? 'has-error' : '' !!} required col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<label for="amount" class="control-label">{{ trans('installment.amount') }}</label>
					<div class="input-group">
						<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
						<input class="form-control" name="amount" type="text" placeholder="Rp. 0" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
						@if ($errors->first('amount'))
						<span class="help-block">{!! $errors->first('amount') !!}</span>
						@endif
					</div>
				</div>
			</div>
			
		</div>
		<div class="box-footer">
			<a href="{{ route('withdrawal')}}" class="btn btn-danger">
				<span class="cil-close"></span> {{trans('general.close')}}
			</a>
			<button class="btn btn-success" type="submit" id="simpan">
				<span class="cil-save"></span> {{('Save')}}
			</button>
		</div>
		</form>
	</div>
@endsection

@section('js')
<script>	
	window.onload = function(){
		$("#customer").change(function () {
			var ambilNama = $(this).find(':selected').attr('data-nama')
			var ambilAlamat = $(this).find(':selected').attr('data-alamat')
			$('#name').val(ambilNama);
			$('#address').val(ambilAlamat);
		});
	}
</script>
@endsection