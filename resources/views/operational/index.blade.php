@extends('layouts.app')
@section('content')
    <meta name="_token" content="{!! csrf_token() !!}" />

    @include('error.error-notification')

    <div class="box">

        <div class="box-header">
            <h4>Pending Transaksi Operasional</h4>
            <hr>
            <div class="text-right">
                <button id="create" data-toggle="modal" data-target="#trxop" class="btn btn-sm btn-success">
                    <i class="fa fa-plus" title="{{ trans('general.new') }}"></i> Tambah Transaksi Operasional
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-responsive table-striped" id="trxoperational" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Waktu Transaksi</th>
                            <th>Tipe Transaksi</th>
                            <th>Jumlah</th>
                            <th>Deskripsi</th>
                            <th>Dibuat Oleh</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="trxop" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('operational.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="trxtype">Jenis Transaksi Operational</label>
                            <div class="row">
                                <div class="col-md-12">
                                    <select name="trxtype" id="trxtype" class="form-control" style="width: 100%;">
                                        @foreach ($trx as $tr)
                                            <option value="{{ $tr->transaction_type }}">{{ $tr->transaction_type }} - {{ $tr->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ket">Keterangan</label>
                            <input type="text" class="form-control" name="ket">
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <input type="text" class="form-control" name="jumlah">
                        </div>
                        <div class="text-right">
                            <button class="btn btn-success" id="save" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btnAngsur" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            //var template = Handlebars.compile($("#details-template").html());
            var dataTables = $('#trxoperational').DataTable({
                "processing": true,
                "serverSide": true,
                "deferRender": true,
                "ajax": {
                    url: "{{ route('operational.json') }}",
                    cache: true
                },

                "columns": [{
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: "mutation_date"
                    },
                    {
                        data: "transaction_type"
                    },
                    {
                        data: "amount"
                    },
                    {
                        data: "description"
                    },
                    {
                        data: "created_by"
                    },
                    {
                        render: function(data, type, row) {
                            return '<a href="/operational/detail/' + row.id +
                                '">Detail | </a> <a href="/operational/approve/' + row.id +
                                '" >Approve | </a> <a href="/operational/delete/' + row.id +
                                '">Hapus</a>'
                        }
                    },
                ],
                "order": [
                    [1, 'asc']
                ]
            });
            $('#trxtype').select2({
                dropdownParent: $('#trxop')
            });

        });

    </script>
@endsection
