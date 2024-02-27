@extends('layouts.app')
@section('content')

@include('error.error-notification')	
<meta name="csrf-token" content="{{ csrf_token() }}">
	<div class="box">
		<form method="post" action="{{URL::to('transaction/payment/store')}}" enctype="multipart/form-data">
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
									<option value="" data-total="0" selected>-- Select Purchase Code --</option>
									@foreach($purchases as $purchase)
										<option value="{{$purchase->trans_code}}" data-id="{{$purchase->id}}" data-code="{{$purchase->trans_code}}" data-total="{{$purchase->total}}">{{ $purchase->trans_code }}</option>
									@endforeach
								</select>
							</div>
							<input type="text" class="form-control" name="trans_code" style="margin-top: 4px; height: auto;" id="trans_code"/>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="year">{{trans('general.customer')}}</label>
						<input type="hidden" name="cust_id" id="cust_id" class="form-control">
						<div class="input-group">
							<div class="input-group-addon">
								<select class="input select2 select2-hidden-accessible" onchange="customer" style="width: 100%;" aria-hidden="true" name="customer" id="customer">
									<option value="" data-name="" selected>-- Select Customer --</option>
									@foreach($customers as $anggota)
										<option value="{{$anggota->member_number}}" data-code="{{$anggota->id}}" data-id="{{$anggota->member_number}}" data-name="{{$anggota->name}}">{{ $anggota->name }}</option>
									@endforeach
								</select>
							</div>
							<input type="text" class="form-control" name="customer_name" style="margin-top: 4px; height: auto;" id="customer_name"/>
						</div>
					</div>
				</div>
				<div class="form-group col-sm-4">
					<label for="year">{{trans('installment.pay_date')}}</label>	
					<input type="date" class="form-control" name="pay_date"  value="{{date('Y-m-d')}}" id="pay_date">
				</div>				
				<div class="form-group col-sm-4">
					<label for="name" class="control-label">{{ trans('general.transaction_type') }}</label>						
					<!--select name="transaction_type" onchange="tipe();" class="form-control" id="transaction_type" required>
						<option value="0" selected>-- Select --</option>
						<option value="installment" data-installment="installment">{{ trans('installment.installment')}}</option>
						<option value="savings" data-saving="savings">{{ trans('installment.savings')}}</option>
						<option value="tempo" data-tempo="tempo">Tempo</option>
					</select-->
					<select name="transaction_type" class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" id="transaction_type">
						<option value="0">======={{trans('general.choice')}}=======</option>
						@foreach($types as $account)								
						<option value="{{$account->transaction_type}}" data-desc="{{$account->description}}">{{$account->transaction_type}} | {{$account->description}}</option>
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
				<!--div class="form-group col-sm-4" id="installment" style="display:none">
					<label for="year">{{trans('installment.loan')}}</label>
					<input type="text" class="form-control" name="jumlah_pinjaman" id="jumlah_pinjaman">
					<label for="year">{{trans('installment.principal')}}</label>
					<input type="text" class="form-control" name="pokok_cicilan" id="pokok_cicilan">
					<label for="year">{{trans('installment.rates')}}</label>
					<input type="text" class="form-control" name="bunga_cicilan" id="bunga_cicilan">
					<label for="year">Total</label>
					<input type="text" class="form-control" name="total_cicilan" id="total_cicilan">
				</div>
				<div class="form-group col-sm-4" id="savings" style="display:none">
					<label for="pokok"><input type="radio" class="tabRadio" name="saving_type" value="pokok" id="pokok_tab" checked="true">Pokok</label>
					<label for="wajib"><input type="radio" class="tabRadio" name="saving_type" value="wajib" id="wajib_tab" checked="false">Wajib</label>
					<label for="sukarela"><input type="radio" class="tabRadio" name="saving_type" value="sukarela" id="sukarela_tab" checked="false">Sukarela</label>
				</div>
				<div class="form-group col-sm-4" id="tempo" style="display:none">
					<label for="year">{{trans('installment.principal')}}</label>
					<input type="text" class="form-control" name="pokok_tempo" id="pokok_tempo">
					<label for="year">{{trans('installment.rates')}}</label>
					<input type="text" class="form-control" name="bunga_tempo" id="bunga_tempo">
					<label for="year">Total</label>
					<input type="text" class="form-control" name="total_tempo" id="total_tempo">
				</div-->
				<div class="form-group col-sm-4">
					<label for="year">STATUS</label>	
					<select name="status" class="form-control" required>
						<!--option value="0">Status</option-->
						<option value="UNPAID">UNPAID</option>
						<option value="PAID">PAID</option>								
						<option value="PARTIAL">PARTIAL</option>
						<option value="CORRUPT">CORRUPT</option>
					</select>
				</div>
				<!--div class="form-group col-sm-4" id="pay_status">
					<label><input type="radio" name="pay_status" class="myRadio" id="full" value="FULL" checked="">FULL</label>
					<label><input type="radio" name="pay_status" class="myRadio" id="free" value="FREE" checked="">FREE</label>
				</div>
				<div class="form-group col-sm-4" id="pay_free">					
					<label><input type="radio" name="full_free" id="pay_principal" value="pay_principal" checked="">POKOK</label>
					<label><input type="radio" name="full_free" id="pay_rates" value="pay_rates" checked="">BUNGA</label>
				</div-->
				<div class="form-group col-sm-4 {!! $errors->has('amount') ? 'has-error' : '' !!} required ">
					<label for="amount">{{trans('general.amount')}}</label>
					<input type="text" class="form-control" name="amount" id="amount" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
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