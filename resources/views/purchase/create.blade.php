@extends('layouts.app')
@section('content')

	<div class="box">
	@include('error.error-notification')
		<form method="post" action="{{URL::to('transaction/purchase/store')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="box-header">
				
			</div>
			<div class="box-body">
				<div class="form-group col-sm-4 {!! $errors->has('transaction_date') ? 'has-error' : '' !!} required ">
					<label for="transaction_type" class="control-label">{{ trans('general.transaction_date') }}</label>
					<input type="date" name="trans_date" class="form-control" value="{{date('Y-m-d')}}">
				</div>
				<div class="form-group col-sm-4 {!! $errors->has('to_customer') ? 'has-error' : '' !!} required ">
					<label for="transaction_type" class="control-label">{{ trans('general.to') }}</label>
					<input type="text" name="to_customer" class="form-control">
				</div>
				<div class="form-group col-sm-4 {!! $errors->has('transaction_type') ? 'has-error' : '' !!} required ">
					<label for="transaction_type" class="control-label">{{ trans('general.transaction_type') }}</label>						
					<select name="transaction_type" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" id="transaction_type">
						<option value="0">=======PILIH=======</option>
						@foreach($types as $account)								
						<option value="{{$account->transaction_type}}" data-desc="{{$account->description}}">{{$account->transaction_type}} | {{$account->description}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-sm-4 {!! $errors->has('description') ? 'has-error' : '' !!} required ">
					<label for="description" class="control-label">{{ trans('general.description') }}</label>
					<input type="text" name="description" class="form-control">
				</div>
				<div class="form-group col-sm-4 {!! $errors->has('unit') ? 'has-error' : '' !!} required ">
					<label for="unit" class="control-label">{{ trans('general.unit') }}</label>
					<!--input type="text" name="unit" class="form-control"-->
					<select name="unit" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" id="unit">
						<option value="Liter">Liter</option>
						<option value="KiloGram">Kilo Gram</option>
						<option value="Gram">Gram</option>
						<option value="Pcs">Pcs</option>
						<option value="Sachet">Sachet</option>
						<option value="Batang">Batang</option>
						<option value="Lembar">Lembar</option>
						<option value="Rim">Rim</option>
					</select>
				</div>
				<div class="form-group col-sm-4 {!! $errors->has('quantity') ? 'has-error' : '' !!} required ">
					<label for="quantity" class="control-label">{{ trans('general.quantity') }}</label>
					<input type="number" name="quantity" class="form-control">
				</div>
				<div class="form-group col-sm-4 {!! $errors->has('price') ? 'has-error' : '' !!} required ">
					<label for="price" class="control-label">{{ trans('general.price') }}</label>
					<input type="text" name="price" class="form-control" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
				</div>				
			</div>
			<div class="box-footer">
				<button class="btn btn-success" type="submit"><span class="fa fa-save"></span> {{trans('general.submit')}}</button>				
				<span class="new-button">
					<a href="{{ route('purchase')}}" class="btn btn-danger">
						<span class="fa fa-close"></span> {{trans('general.close')}}
					</a>
				</span>
			</div>
		</form>
	</div>
@endsection