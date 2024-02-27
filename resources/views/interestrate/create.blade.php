<div class="modal fade in" id="create" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form method="post" action="{{route('interestrate.store')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">{{trans('general.interest_rate')}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group {!! $errors->has('name') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.name') }}</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
							<input class="form-control" name="name" type="text" placeholder="{{trans('general.name')}}" required>
							@if ($errors->first('name'))
							<span class="help-block">{!! $errors->first('name') !!}</span>
							@endif
						</div>
					</div>
					<div class="form-group {!! $errors->has('rate') ? 'has-error' : '' !!} required ">
						<label for="name" class="control-label">{{ trans('general.rate') }}</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-id-box-o"></i></div>
							<input class="form-control" name="rate" type="text" placeholder="{{trans('general.rate')}}">
							@if ($errors->first('rate'))
							<span class="help-block">{!! $errors->first('rate') !!}</span>
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