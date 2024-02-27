<!-- Hapus Modal-->
@foreach ($items as $item)
	<div id="Delete-{{$item->sidemenu_id}}" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<form class="form-horizontal" role="form" method="post" action="{{ url('/menu/management/delete') }}">
			  {{ csrf_field() }}
			  <input type="text" name="menu" value="{{$item->sidemenu_id}}">
			  <input type="text" name="role" value="{{$item->role_id}}">
			<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Delete {{$item->name}}</h4>
				  </div>
				  <div class="modal-body">
					<p>Name  : <b>{{$item->display}}</b>, are you sure to delete ?</p>
				  </div>
				  <div class="modal-footer">
					<button class="btn btn-primary" type="submit" id="hapus">
						<span class="glyphicon glyphicon-trash"></span> Delete
					  </button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				  </div>
				</div>
			</form>
		</div>
	</div>
@endforeach