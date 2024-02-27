@foreach ($transactions as $transaction)
<div id="Delete-{{$transaction->transaction_no}}" class="modal fade" id="dangerModal" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-danger" transaction="document">
		<form class="form-horizontal" transaction="form" method="GET" action="{{ URL::to('/operational/delete', $transaction->transaction_no) }}">
		{{ csrf_field() }}
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">{{trans('general.delete')}} {{$transaction->transaction_no}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<p>Transaction with type : <b>{{$transaction->transaction_no}}</b>, are you sure ?</p>
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