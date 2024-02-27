<?php 
	$purchases = App\Models\Purchase::all();
?>
@foreach($purchases as $purchase)
	<div id="Delete-{{$purchase->id}}" class="modal fade" id="dangerModal" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-danger" role="document">
			<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('transaction/purchase/delete', $purchase->trans_code) }}">
			{{ csrf_field() }}
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">{{trans('general.delete')}} {{$purchase->trans_code}}</h4>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					</div>
					<div class="modal-body">
						<p>Transaction with Number : <b>{{$purchase->trans_code}}</b>, are you sure ?</p>
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
