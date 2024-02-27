@foreach($transactions as $transaction)
<div class="modal fade in" id="Edit-{{$transaction->id}}" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" transaction="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form method="post" action="{{URL::to('/transaction/type/update', $transaction->id)}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">{{trans('general.transaction')}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group col-md-12 {!! $errors->has('transaction_type') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.transaction_type') }}</label>						
						<input class="form-control" name="transaction_type" type="text" value="{{$transaction->transaction_type}}" id="transaction_type" required>						
					</div>
					<div class="form-group col-md-12 {!! $errors->has('account_number') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.account_number') }}</label>						
							<select name="account_number" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" id="contract_number">
								<?php
									$roll = [];
									$accounts = App\Models\AccountGroup::All();                        
									$roll[] = $transaction->account_number;
								?>
								@foreach($accounts as $account)
									@if(in_array($account->account_number, $roll))
										<option value="{{$account->account_number}}" selected="true">{{$account->account_number}} | {{$account->account_name}}</option>
									@else
										<option value="{{$account->account_number}}">{{$account->account_number}} | {{$account->account_name}}</option>
									@endif 
								@endforeach
							</select>
					</div>
					<div class="form-group col-md-12 {!! $errors->has('tipe') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('installment.type') }}</label>
						<select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" name="tipe" id="tipe" required>
							@if($transaction->tipe == 'd')
							<option value="d" selected="true">DEBET</option>
							<option value="k">KREDIT</option>
							@elseif($transaction->tipe == 'k')
							<option value="d">DEBET</option>
							<option value="k" selected="true">KREDIT</option>
							@else
							<option value="d">DEBET</option>
							<option value="k">KREDIT</option>
							@endif
						</select>
					</div>										
					<div class="form-group col-md-12 {!! $errors->has('description') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.description') }}</label>						
						<input class="form-control" name="description" type="text" value="{{$transaction->description}}" id="description" required>						
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