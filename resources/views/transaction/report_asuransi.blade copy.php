@extends('layouts.app')
@section('content')

    @include('error.error-notification')

    <div class="box">
        <div class="box-header">

        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-responsive-sm table-striped" id="insurance" style="width: 100%;">
                    <thead>
                    <tr>
                    <th>NO</th>
                    <th>NO PINJAMAN</th>
                    <th>NAMA</th>
                    <th>PERUSAHAAN</th>
                    <th>JW</th>
                    <th>PLAFON</th>
                    <th>ASURANSI</th>
                    <th>PERSEN ASURANSI</th>
                    <th>IURAN ASURANSI</th>
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
            var dataTables = $('#insurance').DataTable({
                "processing": true,
                "serverSide": true,
                "deferRender": true,
                "ajax": {
                    url: "{{ route('insurance.index') }}",
                    cache: true
                },

                "columns": [
                    {
                        render: function (data,type,row,meta){
                            return meta.row + meta.settings._iDisplayStart+1;
                        },
                    },
                    {data: "loan_number"},
                    {data: "name_user"},
                    {data: "company"},
                    {data: "duration"},
                    {data: "loan_amount", render: $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')},
                    {data: "no_kontrak"},
                    {data: "insurance", render: function (data, type, row) {
                            return data + ' %';
                        }
                    },
                    {
                        data: "loan_amount",
                        render: function(data, type, row) {
                            // Lakukan operasi matematika di sini
                            var hasil = data * (row.insurance / 100);
                            // format nilai 
                            hasil = $.fn.dataTable.render.number('.', ',', 0, 'Rp. ').display(hasil);
                            return hasil;
                        }
                    },
                ],
                "order": [[1, 'asc']]
            });
        });
    </script>

@endsection
