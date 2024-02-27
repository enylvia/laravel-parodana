@extends('layouts.app')
@section('content')
<meta name="_token" content="{!! csrf_token() !!}"/>
@include('error.error-notification')

	<div class="box">
		<div class="box-header">
			<a href="/customer/balance/create" class="btn btn-xs btn-success" target="_blank">
				<i class="fa fa-plus" title="Add"></i>
			</a>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive table-striped" style="width:100%;" id="balance">
					<thead>
						<tr>
							<th class="text-center">No. Transaksi</th>
							<th class="text-center">Tgl. Transaksi</th>
							<th class="text-center">Nasabah</th>
							<th class="text-center">No. Nasabah</th>
							<th class="text-center">No. ATM</th>
							<th class="text-center">No. PIN</th>
							<th class="text-center">Nama BANK</th>
							<th class="text-center">Saldo</th>
							<th class="text-center" colspan="3">{{trans('general.actions')}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<div class="box-footer">
			
		</div>
	</div>	
	@include('customer.balance.edit')
	@include('customer.balance.delete')
	@include('customer.balance.journal')
	
@endsection

@section('js')
	<script type="text/javascript">
		$(document).ready( function () {
		$.ajaxSetup({
		headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
		});
		
		$('#balance').DataTable(
			{	
				processing: false,
				serverSide: true,
				//responsive: true,
				ajax: "{{ route('balance') }}",		
				columns: [
					//{ data: 'avatar', name: 'user.image',
					//	render: function( data, type, full, meta ) {
					//		return "<img src=\"/uploads/photo/" + data + "\" height=\"60px\" width=\"60px\"/>";
					//	}
					//},
					{ data: 'transaction_no', name: 'account_balance.transaction_no' },
					{ data: 'mutation_date', name: 'mutation_date' },
					{ data: 'name', name: 'customer.name' },				
					{ data: 'member_number', name: 'loans.member_number' },
					{ data: 'atm_number', name: 'atm_number' },
					{ data: 'bank_pin', name: 'bank_pin' },
					{ data: 'bank_name', name: 'bank_name' },
					{ data: 'amount', render: $.fn.dataTable.render.number( '.' , ',', 0, 'Rp. ') },
					//{ data: 'btnMutasiIn', name: 'btnMutasiIn' },
					//{ data: 'btnMutasiOut', name: 'btnMutasiOut' },
					{ data: 'edit', name: 'edit' },
					//{ data: 'view', name: 'view' },
					{ data: 'delete', name: 'delete' },
					//{ data: 'btnHistory', name: 'btnHistory' },
					{ data: 'print', name: 'print' },
					{ data: 'journal', name: 'journal' }
				],
				order: [[0, 'desc']]
			});
		});
	</script>
	
	<script type="text/javascript">

    // CSRF Token
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function(){
		//for (let i = 0; i < 500; i++) {
		//  no += "The number is " + i + "<br>";
		//}
		$( "#type_in" ).select2({
			placeholder: 'Select',
			ajax: { 
			  url: "{{route('balance.loadtype')}}",
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
    </script>
	<script type="text/javascript">

    // CSRF Token
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function(){

      $( "#type_out" ).select2({
		placeholder: 'Select',
        ajax: { 
          url: "{{route('balance.loadtype')}}",
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
    </script>
@endsection
