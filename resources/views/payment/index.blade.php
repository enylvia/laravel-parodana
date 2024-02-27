@extends('layouts.app')
@section('content')

@include('error.error-notification')	
	
	<div class="box">
		<div class="box-header">
			<!--a id="create" data-target="#create" data-toggle="modal" class="btn btn-success btn-sm">
				<span class="cil-note-add"></span> {{trans('general.new')}}
			</a-->
			<a href="/transaction/payment/create" class="btn btn-xs btn-warning" target="_blank">
				<i class="fa fa-plus" title="Add"></i>
			</a>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive-sm table-striped" style="width:100%" id="custlist">
					<thead>
						<tr>							
							<th>{{trans('general.transaction_code')}}</th>
							<th>{{trans('general.transaction_date')}}</th>
							<th>{{trans('general.customer')}}</th>
							<!--th>{{trans('general.from')}}</th>
							<th>{{trans('general.to')}}</th-->
							<th>{{trans('general.transaction_type')}}</th>
							<th>{{trans('general.amount')}}</th>
							<th>Status</th>
							<th class="text-center" colspan="4">{{trans('general.actions')}}</th>
						</tr>
					</thead>					
				</table>			
			</div>
		</div>
		
	</div>
	
	@include('payment.delete')
	@include('payment.journal')
	
@endsection

@section('js')

<script type="text/javascript">
	$(document).ready( function () {
	$.ajaxSetup({
	headers: {
	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
	});
	
	$('#custlist').DataTable(
		{				
			//"scrollY": 200,
			//"scrollX": true,
			processing: false,
			serverSide: true,
			//responsive: true,			
			ajax: "{{ route('payment') }}",		
			columns: [		
				{ data: 'transaction_code', name: 'transaction_code' },
				{ data: 'pay_date', name: 'pay_date' },
				{ data: 'customer_name', name: 'customer_name' },
				{ data: 'transaction_type', name: 'transaction_type'},
				{ data: 'amount', render: $.fn.dataTable.render.number( '.' , ',', 0, 'Rp. ') },
				{ data: 'status', name: 'status'},
				{ data: 'edit', name: 'edit'},
				{ data: 'delete', name: 'delete'},
				{ data: 'print', name: 'print'},
				{ data: 'journal', name: 'journal'}
			],
			order: [[0, 'desc']]
		});
	});
	function add(){
	$('#CompanyForm').trigger("reset");
	$('#CompanyModal').html("Add Company");
	$('#company-modal').modal('show');
	$('#id').val('');
	}   
	function editFunc(id){
		$.ajax({
		type:"POST",
		url: "{{ url('edit-company') }}",
		data: { id: id },
		dataType: 'json',
		success: function(res){
		$('#CompanyModal').html("Edit Company");
		$('#company-modal').modal('show');
		$('#id').val(res.id);
		$('#name').val(res.name);
		$('#address').val(res.address);
		$('#email').val(res.email);
		}
		});
	}  
	function deleteFunc(id){
	if (confirm("Delete Record?") == true) {
	var id = id;
	// ajax
	$.ajax({
	type:"POST",
	url: "{{ url('delete-company') }}",
	data: { id: id },
	dataType: 'json',
	success: function(res){
	var oTable = $('#ajax-crud-datatable').dataTable();
	oTable.fnDraw(false);
	}
	});
	}
	}
	$('#CompanyForm').submit(function(e) {
	e.preventDefault();
	var formData = new FormData(this);
	$.ajax({
	type:'POST',
	url: "{{ url('store-company')}}",
	data: formData,
	cache:false,
	contentType: false,
	processData: false,
	success: (data) => {
	$("#company-modal").modal('hide');
	var oTable = $('#ajax-crud-datatable').dataTable();
	oTable.fnDraw(false);
	$("#btn-save").html('Submit');
	$("#btn-save"). attr("disabled", false);
	},
	error: function(data){
	console.log(data);
	}
	});
	});
</script>
@endsection