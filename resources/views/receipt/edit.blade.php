@foreach($receipts as $receipt)
<div id="Edit-{{$receipt->id}}" class="modal fade in" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form method="post" action="{{URL::to('/receipt/update', $receipt->id)}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">{{trans('general.receipt')}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group col-md-12 {!! $errors->has('tr_date') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('installment.date') }}</label>						
							<input class="form-control" name="trans_date" type="date" value="{{$receipt->trans_date}}" required>						
					</div>
					<div class="form-group col-md-12 {!! $errors->has('tr_date') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.contract_number') }}</label>						
							<select name="contract_number" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true">
								<?php
									$roll = [];
									$contracts = App\Models\Receipt::all();                        
									$roll[] = $receipt->contract_number;
								?>
								@foreach($contracts as $contract)
									@if(in_array($contract->contract_number, $roll))
									  <option value="{{ $contract->contract_number }}" selected="true">{{ $contract->contract_number }}</option>
									@else
									  <option value="{{ $contract->contract_number }}">{{ $contract->contract_number }}</option>
									@endif 
								@endforeach
							</select>
					</div>
					<div class="form-group col-md-12 {!! $errors->has('tr_date') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.provision') }}</label>						
						<input class="form-control" name="provision" type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" title="This should be a number with up to 2 decimal places." value="{{ $receipt->provision }}" required>
					</div>
					<div class="form-group col-md-12 {!! $errors->has('tr_date') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.insurance') }}</label>
						<select class="form-control" id="insurance" name="insurance">
							@if ($contract->insurance === '0.5')
								<option value="0.5" selected="true">6 Bulan 0.5%</option>
								<option value="1">9 Bulan 1%</option>
								<option value="1.25">12 Bulan 1.25%</option>
								<option value="1.50">15 Bulan 1.50%</option>
								<option value="1.75">18 Bulan 1.75%</option>
								<option value="2">24 Bulan 2%</option>
							@elseif ($contract->insurance === '1')
								<option value="0.5">6 Bulan 0.5%</option>
								<option value="1" selected="true">9 Bulan 1%</option>
								<option value="1.25">12 Bulan 1.25%</option>
								<option value="1.50">15 Bulan 1.50%</option>
								<option value="1.75">18 Bulan 1.75%</option>
								<option value="2">24 Bulan 2%</option>
							@elseif ($contract->insurance === '1.25')
								<option value="0.5">6 Bulan 0.5%</option>
								<option value="1">9 Bulan 1%</option>
								<option value="1.25" selected="true">12 Bulan 1.25%</option>
								<option value="1.50">15 Bulan 1.50%</option>
								<option value="1.75">18 Bulan 1.75%</option>
								<option value="2">24 Bulan 2%</option>
							@elseif ($contract->insurance === '1.50')
								<option value="0.5">6 Bulan 0.5%</option>
								<option value="1">9 Bulan 1%</option>
								<option value="1.25">12 Bulan 1.25%</option>
								<option value="1.50" selected="true">15 Bulan 1.50%</option>
								<option value="1.75">18 Bulan 1.75%</option>
								<option value="2">24 Bulan 2%</option>
							@elseif ($contract->insurance === '1.75')
								<option value="0.5">6 Bulan 0.5%</option>
								<option value="1">9 Bulan 1%</option>
								<option value="1.25">12 Bulan 1.25%</option>
								<option value="1.50">15 Bulan 1.50%</option>
								<option value="1.75" selected="true">18 Bulan 1.75%</option>
								<option value="2">24 Bulan 2%</option>
							@elseif ($contract->insurance === '2')
								<option value="0.5">6 Bulan 0.5%</option>
								<option value="1">9 Bulan 1%</option>
								<option value="1.25">12 Bulan 1.25%</option>
								<option value="1.50">15 Bulan 1.50%</option>
								<option value="1.75">18 Bulan 1.75%</option>
								<option value="2" selected="true">24 Bulan 2%</option>
							@else
								<option value="0.5">6 Bulan 0.5%</option>
								<option value="1">9 Bulan 1%</option>
								<option value="1.25">12 Bulan 1.25%</option>
								<option value="1.50">15 Bulan 1.50%</option>
								<option value="1.75">18 Bulan 1.75%</option>
								<option value="2">24 Bulan 2%</option>
							@endif
						</select>
					</div>
					<div class="form-group col-md-12 {!! $errors->has('tr_date') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.stamp') }}</label>						
						<input class="form-control" name="stamp" type="text" value="{{ number_format($receipt->stamp, 0, ',' , '.') }}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>						
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