@extends('layouts.app')
@section('content')
	<div class="box">
		<div class="box-header">
		<form method="post" action="{{URL::to('report/installment/printPdf')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
			<div class="form-group col-sm-4">
				<select class="input select2 select2-hidden-accessible" style="width:100%;" aria-hidden="true" name="customer" id="customer">
					<!--option value="0" data-id="0" data-member="0">{{trans('general.choice')}} {{trans('general.customer')}}</option-->
					@foreach($customers as $customer)
						<option value="{{$customer->id}}" data-id="{{$customer->id}}" data-member="{{$customer->member_number}}">{{ $customer->member_number }} | {{ $customer->name }}</option>						
					@endforeach
				</select>
			</div>
			<div class="form-group col-sm-4">				
				<button type="submit" formtarget="_blank" class="btn btn-default" ><i class="fa fa-search"></i></button>
				<!--input name="submit" type="button" value="History" onclick="location.href='installment/printPdf/' + document.getElementById('customer').value" target="_blank" />
				<a href="{{URL::to('report/installment/printPdf/') }}" class="btn btn-default" target="_blank" id="btnSearch">
					<i class="fa fa-search"></i>
				</a-->				
			</div>
		</form>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive-sm table-striped" style="width:80%" id="installment">
					<thead>
						<tr>
							<th class="text-center">Tanggal</th>
							<th class="text-center">Bukti Transaksi</th>
							<th class="text-center">No. Anggota</th>
							<th class="text-center">Cara Bayar</th>
							<th class="text-center">Pokok</th>
							<th class="text-center">Bunga</th>
							<th class="text-center">Jumlah</th>
						</tr>
					</thead>
					<tbody>
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script>
		$("#customer").change(function () {
			var ambilCustomer = $(this).find(':selected').attr('data-member');
			//alert(ambilCustomer);
			//var id = ambilCompany;
			//document.getElementById('btnSearch').value = ambilCustomer;
			
			$.ajax({
				type: "GET",
				url: "{!! url('/report/installment/member/" + ambilCustomer + "/') !!}",
				success: function (response) {
					if (response['result'] == 'success') {	
						$('tbody').empty();
						var members = (response['installments']);						
						//$('tbody').html(response.table_data);
												
						$.each(members, function(index, subcatObj){
							//var payDate= new Date(subcatObj.pay_date));
							//var pokok = (response['pokok']);
							//var bunga = (response['bunga']);
							//alert(pokok);
							var MyDate = new Date(subcatObj.pay_date);
							var MyDateString;
							MyDate.setDate(MyDate.getDate() + 20);
							MyDateString = ('0' + MyDate.getDate()).slice(-2) + '-' + ('0' + (MyDate.getMonth()+1)).slice(-2) + '-' + MyDate.getFullYear();
							var jumlah = Math.ceil(subcatObj.amount);
							var payStatus = subcatObj.pay_status;
							var fullFree = subcatObj.full_free;
							//if (payStatus == "FULL")
							//{
							//	var principal = pokok;
							//	var rate = bunga;
							//} else {
							//	var principal = 0;
							//	var rate = 0;
							//}
							if (fullFree == "pay_principal")
							{
								var principal = jumlah;
							} else {
								var principal = 0;
							}
							if (fullFree == "pay_rates")
							{
								var rate = jumlah;
							} else {
								var rate = 0;
							}
							var	number_string = jumlah.toString(),
								sisa 	= number_string.length % 3,
								rupiah 	= number_string.substr(0, sisa),
								ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
									
							if (ribuan) {
								separator = sisa ? '.' : '';
								rupiah += separator + ribuan.join('.');
							}
							$('tbody').append('<tr><td align="middle">' +MyDateString+ '</td><td align="middle">' +subcatObj.trans_number+'</td><td align="middle">' +subcatObj.member_number+'</td><td align="middle">' +subcatObj.pay_method+'</td><td align="right">' +principal+'</td><td align="right">' +rate+'</td><td align="right">' +rupiah+'</td></tr>');
						});
					}
				},
				error: function (response) {
					alert("error");
				}
			});

		});
	</script>
@endsection