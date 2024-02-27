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
            var dataTables = $('#installment').DataTable({
                "processing": true,
                "serverSide": true,
                "deferRender": true,
                "ajax": {
                    url: "{{ route('installment.lunas') }}",
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
                    {data: "name"},
                    {data: "contract_date"},
                    {data: "loan_number"},
                    {data: "loan_amount", render: $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')},
                    {data: "time_period"},
                    {data: "interest_rate"},
                    {data: "pay_principal", render: $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')},
                    {data: "pay_interest", render: $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')},
                    {data: "pay_month", render: $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')},
                    {data: "loan_remaining", render: $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')},
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
        });
    </script>

@endsection
