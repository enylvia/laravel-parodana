@extends('layouts.app')
@section('content')

    @include('error.error-notification')

    <div class="box">
        <div class="box-header">

        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-responsive-sm table-striped" id="custlist" style="width: 100%;">
                    <thead>
                    <tr>
                    <th>{{trans('general.name')}}</th>
					<th>{{trans('general.member_number')}}</th>
					<th>{{trans('general.date')}}</th>
					<th>{{trans('general.contract_number')}}</th>
					<th class="text-center" colspan="3">{{trans('general.actions')}}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

@endsection
@section('js')

    <script type="text/javascript">
        $(document).ready(function() {
            //var template = Handlebars.compile($("#details-template").html());
             var dataTables = $('#custlist').DataTable({
                // "processing": true,
                "serverSide": true,
                "deferRender": true,
                "ajax": {
                    url: "{{ route('contract.json') }}",
                    cache: true
                },

                "columns": [
                    {data: "name"},
                    {data: "member_number"},
                    {data: "contract_date"},
                    {data: "contract_number"},

					// href to detail
					{
						render: function(data, type, row) {
							return '<a href="/customer/contract/detail/'+row.customer_id+'" class="btn btn-sm btn-primary">Detail</a>';
						}
					},
                ],
				"order": [0, 'asc'],
            });
        });
    </script>

@endsection
