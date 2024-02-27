@extends('layouts.document_layout')
@section('content')
<div class="container py-5">
    <div class="card shadow p-3 mb-5 bg-white rounded" id="btn-cetak">
        <div class="card-body">
            <table class="" width="100%">
                <tr>
                    <td>
                    <td class="py-2" width="10%" style="height: 250px;"><img src="{{asset('img/logo/logo-small.png')}}" width="155" /></td>
                </td>
                <td class="py-2" style="line-height: 100%;">
                    <div class="" style="text-align:center;">
                        <b>
                            <p>KOPERASI SIMPAN PINJAM PARODAN-M</p>
                            <p>LAPORAN LABA/RUGI </p>
                        </b>
                    </div>
                </td>
                <td class="py-2" width="10%" style="height: 250px;"><img src="{{asset('img/logo/logo-small.png')}}" width="155" /></td>
                </tr>
            </table>
            <table width="100%">
            <hr>
                <tr style="background-color: #FEA1A1;">
                    <td>PENDAPATAN</td>
                </tr>
                @php 
                $pendapatan = 0;
                @endphp
                @foreach($data as $d)
                <tr>
                    <td>{{$d->account_name}}</td>
                    <td>Rp. {{number_format(($d->kredit), 0, ',', '.')}}</td>
                </tr>
                @php
                $pendapatan += $d->kredit;
                @endphp
                @endforeach
                
                <tr>
                        <td class="py-3"><b>TOTAL PENDAPATAN</b></td>
                        <td><b>Rp. {{number_format(($pendapatan), 0, ',', '.')}}</b></td>
                </tr>
                <tr style="background-color: #FEA1A1;">
                    <td>BEBAN OPERATIONAL</td>
                </tr>
                @php 
                $beban = 0;
                @endphp
                @foreach($dataBeban as $d)
                <tr>
                    <td>{{$d->account_name}}</td>
                    <td>Rp. {{number_format(($d->debit), 0, ',', '.')}}</td>
                </tr>
                @php
                $beban += $d->debit;
                @endphp
                @endforeach


                <tr>
                        <td class="py-3"><b>TOTAL BEBAN OPERATIONAL</b></td>
                        <td><u><b>Rp. {{number_format(($beban), 0, ',', '.')}}</b></u></td>
                </tr>
                @php 
                $laba = $pendapatan - $beban;
                @endphp
                <tr>
                        <td class="py-3"><b>LABA</b></td>
                        <td><b>Rp. {{number_format(($laba), 0, ',', '.')}}</b></td>
                </tr>
            </table>
            <div class="text-end">
                <a href="/" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection