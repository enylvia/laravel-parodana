<div class="modal fade" id="free-{{$angsuran->id}}">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
		<form method="post" action="{{URL::to('/installment/free_store', $angsuran->id)}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input type="text" class="form-control" name="inst_to" value="{{$angsuran->inst_to}}" id="inst_to">
			<input type="hidden" class="form-control" name="free_id" value="{{$angsuran->id}}" id="free_id">
			<input type="hidden" class="form-control" name="memberNumber" value="{{$memberNumber}}" id="memberNumber">
			<input type="hidden" class="form-control" name="pay_status" value="FREE" id="pay_status">
			<div class="modal-header">
				<h4 class="modal-title">Bayar Bebas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="done-message" class="hide">
                    <div class="alert alert-info alert-dismissible fade in" role="alert">
                      <button type="button" class="close">
                        <span>×</span>
                      </button>
                      <strong>Success!</strong>
                    </div>
                </div>
				<div class="form-group col-md-6">
					<label for="year">{{trans('installment.pay_date')}}</label>	
					<input type="date" class="form-control" name="pay_date" value="{{date('Y-m-d')}}" id="pay_date">
				</div>
				<div class="form-group col-md-6">
					<label for="transfer">Transfer</label>	
					<input type="text" class="form-control" name="transfer_in" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in" required>
					<span class="text-live">
						<strong id="transfer-msg-err"></strong>
					</span>
				</div>
				<?php
					$loans = App\Models\Loan::where('member_number',$memberNumber)->first();
					$tempos = App\Models\Tempo::where('member_number',$memberNumber)->where('status','=','UNPAID')->first();
					$byrCicilan = $loans->pay_month;
					$getCutsId = $loans->customer_id;
					$last = App\Models\Installment::where('member_number',$memberNumber)->orderBy('id','desc')->first();
					if (!empty($last->inst_to))
					{
						$lastID = $last->inst_to;
					}else {
						$lastID = 0;
					}
					$sisaBayaran = App\Models\Installment::where('member_number',$memberNumber)->where('reminder', '>', 0)->first();
					$sisa = !empty($sisaBayaran->reminder) ? $sisaBayaran->reminder : 0;
					$kontrak = App\Models\CustomerContract::where('customer_id',$getCutsId)->first();
					$byrTempo = !empty($tempos->total_amount) ? $tempos->total_amount : 0;
					$tabungan = str_replace('.', '', $kontrak->m_savings);
					if ($sisa > 0)
					{						
						$totalBayar = $sisa;
					} else {
						$totalBayar = $byrCicilan + $byrTempo + $tabungan;
					}					
				?>
				<div class="form-group col-md-4" style="display:block">
					<label for="tempo">Angsuran</label>	
					<input type="text" class="form-control" name="cicilan" value="{{$byrCicilan}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in">
				</div>
				<div class="form-group col-md-4" style="display:block">
					<label for="tempo">Tempo</label>	
					<input type="text" class="form-control" name="tempo" value="{{$byrTempo}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in">
				</div>
				<div class="form-group col-md-4" style="display:block">
					<label for="tempo">Wajib</label>	
					<input type="text" class="form-control" name="wajib" value="{{!empty($kontrak->m_savings) ? $kontrak->m_savings : 0}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in">
				</div>
				<div class="form-group col-md-4" style="display:block">
					<label for="tempo">Sisa</label>	
					<input type="text" class="form-control" name="kurang" value="{{$sisa}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in">
				</div>
				<div class="form-group col-md-4">
					<label for="name" class="control-label">{{ trans('installment.payment_method') }}</label>						
					<select name="payment_method" class="form-control" required>
						<option value="Tunai">{{ trans('installment.cash')}}</option>
						<option value="Transfer">{{ trans('installment.transfer')}}</option>
						<option value="Debit">{{ trans('installment.debit_card')}}</option>
						<option value="Kredit">{{ trans('installment.credit_card')}}</option>
					</select>
				</div>
				<div class="form-group col-md-4">
					<label for="transfer">Jumlah</label>	
					<input type="text" class="form-control" name="tagihan" value="{{$totalBayar}}">
					<input type="text" class="form-control" name="amount" placeholder="Total: Rp. {{$totalBayar}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="amount" required>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">{{trans('general.close')}}</button>
				<button type="submit" class="btn btn-primary" id="saveBtn">{{trans('general.save')}}</button>
			</div>
		</form>
		</div>
	</div>
</div>

@section('js')
	<script type="text/javascript">
		$('body').on('click', '#saveBtn', function(){
			var id = $(this).attr('free_id');
			//alert(id);
			var freeForm = $("#freeForm");
			var formData = freeForm.serialize();
			$( '#transfer-msg-err' ).html( "" );

			$.ajax({
				url: "{!! url('/installment/free_store/" + id + "') !!}",
				headers: {
					'X-CSRF-Token': $('meta[name="_token"]').attr('content')
				},
				type:'POST',
				data:formData,
				success:function(data) {
					console.log(data);
					if(data.query) {
						if(data.query.transfer_in){
							$( '#transfer-msg-err' ).html( data.query.transfer_in[0] );
						}						
					}
					if(data.success) {
						$('#done-message').removeClass('hide');
						setInterval(function(){ 
							$('#free').modal('hide');
							$('#done-message').addClass('hide');
						}, 3000);
					}
				},
			});
		});
	</script>
@endsection