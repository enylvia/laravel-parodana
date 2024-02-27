@foreach($setorans as $setoran)
<div class="modal fade in" id="Edit-{{$setoran->id}}" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form method="post" action="{{URL::to('/deposit/update', $setoran->id)}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">{{trans('installment.savings')}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group {!! $errors->has('tr_date') ? 'has-error' : '' !!} required col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label for="name" class="control-label">{{ trans('installment.date') }}</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
							<input class="form-control" name="tr_date" type="date" value="{{$setoran->tr_date}}">
						</div>
					</div>
					<div class="form-group {!! $errors->has('name') ? 'has-error' : '' !!} required col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label for="name" class="control-label">{{ trans('installment.customer_number') }}</label>
						<div class="input-group">
							<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="member_number" id="member_number" required>								
								<?php
								$roll = [];								                       
									$roll[] = $setoran->member_number;
								?>
								@foreach($customers as $customer)
									@if(in_array($customer->member_number, $roll))
									  <option value="{{ $customer->member_number }}" selected="true">{{ $customer->member_number }}</option>
									@else
									  <option value="{{ $customer->member_number }}">{{ $customer->member_number }}</option>
									@endif 
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group {!! $errors->has('name') ? 'has-error' : '' !!} required col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label for="name" class="control-label">{{ trans('installment.savings_type') }}</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
							<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="tipe" id="tipe" required>																
								@if ($setoran->tipe === 'WAJIB')							
								<option value="wajib" selected="true">WAJIB</option>
								<option value="pokok">POKOK</option>
								<option value="sukarela">SUKARELA</option>
								@elseif($setoran->tipe === 'POKOK')	
									<option value="pokok" selected="true">POKOK</option>
									<option value="wajib">WAJIB</option>
									<option value="sukarela">SUKARELA</option>								
								@elseif($setoran->tipe === 'SUKARELA')
									<option value="sukarela" selected="true">SUKARELA</option>
									<option value="wajib">WAJIB</option>
									<option value="pokok">POKOK</option>
								@else									
									<option value="wajib">WAJIB</option>
									<option value="pokok">POKOK</option>
									<option value="sukarela">SUKARELA</option>
								@endif
							</select>
						</div>
					</div>					
					<div class="form-group {!! $errors->has('amount') ? 'has-error' : '' !!} required col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label for="name" class="control-label">{{ trans('installment.amount') }}</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
							<input class="form-control" name="amount" type="text" value="{{ number_format($setoran->amount, 0, ',', '.') }}" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
							@if ($errors->first('amount'))
							<span class="help-block">{!! $errors->first('amount') !!}</span>
							@endif
						</div>
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