
{{--	<!-- REPAYMENT -->--}}
{{--	<div class="modal fade" id="repayment-{!!$angsuran->id!!}" tabindex="-1">--}}
{{--	    <div class="modal-dialog">--}}
{{--	        <div class="modal-content {!!$angsuran->id!!}">--}}
{{--	            <form method="post" action="{{URL::to('/installment/repayment_store', $angsuran->id)}}" enctype="multipart/form-data">--}}
{{--					{{ csrf_field() }}--}}
{{--					<input type="hidden" class="form-control" name="inst_to" value="{{$angsuran->inst_to}}" id="inst_to">--}}
{{--					<input type="hidden" class="form-control" name="free_id" value="{{$angsuran->id}}" id="free_id">--}}
{{--					<input type="hidden" class="form-control" name="memberNumber" value="{{$angsuran->member_number}}" id="memberNumber">--}}
{{--					<input type="hidden" class="form-control" name="pay_status" value="FREE" id="pay_status">--}}
{{--					<div class="modal-header">--}}
{{--						<h4 class="modal-title">Bayar Pelunasan</h4>--}}
{{--						<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--							<span aria-hidden="true">&times;</span>--}}
{{--						</button>--}}
{{--					</div>--}}
{{--					<div class="modal-body" id="bodyRepayment">--}}
{{--						<?php--}}
{{--							//$loans = App\Models\Loan::where('member_number',$angsuran->member_number)->first();--}}
{{--							$loans = App\Models\Loan::where('loan_number',$angsuran->loan_number)->first();--}}
{{--							$getMember = $loans->member_number;--}}
{{--							$tempos = App\Models\Tempo::where('member_number',$getMember)->where('status','=','UNPAID')->first();--}}
{{--							//$byrCicilan = !empty($loans->pay_month) ? $loans->pay_month : 0;--}}
{{--							$getCutsId = !empty($loans->customer_id) ? $loans->customer_id : NULL;--}}
{{--							$last = App\Models\Installment::where('loan_number',$angsuran->loan_number)->orderBy('id','desc')->first();--}}
{{--							if (!empty($last->inst_to))--}}
{{--							{--}}
{{--								$lastID = $last->inst_to;--}}
{{--							}else {--}}
{{--								$lastID = 0;--}}
{{--							}--}}
{{--							$sisaBulan = App\Models\Installment::where('loan_number',$angsuran->loan_number)->where('status','=','UNPAID')->count();--}}
{{--							$sisaNaBulan = $sisaBulan;--}}
{{--							//dd($sisaNaBulan);--}}
{{--							$sisaBayaran = App\Models\Installment::where('id',$angsuran->id)->where('reminder', '>', 0)->first();--}}
{{--							$sisa = !empty($sisaBayaran->reminder) ? $sisaBayaran->reminder : 0;--}}
{{--							//$sisa = !empty($sisaBayaran->sisa) ? $sisaBayaran->sisa : 0;--}}
{{--							$kontrak = App\Models\CustomerContract::where('customer_id',$getCutsId)->first();--}}
{{--							$tabWajib = !empty($kontrak->m_savings) ? $kontrak->m_savings : 0;--}}
{{--							//$byrTempo = !empty($tempos->total_amount) ? $tempos->total_amount : 0;--}}
{{--							$tabungan = str_replace('.', '', $tabWajib);--}}
{{--							$sisaHutang = $loans->loan_remaining;--}}
{{--							//$wajib = $tabungan ? $tabungan : 0;--}}
{{--							if(!empty($loans->pay_date)){--}}
{{--								$payDate = explode(',', $loans->pay_date);--}}
{{--							} else {--}}
{{--								$payDate = 0;--}}
{{--							}--}}

{{--							$setTempo = !empty($tempos->total_amount) ? $tempos->total_amount : 0;--}}
{{--							$byrTempo = $setTempo;--}}
{{--							$setCicilan = !empty($loans->pay_month) ? $loans->pay_month : 0;--}}
{{--							$byrCicilan = $setCicilan;--}}
{{--							$setwajib = $tabungan ? $tabungan : 0;--}}
{{--							$wajib = $setwajib * $sisaNaBulan;--}}
{{--							$bayar = $byrCicilan + $byrTempo + $sisa;--}}
{{--							$totalBayar = $sisaHutang + $byrTempo;--}}

{{--						?>--}}
{{--						<div id="done-message" class="hide">--}}
{{--		                    <div class="alert alert-info alert-dismissible fade in" role="alert">--}}
{{--		                      <button type="button" class="close">--}}
{{--		                        <span>×</span>--}}
{{--		                      </button>--}}
{{--		                      <strong>Success!</strong>--}}
{{--		                    </div>--}}
{{--		                </div>--}}
{{--						<!--div class="form-group col-md-6">--}}
{{--							<label for="year">{{trans('installment.pay_date')}}</label>--}}
{{--							<input type="date" class="form-control" name="pay_date" value="{{date('Y-m-d')}}" id="pay_date">--}}
{{--						</div-->--}}
{{--						<div class="form-group col-md-12">--}}
{{--							<label for="year">No. Pinjaman</label>--}}
{{--							<input type="text" class="form-control" name="loan_number" value="{{ !empty($loans->loan_number) ? $loans->loan_number : 0}}" id="loan_number">--}}
{{--						</div>--}}
{{--						<div class="form-group col-md-12">--}}
{{--							<label for="transfer">Transfer Masuk</label>--}}
{{--							<input type="text" class="form-control" name="transfer_in" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in" required>--}}
{{--							<span class="text-live">--}}
{{--								<strong id="transfer-msg-err"></strong>--}}
{{--							</span>--}}
{{--						</div>--}}
{{--						<input type="hidden" class="form-control" name="pay_date" value="{{date('Y-m-d')}}" id="pay_date">--}}
{{--						<input type="hidden" class="form-control" name="byrTempo" value="{{$byrTempo}}">--}}
{{--						<input type="hidden" class="form-control" name="byrWajib" value="{{$wajib}}">--}}
{{--						<input type="hidden" class="form-control" name="byrCicilan" value="{{$byrCicilan}} * {{$sisaNaBulan}}">--}}
{{--						<!--div class="form-group col-md-4" style="display:block">--}}
{{--							<label for="tempo">Angsuran</label>--}}
{{--							<input type="text" class="form-control" name="cicilan" value="{{$byrCicilan}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in">--}}
{{--						</div>--}}
{{--						<div class="form-group col-md-4" style="display:block">--}}
{{--							<label for="tempo">Tempo</label>--}}
{{--							<input type="text" class="form-control" name="tempo" value="{{$byrTempo}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in">--}}
{{--						</div>--}}
{{--						<div class="form-group col-md-4" style="display:block">--}}
{{--							<label for="tempo">Wajib</label>--}}
{{--							<input type="text" class="form-control" name="wajib" value="{{!empty($kontrak->m_savings) ? $kontrak->m_savings : 0}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in">--}}
{{--						</div>--}}
{{--						<div class="form-group col-md-4" style="display:block">--}}
{{--							<label for="tempo">Sisa</label>--}}
{{--							<input type="text" class="form-control" name="kurang" value="{{$sisa}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in">--}}
{{--						</div-->--}}
{{--						<div class="form-group col-md-12">--}}
{{--							<label for="name" class="control-label">{{ trans('installment.payment_method') }}</label>--}}
{{--							<select name="payment_method" class="form-control" required>--}}
{{--								<option value="Tunai">{{ trans('installment.cash')}}</option>--}}
{{--								<option value="Transfer">{{ trans('installment.transfer')}}</option>--}}
{{--								<option value="Debit">{{ trans('installment.debit_card')}}</option>--}}
{{--								<option value="Kredit">{{ trans('installment.credit_card')}}</option>--}}
{{--							</select>--}}
{{--						</div>--}}
{{--						<div class="form-group col-md-12">--}}
{{--							<label for="transfer">Jumlah</label>--}}
{{--							<input type="hidden" class="form-control" name="tagihan" value="{{$totalBayar}}">--}}
{{--							<input type="text" class="form-control" name="amount" placeholder="Sisa Hutang: Rp. {{$totalBayar}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="amount" required>--}}
{{--						</div>--}}
{{--						<div class="form-group col-md-12">--}}
{{--							<label for="transfer">Denda</label>--}}
{{--							<input type="text" class="form-control" name="charge" placeholder="Rp. 0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="charge">--}}
{{--						</div>--}}
{{--					</div>--}}
{{--					<div class="modal-footer">--}}
{{--						<button type="button" class="btn btn-default" data-dismiss="modal">{{trans('general.close')}}</button>--}}
{{--						<button type="submit" class="btn btn-primary" id="saveBtn">{{trans('general.save')}}</button>--}}
{{--					</div>--}}
{{--				</form>--}}
{{--	        </div>--}}
{{--	    </div>--}}
{{--	</div>--}}




<?php
	$angsurans = App\Models\Installment::all();
	?>

	@foreach($angsurans as $angsuran)
	<!-- FULL -->
	<div class="modal fade" id="full-{{$angsuran->id}}">
	    <div class="modal-dialog">
	        <div class="modal-content">
	        	<form method="post" action="{{URL::to('/installment/full_store', $angsuran->id)}}" enctype="multipart/form-data">
				{{ csrf_field() }}
	        	<!--form enctype="multipart/form-data" id="fullForm"-->
					<input type="hidden" class="form-control" name="angsuran_id" value="{{$angsuran->id}}" id="angsuran_id">
					<input type="hidden" class="form-control" name="memberNumber" value="{{$angsuran->member_number}}" id="memberNumber">
					<input type="hidden" class="form-control" name="loanNumber" value="{{$angsuran->loan_number}}" id="loanNumber">
					<input type="hidden" class="form-control" name="pay_status" value="FULL" id="pay_status">
		            <!-- Modal Header -->
		            <div class="modal-header">
		                <h4 class="modal-title">Bayar Full</h4>
		                <button type="button" class="close modelClose" data-dismiss="modal">&times;</button>
		            </div>
		            <!-- Modal body -->
		            <div class="modal-body">
		                <div class="form-group col-md-6">
							<label for="year">{{trans('installment.pay_date')}}</label>
							<input type="date" class="form-control" name="pay_date" value="{{date('Y-m-d')}}" id="pay_date" required>
						</div>
						<div class="form-group col-md-6">
							<label for="transfer">Transfer</label>
							<input type="text" class="form-control" name="transfer_in" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in_full">
						</div>
						<?php
							$loans = App\Models\Loan::where('member_number',$angsuran->member_number)->first();
							$tempos = App\Models\Tempo::where('member_number',$angsuran->member_number)->where('status','=','UNPAID')->first();
							$byrCicilan = !empty($loans->pay_month) ? $loans->pay_month : 0 ;
							$getCutsId = !empty($loans->customer_id) ? $loans->customer_id : NULL ;
							$sisaBayaran = App\Models\Installment::where('member_number',$angsuran->member_number)
							->where('reminder', '>', 0)->first();
							$sisa = !empty($sisaBayaran->reminder) ? $sisaBayaran->reminder : 0;
							$kontrak = App\Models\CustomerContract::where('customer_id',$getCutsId)->first();
							$tabWajib = !empty($kontrak->m_savings) ? $kontrak->m_savings : 0;
							$byrTempo = !empty($tempos->total_amount) ? $tempos->total_amount : 0;
							$tabungan = str_replace('.', '', $tabWajib);
							$wajib = $tabungan ? $tabungan : 0;
							//$totalBayar = $byrCicilan + $byrTempo + $tabungan + $sisa;
							$totalBayar = $byrCicilan + $byrTempo;
						?>
						<input type="hidden" class="form-control" name="tagihanFull" value="{{$totalBayar}}" id="tagihanFull">
						<div class="form-group col-md-4">
							<label for="name" class="control-label">{{ trans('installment.payment_method') }}</label>
							<select name="payment_method" class="form-control" required>
								<option value="Tunai">{{ trans('installment.cash')}}</option>
								<option value="Transfer">{{ trans('installment.transfer')}}</option>
								<option value="Debit">{{ trans('installment.debit_card')}}</option>
								<option value="Kredit">{{ trans('installment.credit_card')}}</option>
							</select>
						</div>
						<div id="FullModal">

	                	</div>
		            </div>
		            <!-- Modal footer -->
		            <div class="modal-footer">
		                <!--button type="button" class="btn btn-success" id="SubmitFullForm">Bayar</button-->
		                <button type="submitFull" class="btn btn-success" id="submitFull">Bayar</button>
		                <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Batal</button>
		            </div>
	        	</form>
	        </div>
	    </div>
	</div>

	<!-- FREE -->
	<div class="modal fade" id="free-{!!$angsuran->id!!}" tabindex="-1">
	    <div class="modal-dialog">
	        <div class="modal-content {!!$angsuran->id!!}">
	            <form method="post" action="{{URL::to('/installment/free_store', $angsuran->id)}}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" class="form-control" name="inst_to" value="{{$angsuran->inst_to}}" id="inst_to">
					<input type="hidden" class="form-control" name="free_id" value="{{$angsuran->id}}" id="free_id">
					<input type="hidden" class="form-control" name="memberNumber" value="{{$angsuran->member_number}}" id="memberNumber">
					<input type="hidden" class="form-control" name="pay_status" value="FREE" id="pay_status">
					<div class="modal-header">
						<h4 class="modal-title">Bayar Bebas</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" id="bodyFree">
						<?php
							//$loans = App\Models\Loan::where('member_number',$angsuran->member_number)->first();
							$loans = App\Models\Loan::where('loan_number',$angsuran->loan_number)->first();
							$getMember = $loans->member_number;
							$tempos = App\Models\Tempo::where('member_number',$getMember)->where('status','=','UNPAID')->first();
							$getCutsId = !empty($loans->customer_id) ? $loans->customer_id : NULL;
							$last = App\Models\Installment::where('loan_number',$angsuran->loan_number)->orderBy('id','desc')->first();
							if (!empty($last->inst_to))
							{
								$lastID = $last->inst_to;
							}else {
								$lastID = 0;
							}
							$cariIDSisa = App\Models\Installment::where('loan_number',$angsuran->loan_number)->where('reminder', '>', 0)->orderBy('id','desc')->get();

							foreach($cariIDSisa as $idSisa)
							{
								$getID = $idSisa->id;
								$sisaBayaran = App\Models\Installment::where('id',$getID)->first();
							}
							$kontrak = App\Models\CustomerContract::where('customer_id',$getCutsId)->first();
							$tabWajib = !empty($kontrak->m_savings) ? $kontrak->m_savings : 0;
							$tabungan = str_replace('.', '', $tabWajib);
							if(!empty($loans->pay_date)){
								$payDate = explode(',', $loans->pay_date);
							} else {
								$payDate = 0;
							}

							$jumlah = $payDate ? count($payDate) : 0;

							if($jumlah > 1)
							{
								$setTempo = !empty($tempos->total_amount) ? $tempos->total_amount : 0;
								$byrTempo = $setTempo / 2;
								$setCicilan = !empty($loans->pay_month) ? $loans->pay_month : 0;
								$byrCicilan = $setCicilan / 2;
								$setwajib = $tabungan ? $tabungan : 0;
								$wajib = $setwajib / 2;
								$bayar = $byrCicilan + $byrTempo;
								$totalBayar = $bayar;
							} else {
								if ($sisa > 0)
								{
									$totalBayar = $byrCicilan + $byrTempo + $sisaBayaran->reminder;
									//$totalBayar = $byrCicilan + $byrTempo;
								} else {
									$byrTempo = !empty($tempos->total_amount) ? $tempos->total_amount : 0;
									$byrCicilan = !empty($loans->pay_month) ? $loans->pay_month : 0;
									$wajib = $tabungan ? $tabungan : 0;
									//$totalBayar = $byrCicilan + $byrTempo;
									$totalBayar = $byrCicilan + $byrTempo;
								}
							}
						?>
						<div id="done-message" class="hide">
		                    <div class="alert alert-info alert-dismissible fade in" role="alert">
		                      <button type="button" class="close">
		                        <span>×</span>
		                      </button>
		                      <strong>Success!</strong>
		                    </div>
		                </div>
						<div class="form-group col-md-12">
							<label for="year">No. Pinjaman</label>
							<input type="text" class="form-control" name="loan_number" value="{{ !empty($loans->loan_number) ? $loans->loan_number : 0}}" id="loan_number">
						</div>
						<div class="form-group col-md-12">
							<label for="transfer">Transfer Masuk</label>
							<input type="text" class="form-control" name="transfer_in" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in_free" required>
							<span class="text-live">
								<strong id="transfer-msg-err"></strong>
							</span>
						</div>
						<input type="hidden" class="form-control" name="pay_date" value="{{date('Y-m-d')}}" id="pay_date">
						<input type="hidden" class="form-control" name="byrTempo" value="{{$byrTempo}}">
						<input type="hidden" class="form-control" name="byrWajib" value="{{$wajib}}">
						<input type="hidden" class="form-control" name="byrCicilan" value="{{$byrCicilan}}">
						<div class="form-group col-md-12">
							<label for="name" class="control-label">{{ trans('installment.payment_method') }}</label>
							<select name="payment_method" class="form-control" required>
								<option value="Tunai">{{ trans('installment.cash')}}</option>
								<option value="Transfer">{{ trans('installment.transfer')}}</option>
								<option value="Debit">{{ trans('installment.debit_card')}}</option>
								<option value="Kredit">{{ trans('installment.credit_card')}}</option>
							</select>
						</div>
						<div class="form-group col-md-12">
							<label for="transfer">Jumlah</label>
							<input type="hidden" class="form-control" name="tagihan" value="{{$totalBayar}}">
							<input type="text" class="form-control" name="amount" placeholder="Cicilan : {{$byrCicilan}} Tempo : {{$byrTempo}} Wajib: {{$wajib}} Total Bayar: Rp. {{$totalBayar}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="amount_free" required>
						</div>
						<div class="form-group col-md-12">
							<label for="transfer">Denda</label>
							<input type="text" class="form-control" name="charge" placeholder="Rp. 0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="charge">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">{{trans('general.close')}}</button>
						<button type="submit" class="btn btn-primary" id="saveBtnFree">Bayar</button>
					</div>
				</form>
	        </div>
	    </div>
	</div>
	@endforeach