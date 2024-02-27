@extends('layouts.app')
@section('content')
<meta name="_token" content="{!! csrf_token() !!}"/>
@include('error.error-notification')

	<div class="box">
		<div class="box-header">
		
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive table-striped" style="width:100%;" id="balance">
					<thead>
						<tr>
							<th class="text-center">Poto</th>
							<th class="text-center">Nasabah</th>
							<th class="text-center">No Anggota</th>
							<th class="text-center">No Rekening</th>
							<th class="text-center">No PIN</th>
							<th class="text-center">Nama Bank</th>
							<th class="text-center" colspan="4">Mutasi</th>
							<!--th colspan="2" class="text-center">{{trans('general.actions')}}</th-->
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
		
	@include('customer.balance.mutasiin')
	@include('customer.balance.mutasiout')
	
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
				{ data: 'avatar', name: 'user.image',
                    render: function( data, type, full, meta ) {
                        return "<img src=\"/uploads/photo/" + data + "\" height=\"60px\" width=\"60px\"/>";
                    }
                },
				{ data: 'name', name: 'customer.name' },				
				{ data: 'member_number', name: 'loans.member_number' },
				{ data: 'atm_number', name: 'atm_number' },
				{ data: 'bank_pin', name: 'bank_pin' },
				{ data: 'bank_name', name: 'bank_name' },
				{ data: 'btnMutasiIn', name: 'btnMutasiIn' },
				{ data: 'btnMutasiOut', name: 'btnMutasiOut' },
				{ data: 'btnView', name: 'btnView' },
				{ data: 'btnHistory', name: 'btnHistory' }
			],
			order: [[0, 'desc']]
		});
	});
</script>
<!--script>
$(document).ready(function(){

	fetch_customer_data();
	fetch_page();
	$('.pagination li a').click(function(e) {
		e.preventDefault();		
		var page = $(this).attr('href').split('page=')[1];
		$('li').removeClass('active');
		$(this).parent('li').addClass('active');
		fetch_customer_data('',page);
		//fetch_page(page);
	});

	function fetch_customer_data(query = '', page = '')
	{		
		$.ajax({
		url:"{{ route('balance.loaddata') }}",  
		//url:"/customer/balance/load?query="+query+"&page="+page,
		method:'GET',
		data:{query:query, page:page},
		dataType:'json',
		success:function(data)
		{
			$('tbody').html(data.table_data);
			$('#total_records').text(data.total_data);
		//location.hash = page;
		},
			error: function (data, textStatus, errorThrown) {
			console.log(data);
		}
		})
	}
 
	function fetch_page(page = '')
	{
		var _token = $("input[name='_token']").val();
		$.ajax({
			url:"balance?page="+page,
			method:'POST',
			data:{_token:_token, page:page},
			dataType:'json',
			success:function(data)
			{
				$('tbody').html(data.table_data);
				$('#total_records').text(data.total_data);
			},
				error: function (data, textStatus, errorThrown) {
				console.log(data);
			}
		});
	}
 
	$(document).on('keyup', '#search', function(){
		var query = $(this).val();
		var page = $('#page option:selected').val();
		fetch_customer_data(query,page);
	});
	 
	$(document).on('change', '#page', function(e){	 
		var page = $('#page option:selected').val();
		//alert(page);  
		fetch_customer_data('',page);
		fetch_page(page);
	});	
 
});

</script>

<script type="text/javascript">

	$(document).ready(function() {

		$(".in-submit").click(function(e){
			e.preventDefault();

			var _token = $("input[name='_token']").val();
			var date_trans = $("input[name=date_trans]").val();
			var cust_id = $("input[name=cust_id]").val();
			var member_number = $("input[name=member_number]").val();
			var acc_number = $("input[name=acc_number]").val();
			var acc_to = $("input[name=acc_to]").val();
			var payment_type = $("input[name=payment_type]").val();
			var payment_method = $('#in_payment_method option:selected').val();
			var amount = $("input[name=amount]").val();
			var description = $("input[name=description]").val();

			$.ajax({
				url: "{{ route('balance.store') }}",
				type:'POST',
				data: {_token:_token, date_trans:date_trans,
						cust_id:cust_id,
						member_number:member_number,
						acc_number:acc_number,
						acc_to:acc_to,
						payment_type:payment_type,
						payment_method:payment_method,
						amount:amount,
						description:description},
				success: function(data) {
				  printMsg(data);
				  $("#cform")[0].reset();
				  $('#MutasiIn-'+cust_id).modal('hide');
				},
				error: function (data, textStatus, errorThrown) {
					console.log(data);
				}
			});
		}); 

		function printMsg (msg) {
		  if($.isEmptyObject(msg.error)){
			  console.log(msg.success);
			  $('.alert-block').css('display','block').append('<strong>'+msg.success+'</strong>');
		  }else{
			$.each( msg.error, function( key, value ) {
			  $('.'+key+'_err').text(value);
			});
		  }
		}
		
	});

</script-->

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
