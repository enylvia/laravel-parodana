@foreach($transactions as $transaction)
<div class="modal fade in" id="Edit-{{$transaction->transaction_no}}" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" transaction="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form method="post" action="{{URL::to('/operational/update', $transaction->transaction_no)}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">{{trans('general.transaction')}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group col-md-12 {!! $errors->has('transaction_type') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.transaction_type') }}</label>						
							<select name="transaction_type" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" id="transaction_type">
								<?php
									$roll = [];
									$types = App\Models\TransactionType::All();                        
									$roll[] = $transaction->transaction_type;
								?>
								@foreach($types as $account)
									@if(in_array($account->transaction_type, $roll))
										<option value="{{$account->transaction_type}}" selected="true">{{$account->transaction_type}}</option>
									@else
										<option value="{{$account->transaction_type}}">{{$account->transaction_type}}</option>
									@endif 
								@endforeach
							</select>
					</div>										
					<div class="form-group col-md-12 {!! $errors->has('description') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.description') }}</label>						
						<input class="form-control" name="description" type="text" value="{{$transaction->description}}" id="description" required>						
					</div>
					<div class="form-group col-md-12 {!! $errors->has('amount') ? 'has-error' : '' !!} required ">
						<label for="amount" class="control-label">{{ trans('general.amount') }}</label>						
						<input class="form-control" name="amount" type="text" value="{{$transaction->amount}}" id="amount" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>						
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