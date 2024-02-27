<div class="modal fade in" id="create" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form method="post" action="{{route('menu.store')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">{{trans('general.role')}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group ">
						<label for="provinsi">{{trans('general.role')}}</label>
						<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="role" id="role">
							<option value="">=== Pilih Role ===</option>
							@foreach($roles as $role)
							  <option value="{{$role->id}}">{{ $role->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group ">
						<label for="provinsi">{{trans('general.menu')}}</label>
						<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="sidemenu" id="sidemenu">
							<option value="">=== Pilih Menu ===</option>
							@foreach($menus as $value)
							  <option value="{{$value->id}}">{{ $value->display }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group ">
						<label for="provinsi">{{trans('general.menu_access')}}</label>
						<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="menu_access" id="menu_access">
							<option value="0">False</option>
							<option value="1">True</option>
						</select>
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