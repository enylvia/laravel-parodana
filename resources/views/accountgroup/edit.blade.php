<?php
	$accounts = App\Models\AccountGroup::all();
?>			
@foreach($accounts as $account)		
<div id="Edit-{{$account->id}}" class="modal fade" id="dangerModal" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/account/update', $account->id) }}">
		{{ csrf_field() }}
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">{{trans('general.edit')}} {{$account->account_name}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<select class="form-control " name="parents" id="parents">							
							<?php 
								$roll = [];       
								$groups = App\Models\AccountGroup::All();
								$roll[] = $account->id;
							?>
							@foreach ($groups as $value)
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
							<input class="form-control" name="account_number" type="text" value="{{$account->account_number}}" required>
							@if ($errors->first('account_number'))
							<span class="help-block">{!! $errors->first('account_number') !!}</span>
							@endif
						</div>
					</div>
					<div class="form-group {!! $errors->has('account_name') ? 'has-error' : '' !!} required ">
						<label for="account_name" class="control-label">{{ trans('general.account_name') }}</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
							<input class="form-control" name="account_name" type="text" value="{{$account->account_name}}">
							@if ($errors->first('account_name'))
							<span class="help-block">{!! $errors->first('account_name') !!}</span>
							@endif
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
					<button class="btn btn-danger" type="submit">{{trans('general.save')}}</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endforeach