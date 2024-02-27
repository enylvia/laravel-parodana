<div class="modal fade in" id="create" style="display: none;" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" action="{{route('account.store')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">{{trans('general.account')}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<select class="form-control " name="parents" id="parents">							
						  <option value="0" selected="true">Root</option>
								@foreach ($catGroup as $value)
									<?php $roll = [];                                  
									  $roll[] = $value->id_parent;
									?>
									@if(in_array($value->id, $roll))
									<option value="{{ $value->id }}" selected="true">{{ $value->account_number }} | {{ $value->account_name }}</option>
									@else
									<option value="{{$value->id}}">{{ $value->account_number }} | {{ $value->account_name }}</option>
									@endif
								@endforeach
						</select>
					</div>
					<div class="form-group {!! $errors->has('account_number') ? 'has-error' : '' !!} required ">
						<label for="account_number" class="control-label">{{ trans('general.account_number') }}</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
							<input class="form-control" name="account_number" type="text" placeholder="{{trans('general.account_number')}}" required>
							@if ($errors->first('account_number'))
							<span class="help-block">{!! $errors->first('account_number') !!}</span>
							@endif
						</div>
					</div>
					<div class="form-group {!! $errors->has('account_name') ? 'has-error' : '' !!} required ">
						<label for="account_name" class="control-label">{{ trans('general.account_name') }}</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
							<input class="form-control" name="account_name" type="text" placeholder="{{trans('general.account_name')}}">
							@if ($errors->first('account_name'))
							<span class="help-block">{!! $errors->first('account_name') !!}</span>
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