@extends('layouts.app')
@section('content')

	@include('error.error-notification')
	<div class="box">	
	<form method="post" action="{{route('contract.store')}}" enctype="multipart/form-data">
	{{ csrf_field() }}
		<div class="box-header">
			<h2>{{trans('general.contract')}}</h2>
		</div>
		<div class="box-body">		
		@foreach($customers as $customer)	
			<?php 				
				$approves = App\Models\CustomerApprove::where('reg_number',$customer->reg_number)->get();
			?>
			<input type="hidden" name="customer_id" value="{{$customer->id}}">
			<input type="hidden" name="reg_number" value="{{$customer->reg_number}}">
			<div class="form-group col-sm-4">
				<label for="year">{{trans('general.customer')}}</label>	
				<input type="text" class="form-control" name="cuatomer" id="customer" value="{{$customer->name}}" disabled>
			</div>
			<div class="form-group col-sm-4">
				<label for="year">{{trans('survey.loan_to')}}</label>	
				<input type="text" class="form-control" name="loan_to" id="loan_to" value="{{$customer->loan_to}}">
			</div>
			@foreach($approves as $approve)
			<div class="form-group col-sm-4">
				<label for="year">{{trans('loan.loan_amount')}}</label>	
				<input type="text" class="form-control" name="loan_amount" id="loan_amount" value="{{number_format($approve->approve_amount, 0, ',' , '.') }}" disabled>
			</div>
			<div class="form-group col-sm-4">
				<label for="year">{{trans('loan.time_period')}}</label>	
				<input type="text" class="form-control" name="time_period" id="time_period" value="{{$approve->time_period}}" disabled>
			</div>			
			<div class="form-group col-sm-4">
				<label for="year">{{trans('loan.interest_rate')}}</label>	
				<input type="text" class="form-control" name="interest_rate" id="interest_rate" value="{{$approve->interest_rate}} %" disabled>
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
			<div class="form-group col-sm-4">
				<label for="year">Angsuran</label>	
				<input type="text" class="form-control" name="angsuran" value="{{$payMonth}}">
			</div>
			<div class="form-group col-sm-4">
				<label for="year">Total /Bulan</label>	
				<input type="text" class="form-control" name="total_month" placeholder="Rp.0">
			</div>
		@endforeach
		</div>
		<div class="box-footer">
			<button class="btn btn-success" type="submit"><span class="fa fa-save"></span> {{trans('general.submit')}}</button>				
			<span class="new-button">
				<a href="{{ route('contract')}}" class="btn btn-danger">
					<span class="fa fa-close"></span> {{trans('general.close')}}
				</a>
			</span>
		</div>
		</form>
	</div>
	
@endsection

@section('js')
	<script type="text/javascript">
        
		var item_row = 0;
        function addItem() {
            html  = '<tr id="item-row-' + item_row + '">';
            html += '  <td class="text-center" style="vertical-align: middle;">';
            html += '      <button type="button" onclick="$(this).tooltip(\'destroy\'); $(\'#item-row-' + item_row + '\').remove(); totalItem();" data-toggle="tooltip" title="Hapus" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>';
            html += '  </td>';          			            
			
			html += '  <td>';
            html += '      <input class="form-control text-left" required name="berkas[]" type="text" id="item-row-' + item_row + '" >';
            html += '  </td>';
			
			html += '  <td>';           
            html += '      <select class="form-control select2 select2-hidden-accessible" style="width: 100%;"  ';
            html += '      tabindex="-1" aria-hidden="true" name="status[]" id="item-row-' + item_row + '">';            
            html += '         <option value="copy" disable="true" selected="true">FOTO COPY</option>';
			html += '         <option value="asli" disable="true" selected="true">ASLI</option>';
            html += '       </select>';
            html += '  </td>';
			
			html += '  <td>';
            html += '      <input class="form-control text-left" required name="keterangan[]" type="text" id="item-row-' + item_row + '">';
            html += '  </td>';                                          

            $('#items tbody #addItem').before(html);
            //$('[rel=tooltip]').tooltip();

            $('[data-toggle="tooltip"]').tooltip('hide');

            $('#item-row-' + item_row + ' .select2').select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}"
            });

            item_row++;
        }

        function totalItem() {
            $.ajax({
                url: '{{ url("items/items/totalItem") }}',
                type: 'POST',
                dataType: 'JSON',
                data: $('#currency_code, #items input[type=\'text\'],#items input[type=\'hidden\'], #items textarea, #items select'),
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(data) {
                    if (data) {
                        $.each( data.items, function( key, value ) {
                            $('#item-total-' + key).html(value);
                        });

                        $('#sub-total').html(data.sub_total);
                        $('#tax-total').html(data.tax_total);
                        $('#grand-total').html(data.grand_total);
                    }
                }
            });
        }
        
    </script>

	<script type="text/javascript">						    
		CKEDITOR.replace('long_desc', {
			"filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
		});						    
	</script>
	
	<script type="text/javascript">
	 window.onload = function(){
		$("input[name=m_savings]").keyup(function () {
			var ambilPlafon = $("input[name=angsuran]").val();
			var ambilTabungan = $("input[name=m_savings]").val();	
			var tabungan = ambilTabungan.replace(/[^0-9]/g, '');
			var total = parseInt(ambilPlafon) + parseInt(tabungan);
			$("input[name=total_month]").val(total);
	   });
	}	
	</script>
@endsection