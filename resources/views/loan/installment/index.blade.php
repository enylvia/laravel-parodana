@extends('layouts.app')
@section('content')

@include('error.error-notification')

	<div class="box">
		<div class="box-header">

		</div>
		<div class="box-body">
			<div class="table-responsive">
                This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
				<table class="table table-responsive-sm table-striped" id="installment">
					<thead>
						<tr>
							<th></th>
							<th>No</th>
							<th>Customer Name</th>
							<th>Contract Date</th>
							<th>Loan No</th>
							<th>Loan Amount</th>
							<th>Time Period</th>
							<th>Interest Rate</th>
							<th>Pay Principal</th>
							<th>Pay Interest</th>
							<th>Pay Month</th>
							<th>Sisa</th>
							<!-- <th>View</th>
							<th>Edit</th> -->
							<th>Create Table</th>
							<th>Angsuran</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
	<!-- Ful Modal -->
	<div class="modal fade" id="fullModal">
	    <div class="modal-dialog">
	        <div class="modal-content">
	        	<form method="post" action="" id="fullAction" enctype="multipart/form-data">
				{{ csrf_field() }}
	        	<!--form enctype="multipart/form-data" id="fullForm"-->
					<input type="hidden" class="form-control" name="angsuran_id" value="" id="angsuran_ids">
					<input type="hidden" class="form-control" name="memberNumber" value="" id="member_numbers">
					<input type="hidden" class="form-control" name="loanNumber" value="" id="loan_numbers">
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
						<div class="form-group col-md-4">
							<label for="name" class="control-label">{{ trans('installment.payment_method') }}</label>
							<select name="payment_method" class="form-control" required>
								<option value="Tunai">{{ trans('installment.cash')}}</option>
								<option value="Kartu Debet">{{ trans('installment.debit_card')}}</option>
								<option value="Kartu Debet OCBC">{{ trans('installment.debit_ocbc')}}</option>
								<option value="Kartu Debet Permata">{{ trans('installment.debit_permata')}}</option>
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
	<!-- FREE  Modal-->
	<div class="modal fade" id="free" tabindex="-1">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <form method="post" action="" id="freeForm" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" class="form-control" name="inst_to" value="" id="inst_to">
					<input type="hidden" class="form-control" name="free_id" value="" id="free_id">
					<input type="hidden" class="form-control" name="memberNumber" value="" id="member_number">
					<input type="hidden" class="form-control" name="pay_status" value="FREE" id="pay_status">
					<div class="modal-header">
						<h4 class="modal-title">Bayar Bebas</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" id="bodyFree">
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
							<input type="text" class="form-control" name="loan_number" value="" id="loan_number">
						</div>
						<div class="form-group col-md-12">
							<label for="transfer">Transfer Masuk</label>
							<input type="text" class="form-control" name="transfer_in" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="transfer_in_free" required>
							<span class="text-live">
								<strong id="transfer-msg-err"></strong>
							</span>
						</div>
						<input type="hidden" class="form-control" name="pay_date" value="" id="pay_date">
						<input type="hidden" class="form-control" name="byrTempo" value="">
						<input type="hidden" class="form-control" name="byrWajib" value="">
						<input type="hidden" class="form-control" name="byrCicilan" value="">
						<div class="form-group col-md-12">
							<label for="name" class="control-label">{{ trans('installment.payment_method') }}</label>
							<select name="payment_method" class="form-control" required>
								<option value="Tunai">{{ trans('installment.cash')}}</option>
								<option value="Kartu Debet">{{ trans('installment.debit_card')}}</option>
								<option value="Kartu Debet OCBC">{{ trans('installment.debit_ocbc')}}</option>
								<option value="Kartu Debet Permata">{{ trans('installment.debit_permata')}}</option>
							</select>
						</div>
						<div class="form-group col-md-12">
							<label for="transfer">Jumlah</label>
							<input type="hidden" class="form-control" name="tagihan" value="">
							<input type="text" class="form-control" name="amount" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="amount_free" required>
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
<!-- end of modal -->
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
					<button type="button" class="btn btn-default btnAngsur" data-dismiss="modal">{{trans('general.close')}}</button>
					<!--input type="submit" class="btn btn-success" value='Simpan'/-->
				</div>
			</div>
		</div>
	</div>

@endsection

@section('js')

<script type="text/javascript">
	// make function that run when the button is clicked
	function formatNumber(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
	$.fn.dataTable.ext.errMode = 'throw';
	$(document).ready(function() {
		//var template = Handlebars.compile($("#details-template").html());
	    var dataTables = $('#installment').DataTable({
	      	"processing":true,
	      	"serverSide":true,
            "deferRender": true,
	      	"ajax":{
	        	url: "{{ route('installment') }}",
                cache: true
	      	},

	      "columns": [
	          {
	            "class":          "details-control",
	            "orderable":      false,
	            "data":           null,
	            "defaultContent": "",

              },
			  {
                        render: function (data,type,row,meta){
                            return meta.row + meta.settings._iDisplayStart+1;
                        },
                    },
				{data: "name"},
				{data: "contract_date"},
				{data: "loan_number"},
				{data: "loan_amount", render: $.fn.dataTable.render.number('.' , ',', 0, 'Rp. ')},
				{data: "time_period"},
				{data: "interest_rate"},
				{data: "pay_principal", render: $.fn.dataTable.render.number('.' , ',', 0, 'Rp. ')},
				{data: "pay_interest", render: $.fn.dataTable.render.number('.' , ',', 0, 'Rp. ')},
				{data: "pay_month", render: $.fn.dataTable.render.number('.' , ',', 0, 'Rp. ')},
				{data: "loan_remaining", render: $.fn.dataTable.render.number('.' , ',', 0, 'Rp. ')},
				// {
                //         render: function (data,type,row){
                //             return '<a href="/installment/view/'+row.loan_number+'" class="btn btn-xs btn-warning" target="_self"><i class="fa fa-eye" title="Lihat"></i></a>';
                //         }
                //     },
				// {
                //         render: function (data,type,row){
                //             return '<a href="/installment/edit/'+row.loan_number+'" class="btn btn-xs btn-warning" target="_self"><i class="fa fa-edit" title="Edit"></i></a>';
                //         }
                //     },
					{
                        render: function (data,type,row){
							if (row.is_created === 1) {
								return '<a href="" class="" target="_self"><i class="" title="Buat Table"></i></a>';
							}else{
								return '<a href="/installment/create/table/'+row.loan_number+'" class="btn btn-xs btn-success" target="_self"><i class="fa fa-plus" title="Buat Table"></i></a>';
							}
						}
					},
				{
                        render: function (data,type,row){
							return '<a class="btn btn-default btn-xs angsuran" onClick="ShowModal(this)" data-id="'+row.loan_number+'"><i class="fa fa-money"></i></a>';
                        }
                    }
	      ],
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
	        var txt = "";
	        var nour = 1;
	        $.ajax({
	            url: "{{ url('installment/getDetail') }}/"+d.loan_number,
	            async: false,
	            dataType: "json",
	            success: function(response) {
	                txt += "<table class='table table-hover' border='0'>";
	                txt += "<thead><tbody><th>No.</th><th>Angsuran</th><th>Jatuh Tempo</th><th>Tgl. Bayar</th><th>Metode Bayar</th><th>Status</th><th>saldo</th><th>Sisa</th><th class='text-center' colspan='2'>Aksi</th></tbody></thead>";
	                    $.each(response.data, function( index, value ) {
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
   $("#loan_number").change(function () {
     var ambilNama = $("#member-"+this.value).data('nama');
     $("#custome_name").val(ambilNama);
   });
}
</script>

<script>
function ShowModal(elem){
    var loanNumber = $(elem).data("id");
		$.ajax({
			headers: {
				'X-CSRF-Token': $('meta[name="_token"]').attr('content')
			},
			url: "{{ url('installment/getCicilan') }}/"+loanNumber,
			type: 'post',
			data: {loanNumber: loanNumber},
			success: function(response){
				//$('.modal-body').html(response);
				$('#bodyAngsur').html(response);
				$('#angsur').modal('show');
			},
			error: function(error) {
				console.log(error);
			}
		});

}
function ShowModalFree(elem){
	var loanNumber = $(elem).data("loan");
	var angsuranID = $(elem).data("id");
	var inst_to = $(elem).data("inst");
	var memberNumbers = $(elem).data("members");
	var action = "{{URL::to('/installment/free_store')}}/"+angsuranID;
	$('#loan_number').val(loanNumber);
	$('#free_id').val(angsuranID);
	$('#angsuran_id').val(angsuranID);
	$('#inst_to').val(inst_to);
	$('#member_number').val(memberNumbers);
	$('#freeForm').attr('action', action);
		$('#free').modal('show');
}

function ShowModalFull(elem){
	var loanNumber = $(elem).data("loan");
	var memberNumbers = $(elem).data("members");
	var angsuranID = $(elem).data("id");
	var action = "{{URL::to('/installment/full_store')}}/"+angsuranID;
	$('#loan_numbers').val(loanNumber);
	$('#angsuran_ids').val(angsuranID);
	$('#member_numbers').val(memberNumbers);
	$('#fullAction').attr('action', action);
		$('#fullModal').modal('show');
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
<script>
$('.btnRepayment').on('click', function (event) {
	var a = $('btnRepayment');
	var ids = a.data('id');
	var member = a.data('member');
	$('.modal-body').html("");
	$('#angsur').on('hidden.bs.modal', function (e) {
		$('#repayment-'+ids).modal('show');
	});
	return false;
});
</script>
<!-- <script>
$('#saveBtnFree').on('click', function () {
	var uangMasuk = document.getElementById("transfer_in_free").value;
	console.log(uangMasuk);
	var jmlBayar = document.getElementById("amount_free").value;
	console.log(jmlBayar);
	if (uangMasuk < jmlBayar) {
		console.log(true)
	}else{
		console.log(false)
	}
	if (uangMasuk < jmlBayar) {
		alert("Uang masuk lebih kecil dari jumlah bayar");
		return false;
	} else {
		return true;
	}
});
</script> -->

@endsection
