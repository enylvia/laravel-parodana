<?php 
	$customers = App\Models\Customer::join('customer_contract', 'customer_contract.customer_id', '=', 'customer.id' )
				->get();
?>
@foreach($customers as $customer)
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="MutasiOut-{{$customer->id}}" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form method="post" action="{{route('balance.store')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">{{trans('general.account')}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group ">
						<label for="date_trans">{{trans('general.date')}}</label>
						<input type="date" name="date_trans" class="form-control" value="<?php echo date("Y-m-d"); ?>" style="width: 100%;" required>
					</div>
					<div class="form-group ">
						<label for="customer">{{trans('general.customer')}}</label>
						<input type="text" name="customer" class="form-control" value="{{$customer->name}}" style="width: 100%;" >
						<input type="hidden" name="cust_id" class="form-control" value="{{$customer->id}}" style="width: 100%;">
						<input type="hidden" name="member_number" class="form-control" value="{{$customer->member_number}}" style="width: 100%;">
					</div>
					<div class="form-group col-sm-6">
						<label for="customer">{{trans('general.from')}} {{trans('general.account')}}</label>
						<input type="text" class="form-control" name="acc_to" value="{{ \setting('company_acc') }}" style="width: 100%;">
					</div> 
					<div class="form-group col-sm-6">
						<label for="customer">{{trans('general.to')}} {{trans('general.account')}}</label>
						<input type="text" name="acc_number" class="form-control" value="{{$customer->atm_number}}" style="width: 100%;">
						<input type="hidden" class="form-control" name="payment_type" value="OUT" style="width: 100%;">
					</div>
					<div class="form-group col-sm-6 {!! $errors->has('transaction_type') ? 'has-error' : '' !!} required ">						
						<label for="transaction_type" class="control-label">{{ trans('general.transaction_type') }}</label>						
						<select name="transaction_type" class="input select2 select2-hidden-accessible" onchange="type_out" style="width: 100%;" aria-hidden="true" id="type_out">
							
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label for="provinsi">{{trans('general.payment_method')}}</label>
						<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="payment_method" id="payment_method">
							<option value="cash">Cash</option>
							<option value="transfer_bank">Transfer Bank</option>
							<option value="debit_card">Kartu Debit</option>
							<option value="credit_card">Kartu Kredit</option>
						</select>
					</div>
					<div class="form-group ">
						<label for="amount">{{trans('general.amount')}}</label>
						<input type="text" name="amount" class="form-control" placeholder="" style="width: 100%;" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
					</div>
					<div class="form-group ">
						<label for="amount">{{trans('general.description')}}</label>
						<input type="text" name="description" class="form-control" placeholder="{{trans('general.description')}}" style="width: 100%;">
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>					
					<button class="btn btn-success" type="submit" id="simpan">
						<span class="cil-save"></span> {{('Save')}}
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endforeach