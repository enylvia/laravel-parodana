@extends('layouts.app')
@section('content')

@foreach($payments as $payment)	
<meta name="csrf-token" content="{{ csrf_token() }}">
	<div class="box">
		@include('error.error-notification')
		<form method="post" action="{{URL::to('transaction/payment/update', $payment->id)}}" enctype="multipart/form-data">
		{{ csrf_field() }}
			<div class="box-header">
			</div>
			<div class="box-body">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="year">{{trans('general.purchase_code')}}</label>
						<div class="input-group">
							<div class="input-group-addon">
								<select class="input select2" onchange="purchase_code" style="background: transparent; border: 0; width: 100%;" aria-hidden="true" name="purchase_code" id="purchase_code" required>
									<?php
										$roll = [];
										$purchases = App\Models\Purchase::All();                        
										$roll[] = $payment->purchase_no;
									?>
									@foreach($purchases as $purchase)
										@if(in_array($purchase->id, $roll))
											<option value="{{$purchase->trans_code}}" data-id="{{$purchase->id}}" data-code="{{$purchase->trans_code}}" data-total="{{$purchase->total}}">{{ $purchase->trans_code }}</option>										  
										@else											
											<option value="{{$purchase->trans_code}}" data-id="{{$purchase->id}}" data-code="{{$purchase->trans_code}}" data-total="{{$purchase->total}}">{{ $purchase->trans_code }}</option>
										@endif 
									@endforeach
								</select>
							</div>
							<input type="text" class="form-control" name="trans_code" value="{{$payment->transaction_code}}" style="margin-top: 4px; height: auto;" id="trans_code"/>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="year">{{trans('general.customer')}}</label>
						<input type="hidden" name="cust_id" id="cust_id" value="{{$payment->cust_id}}" class="form-control">
						<div class="input-group">
							@if($payment->cust_id = 'undefined')
								<div class="input-group-addon">
									<select class="input select2 select2-hidden-accessible" onchange="customer" style="width: 100%;" aria-hidden="true" name="customer" id="customer">
										<option value="{{$payment->customer_name}}" selected="true">{{$payment->customer_name}}</option>								
									</select>
								</div>
								<input type="text" class="form-control" name="customer_name" value="{{$payment->customer_name}}" style="margin-top: 4px; height: auto;" id="customer_name"/>
							@else
							<div class="input-group-addon">
								<select class="input select2 select2-hidden-accessible" onchange="customer" style="width: 100%;" aria-hidden="true" name="customer" id="customer">
									<?php
										$roll = [];
										$customers = App\Models\Customer::All();                        
										$roll[] = $payment->cust_id;
									?>
									@foreach($customers as $anggota)
										@if(in_array($anggota->id, $roll))
											<option value="{{$anggota->member_number}}" data-code="{{$anggota->id}}" data-id="{{$anggota->member_number}}" data-name="{{$anggota->name}}">{{ $anggota->name }}</option>
										@else 											
											<option value="{{$anggota->member_number}}" data-code="{{$anggota->id}}" data-id="{{$anggota->member_number}}" data-name="{{$anggota->name}}">{{ $anggota->name }}</option>
										@endif
									@endforeach
								</select>
							</div>
							<input type="text" class="form-control" name="customer_name" value="{{$payment->customer_name}}" style="margin-top: 4px; height: auto;" id="customer_name"/>
							@endif
						</div>
					</div>
				</div>
				<div class="form-group col-sm-4">
					<label for="year">{{trans('installment.pay_date')}}</label>	
					<input type="date" class="form-control" name="pay_date"  value="{{$payment->pay_date}}" id="pay_date">
				</div>				
				<div class="form-group col-sm-4">
					<label for="name" class="control-label">{{ trans('general.transaction_type') }}</label>
					<select name="transaction_type" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" id="transaction_type">						
						<?php
							$roll = [];
							$types = App\Models\TransactionType::All();                        
							$roll[] = $payment->transaction_type;
						?>
						@foreach($types as $account)
							@if(in_array($account->id, $roll))
							  <option value="{{$account->transaction_type}}" selected="true">{{$account->transaction_type}} | {{$account->description}}</option>
							@else
							  <option value="{{$account->transaction_type}}" data-desc="{{$account->description}}">{{$account->transaction_type}} | {{$account->description}}</option>
							@endif 
						@endforeach
					</select>
				</div>				
				<div class="form-group col-sm-4">
					<label for="name" class="control-label">{{ trans('installment.payment_method') }}</label>						
					<select name="payment_method" class="form-control" required>
						<option value="Tunai">{{ trans('installment.cash')}}</option>
						<option value="Transfer">{{ trans('installment.transfer')}}</option>
						<option value="Debit">{{ trans('installment.debit_card')}}</option>
						<option value="Kredit">{{ trans('installment.credit_card')}}</option>
					</select>
				</div>
				<div class="form-group col-sm-4">
					<label for="year">STATUS</label>	
					<select name="status" class="form-control" required>
						@if($payment->status == 'PAID')						
							<option value="UNPAID">UNPAID</option>
							<option value="PAID" selected>PAID</option>								
							<option value="PARTIAL">PARTIAL</option>
							<option value="CORRUPT">CORRUPT</option>
						@elseif($payment->status == 'UNPAID')
							<option value="UNPAID" selected>UNPAID</option>
							<option value="PAID">PAID</option>								
							<option value="PARTIAL">PARTIAL</option>
							<option value="CORRUPT">CORRUPT</option>
						@elseif($payment->status == 'PARTIAL')						
							<option value="UNPAID">UNPAID</option>
							<option value="PAID">PAID</option>								
							<option value="PARTIAL" selected>PARTIAL</option>
							<option value="CORRUPT">CORRUPT</option>
						@elseif($payment->status == 'CORRUPT')
							<option value="UNPAID">UNPAID</option>
							<option value="PAID">PAID</option>								
							<option value="PARTIAL">PARTIAL</option>
							<option value="CORRUPT" selected>CORRUPT</option>
						@else
							<option value="" selected="true">UNPAID</option>
							<option value="UNPAID">UNPAID</option>
							<option value="PAID">PAID</option>								
							<option value="PARTIAL">PARTIAL</option>
							<option value="CORRUPT">CORRUPT</option>
						@endif											
					</select>
				</div>
				<div class="form-group col-sm-4 {!! $errors->has('amount') ? 'has-error' : '' !!} required ">
					<label for="amount">{{trans('general.amount')}}</label>
					<input type="text" class="form-control" name="amount" value="{{$payment->amount}}" id="amount" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
					@if ($errors->first('amount'))
						<span class="help-block">{!! $errors->first('amount') !!}</span>
					@endif
				</div>
			</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-primary">{{trans('general.submit')}}</button>
			</div>
		</form>
	</div>
	@endforeach
@endsection

@section('js')	
	<!--script type="text/javascript">

    // CSRF Token
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function(){

      $( "#customer" ).select2({
        ajax: { 
          url: "{{route('payment.loadcustomer')}}",
          type: "post",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              _token: CSRF_TOKEN,
              search: params.term // search term
            };
          },
          processResults: function (response) {
            return {
              results: response
            };
          },
          cache: true
        }

      });

    });
    </script-->
	<script type="text/javascript">	
		function tipe(){
		
			//$('transaction_type').on('change',function(e){
			  var status = $('#transaction_type option:selected').val();
			  var x = document.getElementById("installment");
			  var y = document.getElementById("savings");
			  var z = document.getElementById("tempo");
			  var payFree = document.getElementById("pay_free");
			  var payStatus = document.getElementById("pay_status");
			  if(status == 0)
			  {
				x.style.display = 'none';
				y.style.display = 'none';
				z.style.display = 'none';
			  }
			  if(status == "installment")
			  {
				x.style.display = 'block';
				y.style.display = 'none';
				z.style.display = 'none';
				payStatus.style.display = 'block';
			  }
			  if(status == "savings")
			  {
				x.style.display = 'none';
				y.style.display = 'block';
				z.style.display = 'none';
				payFree.style.display = 'none';
				payStatus.style.display = 'none';
			  }
			  if(status == "tempo")
			  {
				x.style.display = 'none';
				y.style.display = 'none';
				z.style.display = 'block';
				payStatus.style.display = 'block';
			  }			  
			//});
		
		}
						
	</script>
	
	<script>		
		
		$('input[name="full_free"]').prop('checked', false);
		var radio = document.querySelectorAll(".myRadio");
		var radioTab = document.querySelectorAll("tabRadio");
		var a = document.getElementById("full");
		var b = document.getElementById("free");
		var x = document.getElementById("pay_rates");
		var y = document.getElementById("pay_principal");
		var z = document.getElementById("pay_free");
		  
		function checkBox(e){
			c = e.target.value;
			//alert(c);
			if(c == "FULL")
			{
				z.style.display = 'none';
				$('input[name="full_free"]').prop('checked', false);
			}
			if(c == "FREE")
			{
				z.style.display = 'block';
			}
		}

		radio.forEach(check => {
			check.addEventListener("click", checkBox);
		});
		radioTab.forEach(check => {
			check.addEventListener("click", checkBox);
		});
    </script>
	
	<script type="text/javascript">
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$(document).ready(function(){
		$("#customer").change(function () {
			var ids = $(this).find(':selected').attr('data-id');
			var code = $(this).find(':selected').attr('data-code');
			var dName = $(this).find(':selected').attr('data-name');			
			var installment = $(this).find(':selected').attr('data-installment');
			var saving = $(this).find(':selected').attr('data-saving');
			var tempo = $(this).find(':selected').attr('data-tempo');
			var pokokcicilan = document.getElementById('pokok_cicilan');
			var bungacicilan = document.getElementById('bunga_cicilan');
			var pokoktempo = document.getElementById('pokok_tempo');
			var bungatempo = document.getElementById('bunga_tempo');
			var pokoktab = document.getElementById('pokok_tab');
			var wajibtab = document.getElementById('wajib_tab');
			var sukarelatab = document.getElementById('sukarela_tab');
			//var angsuran = document.getElementById('angsuran');
			//var tabungan = document.getElementById('tabungan');
			//var tempor = document.getElementById('tempor');
			//alert(ids);
			document.getElementById('cust_id').value = code;
			if (dName == "") {
				document.getElementById('customer_name').value = "";
			} else {
				document.getElementById('customer_name').value = dName;
			}
						
			//$.ajax({
			//	type:"POST",
			//	url: "{{route('payment.loadpayment')}}",
			//	data: { _token: CSRF_TOKEN, customer: ids },
			//	dataType: 'json',
			//	success: function(response){					
			//		$.each(response, function(index, subcatObj){
			//			if (installment == "") {
			//				document.getElementById('jumlah_pinjaman').value = 0;
			//				document.getElementById('pokok_cicilan').value = 0;
			//				document.getElementById('bunga_cicilan').value = 0;
			//				document.getElementById('total_cicilan').value = 0;							
			//			} else {
			//				document.getElementById('jumlah_pinjaman').value = subcatObj.jumlah_pinjaman;
			//				document.getElementById('pokok_cicilan').value = subcatObj.pokok_cicilan;
			//				document.getElementById('bunga_cicilan').value = subcatObj.bunga_cicilan;
			//				document.getElementById('total_cicilan').value = subcatObj.total_cicilan;
			//			} 												
						
			//			if (tempo == "") {
			//				document.getElementById('pokok_tempo').value = 0;
			//				document.getElementById('bunga_tempo').value = 0;
			//				document.getElementById('total_tempo').value = 0;							
			//			} else {
			//				document.getElementById('pokok_tempo').value = subcatObj.pokok_tempo;
			//				document.getElementById('bunga_tempo').value = subcatObj.bunga_tempo;
			//				document.getElementById('total_tempo').value = subcatObj.total_tempo;							
			//			}
			//		});
			//	},
			//	error: function (response, textStatus, errorThrown) {
			//		console.log(response);
			//	}
			//});
		});
	});
	</script>
	<script type="text/javascript">	
		$(document).ready(function(){
			$("#purchase_code").change(function () {
				var ids = $(this).find(':selected').attr('data-id');				
				var code = $(this).find(':selected').attr('data-code');
				var totPurchase = $(this).find(':selected').attr('data-total');
				document.getElementById('trans_code').value = code;	
				document.getElementById('amount').value = totPurchase;
				
				if (totPurchase == 0) {
					document.getElementById('amount').value = 0;
					document.getElementById('trans_code').value = "";
				} else {
					document.getElementById('amount').value = totPurchase;
				}
			});
		});
	</script>
	
@endsection