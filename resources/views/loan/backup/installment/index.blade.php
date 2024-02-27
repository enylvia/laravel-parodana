@extends('layouts.app')
@section('content')

@include('error.error-notification')	
	
	<div class="box">
		<div class="box-header">			
			
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive-sm table-striped" id="installment">
					<thead>
						<tr>
							<th></th>
							<th>Id</th>
							<th>{{trans('installment.customer_name')}}</th>
							<th>{{trans('installment.customer_number')}}</th>
							<th>{{trans('loan.loan_amount')}}</th>
							<th>{{trans('loan.time_period')}}</th>
							<th>{{trans('loan.interest_rate')}}</th>
							<th>{{trans('loan.pay_principal')}}</th>
							<th>{{trans('loan.pay_interest')}}</th>
							<th>{{trans('loan.pay_month')}}</th>
							<th>{{trans('installment.loan_remaining')}}</th>
							<th>{{trans('general.view')}}</th>
							<th>{{trans('general.edit')}}</th>
							<th>Create Table</th>
							<th>Angsuran</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
	
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
							<input type="text" class="form-control" name="transfer_in" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in">
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
							$totalBayar = $byrCicilan + $byrTempo + $wajib;
						?>
						
						<!--div class="form-group col-md-4" style="display:block">
							<label for="tempo">Angsuran</label>	
							<input type="text" class="form-control" name="cicilan" value="{{$byrCicilan}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in">
						</div>
						<div class="form-group col-md-4" style="display:block">
							<label for="tempo">Tempo</label>	
							<input type="text" class="form-control" name="tempo" value="{{$byrTempo}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in">
						</div-->
						<div class="form-group col-md-4" style="display:block">
							<label for="tempo">Wajib</label>	
							<input type="text" class="form-control" name="wajib" value="{{!empty($kontrak->m_savings) ? $kontrak->m_savings : 0}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in">
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
							<input type="text" class="form-control" name="amount" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" value="{{$totalBayar}}" id="amount">
							<input type="hidden" class="form-control" name="tagihan" value="{{$totalBayar}}" id="tagihan">
						</div>
						<!--div class="form-group col-md-4">
							<label for="transfer">Jumlah</label>
							
						</div-->
						<div id="FullModal">
	                    
	                	</div>					
		            </div>
		            <!-- Modal footer -->
		            <div class="modal-footer">
		                <!--button type="button" class="btn btn-success" id="SubmitFullForm">Bayar</button-->
		                <button type="submit" class="btn btn-success" id="submitFull">Bayar</button>
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
							$loans = App\Models\Loan::where('member_number',$angsuran->member_number)->first();
							$tempos = App\Models\Tempo::where('member_number',$angsuran->member_number)->where('status','=','UNPAID')->first();
							$byrCicilan = !empty($loans->pay_month) ? $loans->pay_month : 0;
							$getCutsId = !empty($loans->customer_id) ? $loans->customer_id : NULL;
							$last = App\Models\Installment::where('member_number',$angsuran->member_number)->orderBy('id','desc')->first();
							if (!empty($last->inst_to))
							{
								$lastID = $last->inst_to;
							}else {
								$lastID = 0;
							}
							$sisaBayaran = App\Models\Installment::where('member_number',$angsuran->member_number)->where('reminder', '>', 0)->first();
							$sisa = !empty($sisaBayaran->reminder) ? $sisaBayaran->reminder : 0;
							$kontrak = App\Models\CustomerContract::where('customer_id',$getCutsId)->first();
							$tabWajib = !empty($kontrak->m_savings) ? $kontrak->m_savings : 0;
							$byrTempo = !empty($tempos->total_amount) ? $tempos->total_amount : 0;
							$tabungan = str_replace('.', '', $tabWajib);
							$wajib = $tabungan ? $tabungan : 0;
							if ($sisa > 0)
							{						
								$totalBayar = $sisa;
							} else {
								$totalBayar = $byrCicilan + $byrTempo + $wajib;
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
						<!--div class="form-group col-md-6">
							<label for="year">{{trans('installment.pay_date')}}</label>	
							<input type="date" class="form-control" name="pay_date" value="{{date('Y-m-d')}}" id="pay_date">
						</div-->
						<div class="form-group col-md-12">
							<label for="year">No. Pinjaman</label>	
							<input type="text" class="form-control" name="loan_number" value="{{ !empty($loans->loan_number) ? $loans->loan_number : 0}}" id="loan_number">
						</div>
						<div class="form-group col-md-12">
							<label for="transfer">Transfer Masuk</label>	
							<input type="text" class="form-control" name="transfer_in" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in" required>
							<span class="text-live">
								<strong id="transfer-msg-err"></strong>
							</span>
						</div>
						<input type="hidden" class="form-control" name="byrTempo" value="{{$byrTempo}}">
						<input type="hidden" class="form-control" name="byrWajib" value="{{$wajib}}">
						<input type="hidden" class="form-control" name="byrCicilan" value="{{$byrCicilan}}">
						<!--div class="form-group col-md-4" style="display:block">
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
						</div-->
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
							<input type="text" class="form-control" name="amount" placeholder="Cicilan : {{$byrCicilan}} Tempo : {{$byrTempo}} Wajib: {{$wajib}} Total: Rp. {{$totalBayar}}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="amount" required>
						</div>
						<div class="form-group col-md-12">
							<label for="transfer">Denda</label>	
							<input type="text" class="form-control" name="charge" placeholder="Rp. 0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="charge">
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
	
	@endforeach
	
	<div class="modal fade" id="angsur" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="angsuranShowModal">Daftar Angsuran</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				
				<div class="modal-body" id="bodyAngsur">					
						
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default btnAngsur" data-dismiss="modal">Cancel</button>
					<input type="submit" class="btn btn-success" value='Simpan'/>
				</div>
			</div>
		</div>
	</div>
	
@endsection

@section('js')

<script type="text/javascript">
	function formatNumber(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
	$(document).ready(function() {
		//var template = Handlebars.compile($("#details-template").html());
	    var dataTables = $('#installment').DataTable({
	      	"processing":false,
	      	"serverSide":true,
	      	"order":[],
	      	"ajax":{
	        	url: "{{ route('installment') }}"
	      	},

	      "columns": [
	          {
	            "class":          "details-control",
	            "orderable":      false,
	            "data":           null,
	            "defaultContent": ""
	          },
	          	{data: "id"},
				{data: "customer.name"},				
				{data: "member_number"},
				{data: "loan_amount", render: $.fn.dataTable.render.number('.' , ',', 0, 'Rp. ')},
				{data: "time_period"},
				{data: "interest_rate"},								
				{data: "pay_principal", render: $.fn.dataTable.render.number('.' , ',', 0, 'Rp. ')},
				{data: "pay_interest", render: $.fn.dataTable.render.number('.' , ',', 0, 'Rp. ')},
				{data: "pay_month", render: $.fn.dataTable.render.number('.' , ',', 0, 'Rp. ')},
				{data: "loan_remaining", render: $.fn.dataTable.render.number('.' , ',', 0, 'Rp. ')},
				{data: "btnView"},
				{data: "btnEdit"},
				{data: "btnTable"},
				{data: "btnPay" }
	      ],
	        "order": [[1, 'asc']]
	    });

	    $('#installment tbody').on( 'click', 'td.details-control', function () {
	    	var tr = $(this).closest('tr');
            var row = dataTables.row( tr );
 
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        });
        
		dataTables.draw();

	    function format(d) {
	    	//alert(d.member_number);
	        var txt = "";
	        var nour = 1;
	        $.ajax({
	            url: "{{ url('installment/getDetail') }}/"+d.member_number,
	            async: false,
	            dataType: "json",
	            success: function(response) {
	                //var data = JSON.parse(response.responseText);
	                txt += "<table class='table table-hover' border='0'>";
	                txt += "<thead><tbody><th>No.</th><th>Angsuran</th><th>Jatuh Tempo</th><th>Tgl. Bayar</th><th>Metode Bayar</th><th>Status</th><th>saldo</th><th>Sisa</th><th class='text-center' colspan='2'>Aksi</th></tbody></thead>";
	                    $.each(response.data, function( index, value ) {
	                    	//alert(value.due_date);
	                    	//for (x in data) {	    
							if(!value.pay_date)
							{
								var payDate = '';
							} else{
								var payDate = moment(value.pay_date).format('DD-MM-YYYY');
							}
	                        txt += "<tr><td>" + nour + ".</td>";
	                        txt += "<td>" + value.inst_to + ".</td>";
	                        txt += "<td>" + moment(value.due_date).format('DD-MM-YYYY') + "</td>";
	                        txt += "<td>" + payDate + "</td>";
	                        txt += "<td>" + value.pay_method + "</td>";
							if(value.status == 'PAID')
	                        {
								txt += "<td><span class='label label-success'>"+value.status+"</span></td>";
							}
							if(value.status == 'UNPAID')
							{
								txt += "<td><span class='label label-info'>"+value.status+"</span></td>";
							}
							if(value.status == 'PARTIAL')
							{
								txt += "<td><span class='label label-warning'>"+value.status+"</span></td>";
							}							
							if(value.status == 'CORRUPT')
							{
								txt += "<td><span class='label label-danger'>"+value.status+"</span></td>";
							}
							txt += "<td align='right'>" + formatNumber(value.amount) + "</td>";
	                        txt += "<td align='center'>";
							if(value.reminder === 0 || value.status == 'PAID')
							{
								txt += "LUNAS";
							} else if (value.reminder > 0 || value.status == 'PAID') {
								txt += formatNumber(value.reminder) ;
							} else if (value.reminder === 0 || value.status == 'UNPAID') {
								txt += "BELUM LUNAS";
							} else if(value.amount === 0 || value.status == 'PARTIAL')
							{
								txt += "BELUM LUNAS";
							} else if(value.amount> 0 || value.status == 'PARTIAL')
							{
								txt += "LUNAS";
							} else {
								
							}
							txt += "</td>";
							if(value.status == 'PAID')
							{
								txt += "<td><a id='full' data-toggle='modal' class='btn btn-success btn-sm' style='display:none'>Bayar Penuh</a></td>";
	                    		txt += "<td><a id='free' data-toggle='modal' class='btn btn-warning btn-sm' style='display:none'>Bayar Bebas</a></td>";
							}else if(value.status == 'UNPAID'){
								txt += "<td><a id='full' data-id='"+value.id+"' data-target='#full-"+value.id+"' data-toggle='modal' class='btn btn-success btn-sm'>Bayar Penuh</a></td>";
	                        	txt += "<td><a id='free' data-id='"+value.id+"' data-target='#free-"+value.id+"' data-toggle='modal' class='btn btn-warning btn-sm' data-dismiss='modal'>Bayar Bebas</a></td>";
							}else{
								txt += "<td><a id='full' data-id='"+value.id+"' data-target='#full-"+value.id+"' data-toggle='modal' class='btn btn-success btn-sm'>Bayar Penuh</a></td>";
	                        	txt += "<td><a id='free' data-id='"+value.id+"' data-target='#free-"+value.id+"' data-toggle='modal' class='btn btn-warning btn-sm' data-dismiss='modal'>Bayar Bebas</a></td>";
							}
	                        txt += "</tr>" ;
							nour++;
	                        //}
	                    });
	                txt += "</table>" 
	            }
	        });
	       return txt; 
	    }
  
	});
</script>

<script>
	$(document).ready(function(){
		$('#close').on('hidden.bs.modal', function (e) {
			$(this).find("input[name='transfer_in']").val('').end();
			$(this).find("input[name='amount']").val('').end();
		});
	});	
</script>
<script type="text/javascript">
 window.onload = function(){
   $("#member_number").change(function () {
     var ambilNama = $("#member-"+this.value).data('nama');
     //var ambilStatus = $("#alat-"+this.value).data('status');
     //var ambilKondisi = $("#alat-"+this.value).data('kondisi');
     $("#custome_name").val(ambilNama);
     //$("#status").val(ambilStatus);
     //$("#kondisi").val(ambilKondisi);
   });
}
</script>

<script>
$(document).ready(function(){
    $("#fullForm").submit(function(e){
        e.preventDefault();
	    var id = $("#angsuran_id").val();
	    var pay_date = $("#pay_date").val();
	    var pay_status = $("#pay_status").val();
	    var payment_method = $("#payment_method").val();
	    var transfer_in = $("#transfer_in").val();
	    var wajib = $("#wajib").val();
	    var tagihan = $("#tagihan").val();
        $.ajax({ 
            method: 'POST',          
            url: "{{ url('installment/full_store') }}/"+id,
            headers: {
				'X-CSRF-Token': $('meta[name="_token"]').attr('content')
			},
            async: false,
            data: "&pay_date=" + pay_date + "&pay_status=" + pay_status + "&payment_method=" + payment_method + "&transfer_in=" + transfer_in + "wajib=" + wajib + "tagihan=" + tagihan +  "_token=" + $('#token').val(),
            success: function(result){
                if (result == "success"){
                    alert("Done");
                    document.getElementById("fullForm").reset();
                }
            }
        });
    });
});

function ShowModal(elem){
    var memberNumber = $(elem).data("id");
	//alert(dataId);
    //let $modal = dataId;	
	//let $modal = $('#angsur');

	//$('#installment').on('click', '.angsuran' ,function(e){	
		//var id = $('.angsuran').html();	
	//	e.preventDefault();
	//	$modal.modal('show');
	//});

	//$modal.on('show.bs.modal', function (event) {
	//	var txt = "";
	//        var nour = 1;
	//        $.ajax({
	//            url: "{{ url('installment/getCicilan') }}/"+memberNumber,
	//            async: false,
	//            dataType: "json",
	//            success: function(response) {
	                //var data = JSON.parse(response.responseText);
	//				txt += "<div class='table-responsive'>";
	//                txt += "<table class='table table-hover' border='0'>";
	//                txt += "<thead><tbody><th>No.</th><th>Angsuran</th><th>Jatuh Tempo</th><th>Tgl. Bayar</th><th>Metode Bayar</th><th>Status</th><th>saldo</th><th>Sisa</th><th class='text-center' colspan='2'>Aksi</th></tbody></thead>";
	//                    $.each(response.data, function( index, value ) {
	                    	//alert(value.due_date);
	                    	//for (x in data) {	                    	
	//                        txt += "<tr><td>" + nour + ".</td>";
	//                        txt += "<td>" + value.inst_to + ".</td>";
	//                        //txt += "<td>" + data[x].no_invoice + "</td>";
	//                        txt += "<td>" + moment(value.due_date).format('DD-MM-YYYY') + "</td>";
	//                        txt += "<td>" + moment(value.pay_date).format('DD-MM-YYYY') + "</td>";
	//                        txt += "<td>" + value.pay_method + "</td>";
	                        
	//                        if(value.status == 'PAID')
	//                        {
	//							txt += "<td><span class='label label-success'>"+value.status+"</span></td>";
	//						}
	//						if(value.status == 'UNPAID')
	//						{
	//							txt += "<td><span class='label label-info'>"+value.status+"</span></td>";
	//						}
	//						if(value.status == 'PARTIAL')
	//						{
	//							txt += "<td><span class='label label-warning'>"+value.status+"</span></td>";
	//						}							
	//						if(value.status == 'CORRUPT')
	//						{
	//							txt += "<td><span class='label label-danger'>"+value.status+"</span></td>";
	//						}

	//                        txt += "<td align='right'>" + formatNumber(value.amount) + "</td>";
	//                        txt += "<td align='right'>" + formatNumber(value.reminder) + "</td>";
	                        //txt += "<td><a id="full" data-target='#full-' data-toggle="modal" class="btn btn-success btn-sm">Bayar Penuh</a></td>";

	//                    	if (value.status == 'PAID')
	//                    	{
	//                    		txt += "<td><a id='full' data-toggle='modal' class='btn btn-success btn-sm' style='display:none'>Bayar Penuh</a></td>";
	//                    		txt += "<td><a id='free' data-toggle='modal' class='btn btn-success btn-sm' style='display:none'>Bayar Bebas</a></td>";
	//                    	}

	//                    	if (value.status == 'UNPAID' || value.status == 'PARTIAL' || value.journal == 0)
	//                        {
	//                        	txt += "<td><a id='full' data-id='"+value.id+"' data-target='#full-"+value.id+"' data-toggle='modal' class='btn btn-success btn-sm'>Bayar Penuh</a></td>";
	//                        	txt += "<td><a id='free' data-id='"+value.id+"' data-target='#free-"+value.id+"' data-toggle='modal' class='btn btn-warning btn-sm'>Bayar Bebas</a></td>";
	//                    	}	                    	

	//						if(value.status == 'PARTIAL' || value.journal == 1)
	//						{
	//							txt += "<td><a id='full' data-target='#full' data-toggle='modal' class='btn btn-success btn-sm' style='display:none'>Bayar Penuh</a></td>";
	//							txt += "<td><a id='free' data-target='#free' data-toggle='modal' class='btn btn-warning btn-sm' style='display:none'>Bayar bebas</a></td>";
	//						}
	                        
	//                        nour++;
	                        //}
	//                    });
	//                txt += "</table>" 
	//				txt += "</div>";
	//            }
	//        });
	//       return txt;
	//});
	
	//$('.angsuran').click(function(){
		$.ajax({
			headers: {
				'X-CSRF-Token': $('meta[name="_token"]').attr('content')
			},
			url: "{{ url('installment/getCicilan') }}/"+memberNumber,
			type: 'post',
			data: {memberNumber: memberNumber},
			success: function(response){ 
				//$('.modal-body').html(response);
				$('#bodyAngsur').html(response);
				$('#angsur').modal('show');
			},
			error: function(error) {
				console.log(error);
			}
		});	
	//});		
	
}
</script>
<script>
$('.btnFree').on('click', function (event) {	
	var a = $('btnFree');
	var ids = a.data('id');
	var member = a.data('member');
	$('.modal-body').html("");
	$('#angsur').on('hidden.bs.modal', function (e) {
		$('#free-'+ids).modal('show');
	});
	return false;
});
</script>
<script>
$('.btnFull').on('click', function (event) {	
	var a = $('btnFull');
	var ids = a.data('id');
	var member = a.data('member');
	$('.modal-body').html("");
	$('#angsur').on('hidden.bs.modal', function (e) {
		$('#full-'+ids).modal('show');
	});
	return false;
});
</script>
@endsection