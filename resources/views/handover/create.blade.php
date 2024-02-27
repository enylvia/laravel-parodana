@extends('layouts.app')
@section('content')
    @include('error.error-notification')

    @foreach ($customers as $customer)
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="box">

            <!--form name="add_document" id="add_document"-->
            <form method="post" action="{{ route('handover.store') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="customer_id" value="{{ $customer->reg_number }}">
                <div class="box-header">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-responsive table-striped" id="items">
                            <thead>
                                <tr style="background-color: #f9f9f9;">
                                    <th width="5%" class="text-center">Aksi</th>
                                    <th>Berkas</th>
                                    <th colspan="2">Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="addItem">
                                    <td class="text-center"><button type="button" onclick="addItem();"
                                            data-toggle="tooltip" title="add" class="btn btn-xs btn-primary"
                                            data-original-title="add"><i class="fa fa-plus"></i></button></td>
                                    <td class="text-right" colspan="7"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-success" type="submit">
                        <span class="fa fa-save"></span> {{ 'Save' }}
                    </button>
                    <span class="new-button">
                        <a href="{{ route('handover') }}" class="btn btn-danger">
                            <span class="fa fa-close"></span> {{ trans('general.close') }}
                        </a>
                    </span>
                </div>
            </form>
        </div>
        </div>
    @endforeach
@endsection

@section('js')
    <script type="text/javascript">
        var item_row = 0;

        function addItem() {
            // html = '<tr id="item-row-' + item_row + '">';
            // html += '  <td class="text-center" style="vertical-align: middle;">';
            // html += '      <button type="button" onclick="$(this).tooltip(\'destroy\'); $(\'#item-row-' + item_row +
            //     '\').remove(); totalItem();" data-toggle="tooltip" title="Hapus" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>';
            // html += '  </td>';

            // html += '  <td>';
            // html += '		<input type="hidden" name="customer_id[]" value="{{ $customer->reg_number }}" id="item-row-' +
            //     item_row + '">';
            // html += '      	<input class="form-control text-left" required name="berkas[]" type="text" id="item-row-' +
            //     item_row + '" >';
            // html += '  </td>';

            // html += '  <td>';
            // html += '      <select class="form-control select2 select2-hidden-accessible" style="width: 100%;"  ';
            // html += '      tabindex="-1" aria-hidden="true" name="status[]" id="item-row-' + item_row + '">';
            // html += '         <option value="copy" disable="true" selected="true">FOTO COPY</option>';
            // html += '         <option value="asli" disable="true" selected="true">ASLI</option>';
            // html += '       </select>';
            // html += '  </td>';

            // html += '  <td>';
            // html += '      <input class="form-control text-left" required name="keterangan[]" type="text" id="item-row-' +
            //     item_row + '">';
            // html += '  </td>';

            // $('#items tbody #addItem').before(html);

            $('#items tbody #addItem').before(`
            <tr id="item-row-${item_row}">
                <td class="text-center" style="vertical-align: middle;">
                    <button type="button" onclick="$(this).tooltip('destroy'); $('#item-row-${item_row}').remove(); totalItem();" data-toggle="tooltip" title="Hapus" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                </td>
                <td>
                    <input class="form-control text-left" required name="berkas[${item_row}][nama]" type="text">
                </td>
                <td>
                    <label>
                    <input type="radio" name="berkas[${item_row}][status]" value="asli" />
                        Asli
                    </label>
                </td>
                <td>
                    <label>
                    <input type="radio" name="berkas[${item_row}][status]" value="copy" />
                        Copy
                    </label>
                </td>
                <td>
                    <input class="form-control text-left" required name="berkas[${item_row}][keterangan]" type="text">
                </td>
            </tr>
            `);
            //$('[rel=tooltip]').tooltip();

            $('[data-toggle="tooltip"]').tooltip('hide');

            $('#item-row-' + item_row + ' .select2').select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}"
            });

            item_row++;
        }

        function totalItem() {
            $.ajax({
                url: '{{ url('items/items/totalItem') }}',
                type: 'POST',
                dataType: 'JSON',
                data: $(
                    '#currency_code, #items input[type=\'text\'],#items input[type=\'hidden\'], #items textarea, #items select'
                    ),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data) {
                        $.each(data.items, function(key, value) {
                            $('#item-total-' + key).html(value);
                        });

                        $('#sub-total').html(data.sub_total);
                        $('#tax-total').html(data.tax_total);
                        $('#grand-total').html(data.grand_total);
                    }
                }
            });
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            var i = 1;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('#submit').click(function() {
                $.ajax({
                    url: "{!! url('/customer/handover/store') !!}",
                    method: "POST",
                    data: $('#add_document').serialize(),
                    type: 'json',
                    success: function(data) {
                        if (data.error) {
                            printErrorMsg(data.error);
                        } else {
                            i = 1;
                            $('.dynamic-added').remove();
                            $('#add_document')[0].reset();
                            $(".print-success-msg").find("ul").html('');
                            $(".print-success-msg").css('display', 'block');
                            $(".print-error-msg").css('display', 'none');
                            $(".print-success-msg").find("ul").append(
                                '<li>Record Inserted Successfully.</li>');
                        }
                        window.location.href = "{!! url('/customer/handover') !!}";
                    },
                    error: function(data, textStatus, errorThrown) {
                        console.log(data);
                    }
                });
            });


            function printErrorMsg(msg) {
                $(".print-error-msg").find("ul").html('');
                $(".print-error-msg").css('display', 'block');
                $(".print-success-msg").css('display', 'none');
                $.each(msg, function(key, value) {
                    $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
                });
            }
        });
    </script>
@endsection
