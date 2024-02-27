@extends('layouts.app')
@section('content')
	
	@include('error.error-notification')	
	<div class="box">
		<form method="post" action="{{URL::to('customer/reloan/storefour')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
			<div class="box-header">
			<h2>{{trans('general.contract')}}</h2>
		</div>
		<div class="box-body">		
		@foreach($customers as $customer)	
			<?php 				
				$approves = App\Models\CustomerApprove::where('customer_id',$customer->id)->where('approve',0)->get();
			?>
			<input type="hidden" name="customer_id" value="{{$customer->id}}">
			<input type="hidden" name="reg_number" value="{{$customer->reg_number}}">
			<div class="form-group col-sm-4">
				<label for="year">{{trans('general.customer')}}</label>	
				<input type="text" class="form-control" name="cuatomer" id="customer" value="{{$customer->name}}" disabled>
			</div>
			<div class="form-group col-sm-4">
				<label for="year">{{trans('survey.loan_to')}}</label>	
				<input type="text" class="form-control" name="loan_to" id="loan_to" placeholder="Pinjaman Ke : x">
			</div>
			@foreach($approves as $approve)
			<input type="hidden" name="approvesId" value="{{$approve->id}}">
			<div class="form-group col-sm-4">
				<label for="year">{{trans('loan.loan_amount')}}</label>	
				<input type="text" class="form-control" name="loan_amount" id="loan_amount" value="{{$approve->approve_amount}}" disabled>
			</div>
			<div class="form-group col-sm-4">
				<label for="year">{{trans('loan.time_period')}}</label>
				<input type="text" class="form-control" name="time_period" id="time_period" value="{{$approve->time_period}}" disabled>
				<!--select class="form-control" id="time_period" name="time_period" required>
					<option value="0">Please select</option>
					@foreach($tenors as $tenor)							
						<option value="{{$tenor}}">{{$tenor}} bulan</option>
					@endforeach
				</select-->
			</div>			
			<div class="form-group col-sm-4">
				<label for="year">{{trans('loan.interest_rate')}} %</label>	
				<input type="text" class="form-control" name="interest_rate" id="interest_rate" value="{{$approve->interest_rate}}">
			</div>
			<?php 
				$pinjaman = $approve->approve_amount;
				$tenor = $approve->time_period;
				$kembang = $approve->interest_rate;
				$sukuBunga = $kembang / 12;
				$pokok = $pinjaman / $tenor;
				$bunga = $pinjaman * $sukuBunga / 100;				
				$jumlahAngsuran = $pokok + $bunga;
				$payMonth = ceil($jumlahAngsuran / 1000) * 1000;
			?>
			@endforeach
			<div class="form-group col-sm-4">
				<label for="year">{{trans('loan.bank_name')}}</label>	
				<input type="text" class="form-control" name="bank_name" id="bank_name" value="{{$customer->bank_name}}" disabled>
			</div>
			<div class="form-group col-sm-4">
				<label for="year">{{trans('loan.atm_number')}} / {{trans('general.account')}} </label>	
				<input type="text" class="form-control" name="atm_number" id="atm_number" placeholder="ATM Card Number">
			</div>
			<div class="form-group col-sm-4">
				<label for="year">{{trans('loan.bank_pin')}}</label>	
				<input type="password" class="form-control" name="bank_pin" id="bank_pin" placeholder="0">
			</div>
			<div class="form-group col-sm-4">
				<label for="day">{{trans('general.day')}}</label>
				<select class="form-control" id="hari" name="hari" required>
					<option value="0">Please select</option>
					@foreach($haris as $hari)							
						<option value="{{$hari}}">{{$hari}}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-sm-4">
				<label for="date">{{trans('general.date')}}</label>	
				<select class="form-control" id="tanggal" name="tanggal">
					<option value="0">Please select</option>
					@foreach($tanggals as $tanggal)							
						<option value="{{$tanggal}}">{{$tanggal}}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-sm-4">
				<label for="month">{{trans('general.month')}}</label>
				<select class="form-control" id="bulan" name="bulan">
					<option value="0">Please select</option>
					@foreach($bulans as $bulan)							
						<option value="{{$bulan}}">{{$bulan}}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-sm-4">
				<label for="year">{{trans('general.year')}}</label>	
				<select class="form-control" id="tahun" name="tahun">
					<option value="0">Please select</option>
					@foreach($tahuns as $tahun)							
						<option value="{{$tahun}}">{{$tahun}}</option>
					@endforeach
				</select>
			</div>			
			<div class="form-group col-sm-4">
				<label for="year">{{trans('general.employee')}}</label>	
				<select class="form-control" id="employee" name="employee" required>
					<option value="0">Please select</option>
					@foreach($employees as $employee)							
						<option value="{{$employee->name}}">{{$employee->name}}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-sm-4">
				<label for="year">{{trans('general.mandatory_savings')}}</label>	
				<input type="text" class="form-control" name="m_savings" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" placeholder="Rp. 0" required>
			</div>
			<div class="form-group col-sm-4">
				<label for="year">{{trans('general.insurance')}}</label>	
				<!--input type="text" class="form-control" name="insurance" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" placeholder="Rp. 0" required-->
				<select class="form-control" id="insurance" name="insurance">
					<option value="0.5">6 Bulan 0.5%</option>
					<option value="1">9 Bulan 1%</option>
					<option value="1.25">12 Bulan 1.25%</option>
					<option value="1.50">15 Bulan 1.50%</option>
					<option value="1.75">18 Bulan 1.75%</option>
					<option value="2">21 Bulan 2%</option>
					<option value="2.25">24 Bulan 2.25%</option>
					<option value="2.50">30 Bulan 2.50%</option>
					<option value="2.75">36 Bulan 2.75%</option>
				</select>
			</div>
			<div class="form-group col-sm-4">
				<label for="year">{{trans('general.stamp')}}</label>	
				<input type="text" class="form-control" name="stamp" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" placeholder="Rp. 0">
			</div>
			<div class="form-group col-sm-4">
				<label for="year">{{trans('general.provision')}} %</label>	
				<input type="number" class="form-control" name="provision" placeholder="0 %">
			</div>
			<!--div class="form-group col-sm-4">
				<label for="year">Angsuran</label>	
				<input type="text" class="form-control" name="angsuran" placeholder="">
			</div>
			<div class="form-group col-sm-4">
				<label for="year">Total /Bulan</label>	
				<input type="text" class="form-control" name="total_month" placeholder="Rp.0">
			</div-->
		@endforeach
		</div>
		<div class="box-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>					
			<button class="btn btn-success" type="submit" id="simpan">
				<span class="cil-save"></span> {{('Next')}}
			</button>
		</div>
		</form>
	</div>
@endsection