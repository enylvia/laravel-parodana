@extends('layouts.app')
@section('content')
	@include('error.error-notification')
	
	<div class="box">
		@foreach($loans as $key => $loan)
		<form method="post" action="{{URL::to('/installment/loan/update', $loan->member_number)}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="box-header">
			</div>
			<div class="box-body">				
					<?php 
						$customer = App\Models\Customer::where('member_number',$loan->member_number)->first();
						$kontrak = App\Models\CustomerContract::where('customer_id',$loan->customer_id)->first();
					?>
					<div class="form-group col-sm-4">
						<label for="year">{{trans('general.customer')}}</label>	
						<input type="text" class="form-control" name="cuatomer_name" id="customer_name" value="{{ !empty($customer->name) ? $customer->name : '' }}" disabled>
					</div>
					<div class="form-group col-sm-4">
						<label for="year">{{trans('general.member_number')}}</label>	
						<input type="text" class="form-control" name="member_number" id="member_number" value="{!!$loan->member_number!!}" disabled>
					</div>
					<div class="form-group col-sm-4">
						<label for="year">{{trans('general.contract_number')}}</label>	
						<input type="text" class="form-control" name="contract_number" id="contract_number" value="{{$loan->contract_number}}" disabled>
					</div>
					<div class="form-group col-sm-4">
						<label for="year">{{trans('installment.contract_date')}}</label>	
						<input type="date" class="form-control" name="contract_date" id="contract_date" value="{{ $loan->contract_date }}">
					</div>
					<div class="form-group col-sm-4">
						<label for="year">{{trans('installment.start_month')}}</label>	
						<input type="date" class="form-control" name="start_month" id="start_month" value="{{ $loan->start_month }}">
					</div>
					<div class="form-group col-sm-4">
						<label for="year">{{trans('installment.pay_date')}}</label>	
						<!--input type="number" class="form-control" name="pay_date" id="pay_date" value="{{$loan->pay_date}}"-->
						<select name="payday_date" class="form-control" id="pay_date">
							<option selected="selected">{{trans('general.choice')}}</option>							
							<?php
								$roll = [];
								$tanggals = ['1','2','3','4','5','6','7','8','9','10',
								'11','12','13','14','15','16','17','18','19','20',
								'21','22','23','24','25','26','27','28','29','30','31'];
								$roll[] = $loan->pay_date;
							?>
							@foreach($tanggals as $tanggal)
								@if(in_array($tanggal, $roll))
								  <option value="{{ $tanggal }}" selected="true">{{ $tanggal }}</option>
								@else
								  <option value="{{ $tanggal }}">{{ $tanggal }}</option>
								@endif 
							@endforeach
						</select>
					</div>
					<div class="form-group col-sm-4">
						<label for="year">{{trans('loan.loan_amount')}}</label>	
						<input type="text" class="form-control" name="loan_amount" id="loan_amount" value="{{ number_format($loan->loan_amount, 0, ',' , '.') }}" onkeydown="return numbersonly(this, event);rumusLoan()" onkeyup="javascript:tandaPemisahTitik(this); rumusLoan()">
					</div>
					<div class="form-group col-sm-4">
						<label for="year">{{trans('loan.time_period')}}</label>	
						<!--input type="text" class="form-control" name="time_period" id="time_period" value="{{$loan->time_period}}"-->
						<select class="form-control" id="time_period" name="time_period" onchange="rumusLoan()" required>
						<?php
							$roll = [];
							$tenors = ['1','3','6','9','12','15','18','21','24','27','30','33','36'];                        
							$roll[] = $loan->time_period;
						?>
						@foreach($tenors as $tenor)
							@if(in_array($tenor, $roll))
							  <option value="{{ $tenor }}" selected="true">{{ $tenor }} Bulan</option>
							@else
							  <option value="{{ $tenor }}">{{ $tenor }} Bulan</option>
							@endif 
						@endforeach
						</select>
					</div>
					<div class="form-group col-sm-4">
						<label for="year">{{trans('loan.interest_rate')}}</label>	
						<input type="text" class="form-control" name="interest_rate" onkeyup="rumusLoan()" id="interest_rate" value="{{$loan->interest_rate}}">
					</div>
					<div class="form-group col-sm-4">
						<label for="year">{{trans('loan.pay_principal')}}</label>	
						<input type="text" class="form-control" name="pay_principal" id="pay_principal" value="{{ number_format($loan->pay_principal, 0, ',' , '.') }}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
					</div>
					<div class="form-group col-sm-4">
						<label for="year">{{trans('loan.pay_interest')}}</label>	
						<input type="text" class="form-control" name="pay_interest" id="pay_interest" value="{{ number_format($loan->pay_interest, 0, ',' , '.') }}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
					</div>
					<div class="form-group col-sm-4">
						<label for="year">{{trans('loan.pay_month')}}</label>	
						<input type="text" class="form-control" name="pay_month" id="pay_month" value="{{ number_format($loan->pay_month, 0, ',' , '.') }}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
					</div>
					<div class="form-group col-sm-4">
						<label for="year">Total {{trans('loan.pay_principal')}}</label>	
						<input type="text" class="form-control" name="total_principal" id="total_principal" value="{{ number_format($loan->total_principal, 0, ',' , '.') }}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
					</div>
					<div class="form-group col-sm-4">
						<label for="year">Total {{trans('loan.pay_interest')}}</label>	
						<input type="text" class="form-control" name="total_interest" id="total_interest" value="{{ number_format($loan->total_interest, 0, ',' , '.') }}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
					</div>
					<div class="form-group col-sm-4">
						<label for="year">{{trans('installment.loan_remaining')}}</label>	
						<input type="text" class="form-control" name="loan_remaining" id="loan_remaining" value="{{ number_format($loan->loan_remaining, 0, ',' , '.') }}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
					</div>
					<div class="form-group col-sm-4">
						<label for="year">{{trans('installment.savings')}}</label>	
						<input type="text" class="form-control" name="wajib" id="wajib" value="{{ number_format($kontrak->m_savings, 0, ',' , '.') }}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
					</div>
			</div>
			<div class="box-footer">
				<button class="btn btn-success" type="submit"><span class="fa fa-save"></span> {{trans('general.submit')}}</button>				
				<span class="new-button">
					<a href="{{ route('installment')}}" class="btn btn-danger">
						<span class="fa fa-close"></span> {{trans('general.close')}}
					</a>
				</span>
			</div>
		</form>
		@endforeach
	</div>

@endsection

@section('js')
<script>
	  $(document).ready(function () {
      
    var formatMoney = function (num) {
      var str = num.toString().replace("$", ""),
          parts = false,
          output = [],
          i = 1,
          formatted = null;
      if (str.indexOf(".") > 0) {
          parts = str.split(".");
          str = parts[0];
      }
      str = str.split("").reverse();
      for (var j = 0, len = str.length; j < len; j++) {
          if (str[j] != ",") {
              output.push(str[j]);
              if (i % 3 == 0 && j < (len - 1)) {
                  output.push(",");
              }
              i++;
          }
      }
      formatted = output.reverse().join("");
      return ("$" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
  };
      
      
      var formatPercentage = function(toFormat, multiplyWithHundred){
          	if(isNaN(toFormat)) //called in wrong fashion
             	return toFormat;
          
      		return parseFloat(toFormat) * (multiplyWithHundred ? 100 : 1) + '%';
      };
      
      var unformatMoney = function(formatted){
        var unformatted =  formatted.replace(',', '').replace('$', '');
        if(isNaN(unformatted))
      		return unformatted;
          
        return parseFloat(unformatted);
      };
      
      var unformatPercentage = function(formatted, divideByHundred){
      	var unformatted = formatted.replace('%', '');
        if(isNaN(unformatted))
            return unformatted;
        
        return parseFloat(unformatted) / (divideByHundred ? 100 :  1);
      };
      
      $("#a1,#interest_rate").change(function (e) {
          var a1Val = $('#a1').val().trim();
          var a2Val = $('#interest_rate').val().trim();
          
          if(a1Val)
          	$('#a1').val(formatMoney(unformatMoney(a1Val)));
          
          if(a2Val)
          	$('#interest_rate').val(formatPercentage(unformatPercentage(a2Val)));
          
          if(a1Val && a2Val)
          	$('#a3').val(formatMoney(unformatMoney(a1Val) * unformatPercentage(a2Val, true)));
      });
      
  });
</script>

<script>

function rumusLoan() {
	var ambilBunga = $("input[name=interest_rate]").val();		
	var ambilTenor = $('#time_period option:selected').val();
	var ambilPlafon = $("input[name=loan_amount]").val();		
	var plafon = ambilPlafon.split('.').join('');		
	var bunga_perbulan = ambilBunga / 12;		
	var bunga_rp = plafon / ambilBunga;		
	var angsuran_bunga = plafon * bunga_perbulan / 100;		
	var angsuran_pokok =  plafon / ambilTenor;
	var total_angsuran = angsuran_pokok + angsuran_bunga;
	var angsuran = Math.ceil(total_angsuran / 1000) * 1000;
	var	number_string = angsuran.toString(),
		sisa 	= number_string.length % 3,
		rupiah 	= number_string.substr(0, sisa),
		ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
		
	var pokok = Math.ceil(angsuran_pokok / 1000) * 1000;
	var pokokString = pokok.toString();
		sisaPokok 	= pokokString.length % 3,
		rupiahPokok 	= pokokString.substr(0, sisaPokok),
		ribuanPokok 	= pokokString.substr(sisaPokok).match(/\d{3}/g);

	var bunga = Math.ceil(angsuran_bunga / 1000) * 1000;
	var bungaString = bunga.toString();
		sisaBunga 	= bungaString.length % 3,
		rupiahBunga 	= bungaString.substr(0, sisaBunga),
		ribuanBunga 	= bungaString.substr(sisaBunga).match(/\d{3}/g);
	
	var totalPokok = pokok * ambilTenor;
	var total_Pokok = Math.ceil(totalPokok / 1000) * 1000;
	var totalPokokString = total_Pokok.toString();
		sisaTotalPokok 	= totalPokokString.length % 3,
		rupiahTotalPokok 	= totalPokokString.substr(0, sisaTotalPokok),
		ribuanTotalPokok 	= totalPokokString.substr(sisaTotalPokok).match(/\d{3}/g);
		
	var totalBunga = bunga * ambilTenor;
	var total_bunga = Math.ceil(totalBunga / 1000) * 1000;
	var totalBungaString = total_bunga.toString();
		sisaTotalBunga 	= totalBungaString.length % 3,
		rupiahTotalBunga 	= totalBungaString.substr(0, sisaTotalBunga),
		ribuanTotalBunga 	= totalBungaString.substr(sisaTotalBunga).match(/\d{3}/g);
		
	var totalHutang = angsuran * ambilTenor;
	var total_hutang = Math.ceil(totalHutang / 1000) * 1000;
	var totalHutangString = total_hutang.toString();
		sisaTotalHutang 	= totalHutangString.length % 3,
		rupiahTotalHutang 	= totalHutangString.substr(0, sisaTotalHutang),
		ribuanTotalHutang 	= totalHutangString.substr(sisaTotalHutang).match(/\d{3}/g);
			
	if (ribuan) {
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}
	if (ribuanPokok) {
		separator = sisaPokok ? '.' : '';
		rupiahPokok += separator + ribuanPokok.join('.');
	}
	if (ribuanBunga) {
		separator = sisaBunga ? '.' : '';
		rupiahBunga += separator + ribuanBunga.join('.');
	}
	if (ribuanTotalPokok) {
		separator = sisaTotalPokok ? '.' : '';
		rupiahTotalPokok += separator + ribuanTotalPokok.join('.');
	}
	if (ribuanTotalBunga) {
		separator = sisaTotalBunga ? '.' : '';
		rupiahTotalBunga += separator + ribuanTotalBunga.join('.');
	}
	if (ribuanTotalHutang) {
		separator = sisaTotalHutang ? '.' : '';
		rupiahTotalHutang += separator + ribuanTotalHutang.join('.');
	}
	
	$("input[name=pay_month]").val(rupiah);
	$("input[name=pay_principal]").val(rupiahPokok);
	$("input[name=pay_interest]").val(rupiahBunga);
	$("input[name=total_principal]").val(rupiahTotalPokok);
	$("input[name=total_interest]").val(rupiahTotalBunga);
	$("input[name=loan_remaining]").val(rupiahTotalHutang);
}
</script>

@endsection