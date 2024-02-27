@extends('layouts.app')
@section('content')

@if(session('errors'))
    <div class="alert alert-danger">
        {{ session('errors') }}
    </div>
@elseif(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <div class="box">
        <div class="box-header">

        </div>
        <div class="box-body">
            <div class="table-responsive">
                This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
                <table class="table table-responsive-sm table-striped" style="width:100%" id="customersList">
					<thead>
						<tr>
							<th></th>
							<th>No</th>
							<th>Photo</th>
							<th>Name</th>
							<th>Alamat</th>
							<th>Mobile Phone</th>
							<th>Jenis Kelamin</th>
							<th>No KTP</th>
							<th>No KK</th>
							<th>Status</th>
                            <th>Action</th>
						</tr>
					</thead>					
				</table>		
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
            var dataTables = $('#customersList').DataTable({
                "processing": true,
                "serverSide": true,
                "deferRender": true,
                "ajax": {
                    url: "{{ route('customer') }}",
                    cache: true
                },

                "columns": [
                    {
                        "class": "details-control",
                        "orderable": false,
                        "data": null,
                        "defaultContent": "",

                    },
					{
                        render: function (data,type,row,meta){
                            return meta.row + meta.settings._iDisplayStart+1;
                        },
                    },
					{
                        render: function (data,type,row,meta){
                            return "<img src='{{ asset('uploads/photo') }}/"+row.avatar+"' width='50px' height='50px'>";
                        },
                    },
					
					{data: 'name' , name: 'name'},
					{data: 'address' , name: 'address'},
					{data: 'mobile_phone' , name: 'mobile_phone'},
					{data: 'gender' , name: 'gender'},
					{data: 'card_number', name: 'card_number'},
					{data: 'family_card_number', name: 'family_card_number'},
					{data: 'status', name: 'status'},
                    {
                        render: function (data,type,row,meta){
                            return '<a href="/customer/list/edit/'+row.id+'" class="btn btn-xs btn-success" target="_self">Edit</a>';
                        },
                    },
                ],
            });
        });
    </script>
@endsection