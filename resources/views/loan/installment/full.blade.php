<div class="modal fade" id="full-{{$angsuran->id}}">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form method="post" action="{{URL::to('/installment/full_store', $angsuran->id)}}" enctype="multipart/form-data">
			{{ csrf_field() }}			
			<input type="hidden" class="form-control" name="loanNumber" value="{{$loanNumber}}" id="loanNumber">
			<input type="hidden" class="form-control" name="pay_status" value="FULL" id="pay_status">
				<div class="modal-header">
					<h4 class="modal-title">Bayar Full</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group col-md-6">
						<label for="year">{{trans('installment.pay_date')}}</label>	
						<input type="date" class="form-control" name="pay_date" value="{{date('Y-m-d')}}" id="pay_date" required>
					</div>
					<div class="form-group col-md-6">
						<label for="transfer">Transfer</label>	
						<input type="text" class="form-control" name="transfer_in" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in">
					</div>
					<?php 
						$loans = App\Models\Loan::where('loan_number',$loanNumber)->first();
						$tempos = App\Models\Tempo::where('member_number',$loans->member_number)->where('status','=','UNPAID')->first();
						$byrCicilan = $loans->pay_month;
						$getCutsId = $loans->customer_id;
						$sisaBayaran = App\Models\Installment::where('loan_number',$loanNumber)
						->where('reminder', '>', 0)->first();
						$sisa = !empty($sisaBayaran->reminder) ? $sisaBayaran->reminder : 0;
						$kontrak = App\Models\CustomerContract::where('customer_id',$getCutsId)->first();
						$byrTempo = !empty($tempos->total_amount) ? $tempos->total_amount : 0;
						$tabungan = str_replace('.', '', $kontrak->m_savings);
						//$totalBayar = $byrCicilan + $byrTempo + $tabungan + $sisa;
						$totalBayar = $byrCicilan + $byrTempo + $sisa;
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
					<div class="form-group col-md-6">
						<label for="name" class="control-label">{{ trans('installment.payment_method') }}</label>						
						<select name="payment_method" class="form-control" required>
							<option value="Tunai">{{ trans('installment.cash')}}</option>
							<option value="Transfer">{{ trans('installment.transfer')}}</option>
							<option value="Debit">{{ trans('installment.debit_card')}}</option>
							<option value="Kredit">{{ trans('installment.credit_card')}}</option>
						</select>
					</div>
					<!--div class="form-group col-md-6">
						<label for="transfer">Jumlah</label>	
						<input type="text" class="form-control" name="amount" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="amount">
					</div-->
					<input type="hidden" class="form-control" name="tagihan" value="{{$byrCicilan}}" id="amount">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="close">{{trans('general.close')}}</button>
					<button type="submit" class="btn btn-primary">{{trans('general.save')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>