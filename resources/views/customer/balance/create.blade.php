@extends('layouts.app')
@section('content')

@include('error.error-notification')

	<div class="box">
	<form method="post" action="{{route('balance.store')}}" enctype="multipart/form-data">
	{{ csrf_field() }}
		<div class="box-header">
		</div>
		<div class="box-body">
			<div class="form-group col-sm-6">
				<label for="date_trans">{{trans('general.date')}}</label>
				<input type="date" name="date_trans" class="form-control" value="<?php echo date("Y-m-d"); ?>" style="width: 100%;" id="in_date_trans" required>
				<span class="text-danger error-text date_trans_err"></span>
			</div>
			<div class="form-group col-sm-6">
				<label for="customer">{{trans('general.customer')}}</label>
				<select name="cust_id" class="input select2 select2-hidden-accessible" onchange="cust_id" style="width: 100%;" aria-hidden="true" id="cust_id">
					<?php 
						$customers = App\Models\Customer::all();
					?>
					<option value="">SELECT</option>
					@foreach($customers as $customer)
					<option value="{{$customer->id}}">{{$customer->name}}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-sm-6">
				<label for="payment_type">Metode Transaksi</label>
				<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="method_transaction" id="method_transaction">
					<option value="IN">IN</option>
					<option value="OUT">OUT</option>
				</select>
			</div>
			<div class="form-group col-sm-6">
				<label for="customer">{{trans('general.from')}} {{trans('general.account')}}</label>
				<input type="text" class="form-control" name="acc_number"  style="width: 100%;" id="in_acc_number">
			</div>
			<div class="form-group col-sm-6">
				<label for="customer">{{trans('general.to')}} {{trans('general.account')}}</label>
				<input type="text" class="form-control" name="acc_to"  style="width: 100%;" id="in_acc_to">
			</div>
			<div class="form-group col-sm-6 {!! $errors->has('transaction_type') ? 'has-error' : '' !!} required ">						
				<label for="transaction_type" class="control-label">{{ trans('general.transaction_type') }}</label>						
				<select name="transaction_type" class="input select2 select2-hidden-accessible" onchange="type_in" style="width: 100%;" aria-hidden="true" id="type_in">
					<?php 
						$types = App\Models\TransactionType::all();
					?>
					<option value="">SELECT</option>
					@foreach($types as $type)
					<option value="{{$type->id}}">{{$type->transaction_type}}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-sm-6">
				<label for="provinsi">{{trans('general.payment_method')}}</label>
				<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="payment_method" id="in_payment_method">
					<option value="cash">Cash</option>
					<option value="transfer_bank">Transfer Bank</option>
					<option value="debit_card">Kartu Debit</option>
					<option value="credit_card">Kartu Kredit</option>
				</select>
			</div>
			<div class="form-group col-sm-6">
				<label for="amount">{{trans('general.amount')}}</label>
				<input type="text" name="amount" class="form-control" style="width: 100%;" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="in_amount" required>
				<span class="text-danger error-text amount_err"></span>
			</div>
			<div class="form-group col-sm-6">
				<label for="description">{{trans('general.description')}}</label>
				<input type="text" name="description" class="form-control" placeholder="{{trans('general.description')}}" style="width: 100%;" id="in_description">
				<span class="text-danger error-text description_err"></span>
			</div>				
		</div>
		<div class="box-footer">
			<button class="btn btn-secondary btn-close" type="button" data-dismiss="modal">Close</button>					
			<button class="btn btn-success" type="submit" id="simpan">
				<span class="cil-save"></span> {{('Save')}}
			</button>					
		</div>
	</form>
	</div>

@endsection