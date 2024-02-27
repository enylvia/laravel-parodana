<?php 
	$balances = App\Models\BalanceAccount::all();
?>
@foreach($balances as $balance)
<div id="Edit-{{$balance->id}}" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			
			<form method="post" action="{{URL::to('/customer/balance/update', $balance->id)}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">{{trans('general.account')}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group ">
						<label for="transaction_no">{{trans('general.transaction_no')}}</label>
						<input type="text" name="transaction_no" class="form-control" value="{{$balance->transaction_no}}" style="width: 100%;" id="transaction_no" required>						
					</div>
					<div class="form-group ">
						<label for="customer">{{trans('general.date')}}</label>
						<input type="date" name="date_trans" class="form-control" value="{{ date('Y-m-d', strtotime($balance->mutation_date))}}" style="width: 100%;">
					</div>
					<!--div class="form-group col-sm-6">
						<label for="customer">{{trans('general.from')}} {{trans('general.account')}}</label>
						<input type="text" name="acc_number" class="form-control" value="{{$balance->atm_number}}" style="width: 100%;" id="in_acc_number" required>
						<input type="hidden" name="bank_pin" class="form-control" value="{{$balance->bank_pin}}" style="width: 100%;" id="in_bank_pin">
					</div> 
					<div class="form-group col-sm-6">
						<label for="customer">{{trans('general.to')}} {{trans('general.account')}}</label>
						<input type="text" class="form-control" name="acc_to" value="{{ \setting('company_acc') }}" style="width: 100%;" id="in_acc_to">
						<input type="hidden" class="form-control" name="payment_type" value="IN" style="width: 100%;" id="in_payment_type">
					</div-->
					<div class="form-group ">
						<label for="provinsi">{{trans('general.payment_method')}}</label>
						<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="payment_method" id="payment_method">
							@if ($balance->payment_method === 'cash')	
								<option value="cash" selected="true">Cash</option>
								<option value="transfer_bank">Transfer Bank</option>
								<option value="debit_card">Kartu Debit</option>
								<option value="credit_card">Kartu Kredit</option>
							@elseif ($balance->payment_method === 'transfer_bank')
								<option value="cash">Cash</option>
								<option value="transfer_bank" selected="true">Transfer Bank</option>
								<option value="debit_card">Kartu Debit</option>
								<option value="credit_card">Kartu Kredit</option>
							@elseif ($balance->payment_method === 'debit_card')
								<option value="cash">Cash</option>
								<option value="transfer_bank">Transfer Bank</option>
								<option value="debit_card" selected="true">Kartu Debit</option>
								<option value="credit_card">Kartu Kredit</option>
							@else 
								<option value="cash">Cash</option>
								<option value="transfer_bank">Transfer Bank</option>
								<option value="debit_card">Kartu Debit</option>
								<option value="credit_card" selected="true">Kartu Kredit</option>
							@endif
						</select>
					</div>
					<div class="form-group ">
						<label for="amount">{{trans('general.amount')}}</label>
						<input type="text" name="amount" class="form-control" style="width: 100%;" value="{{ number_format($balance->amount, 0, ',' , '.') }}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="amount" required>
						<span class="text-danger error-text amount_err"></span>
					</div>
					<div class="form-group ">
						<label for="description">{{trans('general.description')}}</label>
						<input type="text" name="description" class="form-control" value="{{$balance->description}}" style="width: 100%;" id="description">
						<span class="text-danger error-text description_err"></span>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary btn-close" type="button" data-dismiss="modal">Close</button>					
					<button class="btn btn-success" type="submit" id="simpan">
						<span class="cil-save"></span> {{('Save')}}
					</button>					
				</div>
			</form>
		</div>
	</div>
</div>
@endforeach