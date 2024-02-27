@extends('layouts.document_layout')
@section('content')
    <div class="card shadow p-3 mb-5 bg-white rounded" id="btn-cetak">
        <div class="card-body">
            <table class="" width="100%">
                <tr>
                    <td colspan="4" class="py-2" style="line-height: 100%;">
                        <div class="" style="text-align:center;">

                                <p>KOPERASI SIMPAN PINJAM PARODAN-M</p>
                                <p>BADAN HUKUM: </p>
                                <p>JL. Raya Serang - Jakarta Km 72, Ruko Sembilan kav 4</p>
                                <p>Kecamatan Kibin PT. Nikomas</p>
                                <p>Serang - Banten</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="py-5" style="line-height: 100%;">
                        <div class="" style="text-align:center;">
                             <p>PERIODE {{Carbon\Carbon::now()->firstOfMonth()->format('d F Y')}} - {{Carbon\Carbon::parse(request()->input('date_trx'))->format('d F Y')}}</p><p>
                        </div>
                    </td>
                </tr>
            </table>
            <table width="100%" class="table-bordered text-center">
            <tr>
                <td>NO</td>
                <td>NO PINJAMAN</td>
                <td>NAMA</td>
                <td>TGL PERSETUJUAN</td>
                <td>TENOR</td>
                <td>JUMLAH PINJAMAN</td>
            </tr>
            @php
            $no = 1;
            @endphp
            @foreach($cc as $c)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$c->loan_number}}</td>
                <td>{{$c->name}}</td>
                <td>{{$c->contract_date}}</td>
                <td>{{$c->time_period}}</td>
                <td>Rp. {{number_format($c->loan_amount)}}</td>
            </tr>
            @endforeach
            </table>
            <div class="text-end py-3" id="action">
                    <a href="/transaction/report/new-nasabah/index" class="btn btn-sm btn-primary">Close</a>
            </div>
        </div>
    </div>
@endsection