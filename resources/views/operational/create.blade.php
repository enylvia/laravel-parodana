<?php 
	$types = App\Models\TransactionType::groupBy('transaction_type')->select('transaction_type','description', \DB::raw('count(*) as total'))->get();		
	$accounts = App\Models\AccountGroup::all();
?>
<div id="Create" class="modal fade in" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form method="post" action="{{URL::to('/operational/store')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="modal-header">
					<h4 class="modal-title">{{trans('general.operational')}}</h4>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group col-md-12 {!! $errors->has('transaction_type') ? 'has-error' : '' !!} required ">
						<label for="transaction_type" class="control-label">{{ trans('general.transaction_type') }}</label>						
							<select name="transaction_type[]" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" id="transaction_type">
								<option value="0">=======PILIH=======</option>
								@foreach($types as $account)								
								<option value="{{$account->transaction_type}}" data-desc="{{$account->description}}">{{$account->transaction_type}} | {{$account->description}}</option>
								@endforeach
							</select>
					</div>										
					<div class="form-group col-md-12 {!! $errors->has('description') ? 'has-error' : '' !!} required ">
						<label for="description" class="control-label">{{ trans('general.description') }}</label>						
						<input class="form-control" name="description" type="text" id="description" required>						
					</div>
					<div class="form-group col-md-12 {!! $errors->has('amount') ? 'has-error' : '' !!} required ">
						<label for="amount" class="control-label">{{ trans('general.amount') }}</label>						
						<input class="form-control" name="amount" type="text" id="amount" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>						
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

@section('js')
<script>	
	window.onload = function(){
		$("#contract_number").change(function () {
			var ambilProvisi = $(this).find(':selected').attr('data-provisi')
			var ambilAsuransi = $(this).find(':selected').attr('data-asuransi')
			var ambilMaterai = $(this).find(':selected').attr('data-materai')
			$('#provisi').val(ambilProvisi);
			$('#asuransi').val(ambilAsuransi);
			$('#materai').val(ambilMaterai);
		});
	}
</script>
@endsection