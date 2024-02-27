@foreach ($users as $user)
<div id="Delete-{{$user->id}}" class="modal fade" id="dangerModal" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-danger" role="document">
		<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('/employee/delete', $user->id) }}">
		{{ csrf_field() }}
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">{{trans('general.delete')}} {{$user->name}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<p>Employee with name : <b>{{$user->name}}</b>, are you sure ?</p>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
					<button class="btn btn-danger" type="submit">{{trans('general.delete')}}</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endforeach