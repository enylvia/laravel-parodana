@extends('layouts.app')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('error.error-notification')

    @foreach ($customers as $customer)
        <div class="box">
            <div class="box-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-responsive table-striped" id="items">
                        <thead>
                            <tr style="background-color: #f9f9f9;">
                                <th width="5%" class="text-center">No</th>
                                <th>Berkas</th>
                                <th colspan="2">Status</th>
                                <th>Keterangan</th>
                                <th class="text-center" colspan="2">{{ trans('general.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form id="form-item" method="POST" action="{{ URL::to('/customer/handover/store') }}">
                                <input form="form-item" type="hidden" name="_token" value="{{ csrf_token() }}">

                                @php
                                    $default_berkas = ['Buku Tabungan & ATM', 'Ijazah', 'Kartu Jamsostek', 'Buku Nikah', 'KTP', 'Kartu Keluarga', 'SK Pengangkatan', 'BPKB', 'Kartu Pengenalan Kerja (KPK)', 'NPWP'];
                                    $index = 0;
                                    $data_handover = $handovers->whereNotIn('berkas', $default_berkas);
                                @endphp
                                @foreach ($default_berkas as $key => $value)
                                    @php
                                        $data = $handovers->where('berkas', $value)->first();
                                    @endphp
                                    @if (!empty($data))
                                        <tr>
                                            <td class="row-index">
                                            </td>
                                            <td>
                                                <input form="form-item" type="text"
                                                    name="berkas[{{ $index }}][id]" value="{{ $data['id'] }}"
                                                    hidden />
                                                <input form="form-item" type="text"
                                                    name="berkas[{{ $index }}][reg_number]"
                                                    value="{{ $customer->reg_number }}" hidden />
                                                <input form="form-item" type="text"
                                                    name="berkas[{{ $index }}][nama]" value="{{ $value }}"
                                                    hidden />
                                                <input form="form-item" class="form-control" type="text" name=""
                                                    value="{{ $value }}" disabled />
                                            </td>
                                            <td>
                                                <label>
                                                    @if ($data['status'] == 'asli')
                                                        <input form="form-item" type="radio"
                                                            name="berkas[{{ $index }}][status]" value="asli"
                                                            checked />
                                                    @else
                                                        <input form="form-item" type="radio"
                                                            name="berkas[{{ $index }}][status]" value="asli" />
                                                    @endif
                                                    Asli
                                                </label>
                                            </td>
                                            <td>
                                                <label>
                                                    @if ($data['status'] == 'copy')
                                                        <input form="form-item" type="radio"
                                                            name="berkas[{{ $index }}][status]" value="copy"
                                                            checked />
                                                    @else
                                                        <input form="form-item" type="radio"
                                                            name="berkas[{{ $index }}][status]" value="copy" />
                                                    @endif
                                                    Copy
                                                </label>
                                            </td>
                                            <td>
                                                <input form="form-item" class="form-control" type="text"
                                                    name="berkas[{{ $index }}][keterangan]"
                                                    value="{{ $data['keterangan'] }}" required />
                                            </td>
                                            {{-- <td class="text-center" colspan="2">
                                                <button type="submit" data-toggle="tooltip" title="Simpan"
                                                    class="btn btn-md btn-success"><i class="fa fa-floppy-o"></i></button>
                                            </td> --}}
                                        </tr>
                                    @else
                                        <tr>
                                            <td class="row-index">
                                            </td>
                                            <td>
                                                <input form="form-item" type="text"
                                                    name="berkas[{{ $index }}][id]" value="" hidden />
                                                <input form="form-item" type="text"
                                                    name="berkas[{{ $index }}][reg_number]"
                                                    value="{{ $customer->reg_number }}" hidden />
                                                <input form="form-item" type="text"
                                                    name="berkas[{{ $index }}][nama]" value="{{ $value }}"
                                                    hidden>
                                                <input form="form-item" class="form-control" type="text" name=""
                                                    value="{{ $value }}" disabled>
                                            </td>
                                            <td>
                                                <label>
                                                    <input form="form-item" type="radio"
                                                        name="berkas[{{ $index }}][status]" value="asli" />
                                                    Asli
                                                </label>
                                            </td>
                                            <td>
                                                <label>
                                                    <input form="form-item" type="radio"
                                                        name="berkas[{{ $index }}][status]" value="copy" />
                                                    Copy
                                                </label>
                                            </td>
                                            <td>
                                                <input form="form-item" class="form-control" type="text"
                                                    name="berkas[{{ $index }}][keterangan]" required />
                                            </td>
                                            {{-- <td class="text-center" colspan="2">
                                                <button type="submit" data-toggle="tooltip" title="Simpan"
                                                    class="btn btn-md btn-success"><i class="fa fa-floppy-o"></i></button>
                                            </td> --}}
                                        </tr>
                                    @endif
                                    @php
                                        $index++;
                                    @endphp
                                @endforeach
                                @foreach ($data_handover as $handover)
                                    <tr>
                                        <td class="row-index">
                                        </td>
                                        <td>

                                            <input form="form-item" type="text" name="berkas[{{ $index }}][id]"
                                                value="{{ $handover['id'] }}" hidden />
                                            <input form="form-item" type="text"
                                                name="berkas[{{ $index }}][reg_number]"
                                                value="{{ $customer->reg_number }}" hidden />
                                            <input class="form-control" type="text"
                                                name="berkas[{{ $index }}][nama]"
                                                value="{{ $handover['berkas'] }}" />
                                        </td>
                                        <td>
                                            <label>
                                                @if ($handover['status'] == 'asli')
                                                    <input form="form-item" type="radio"
                                                        name="berkas[{{ $index }}][status]" value="asli"
                                                        checked />
                                                @else
                                                    <input form="form-item" type="radio"
                                                        name="berkas[{{ $index }}][status]" value="asli" />
                                                @endif
                                                Asli
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                @if ($handover['status'] == 'copy')
                                                    <input form="form-item" type="radio"
                                                        name="berkas[{{ $index }}][status]" value="copy"
                                                        checked />
                                                @else
                                                    <input form="form-item" type="radio"
                                                        name="berkas[{{ $index }}][status]" value="copy" />
                                                @endif
                                                Copy
                                            </label>
                                        </td>
                                        <td>
                                            <input form="form-item" class="form-control" type="text"
                                                name="berkas[{{ $index }}][keterangan]"
                                                value="{{ $handover['keterangan'] }}" required />
                                        </td>
                                        <td style="display: flex; justify-content: center;">
                                            <a href="{{ URL::to('/customer/handover/delete', $handover->id) }}"
                                                class="btn btn-md btn-danger"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach

                                <tr id="add-berkas">
                                    <td class="text-right" colspan="1"></td>
                                    <td class="text-left">
                                        <button type="button" onclick="addBerkas();" data-toggle="tooltip"
                                            title="Tambah Berkas Baru" class="btn btn-md btn-primary"
                                            data-original-title="add">Tambah Berkas</button>
                                    </td>
                                    <td class="text-right" colspan="3"></td>
                                </tr>
                            </form>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-footer text-right">
                <button form="form-item" class="btn btn-success" type="submit">
                    <span class="cil-save"></span> {{ 'Save' }}
                </button>
                <a href="{{ route('handover') }}" class="btn btn-danger">
                    <span class="fa fa-close"></span> {{ trans('general.close') }}
                </a>
            </div>
        </div>
    @endforeach
@endsection

@section('js')
    <script type="text/javascript">
        let row = {{ $index }};

        function setNomorBaris() {
            let countRow = 1;
            $('.row-index').each(function(i, e) {
                e.innerHTML = countRow;
                countRow++;
            });
        }

        function addBerkas() {
            $('#add-berkas').before(`
            <tr class="row-item">
                <td class="row-index"></td>
                <td>
                    <input form="form-item" name="_token" type="hidden" value="{{ csrf_token() }}" />
                    <input form="form-item" type="hidden" name="berkas[${row}][id]" value="" />
                    <input form="form-item" type="hidden" name="berkas[${row}][reg_number]" value="{{ $customers[0]->reg_number }}" />
                    <input form="form-item" class="form-control text-left" required name="berkas[${row}][nama]" type="text" />
                </td>
                <td>
                    <label>
                    <input form="form-item" type="radio" name="berkas[${row}][status]" value="asli" />
                        Asli
                    </label>
                </td>
                <td>
                    <label>
                    <input form="form-item" type="radio" name="berkas[${row}][status]" value="copy" />
                        Copy
                    </label>
                </td>
                <td>
                    <input form="form-item" class="form-control text-left" required name="berkas[${row}][keterangan]" type="text">
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <button type="button" onclick="removeBerkas(this)" data-toggle="tooltip" title="Hapus" class="btn btn-md btn-danger"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
            `);

            row++;
            setNomorBaris();
        }

        function removeBerkas(e) {
            $(e).parent().parent().remove();
            setNomorBaris();
        }

        setNomorBaris();
    </script>
@endsection
