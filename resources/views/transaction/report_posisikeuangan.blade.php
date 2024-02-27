@extends('layouts.document_layout')
@section('content')
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
                            <p>LAPORAN POSISI KEUANGAN </p>
                        </b>
                    </div>
                </td>
                <td class="py-2" width="10%" style="height: 250px;"><img src="{{asset('img/logo/logo-small.png')}}" width="155" /></td>
                </tr>
            </table>
            <div class="row">
                <div class="col-md-6">
                    <p style="background-color: #FEA1A1;">AKTIVA</p>
                    <table width="100%">
                        @php 
                        $totalAktiva = 0;
                        $totalPasiva = 0;
                        $totalKredit = 0;
                        @endphp
                        <p><b>Aktiva Lancar</b></p>
                        @foreach($aktiva as $ak)
                        <tr>
                            @if($ak->debit != 0 )
                            <td>{{$ak->account_name}}</td>
                            <td>Rp. {{number_format($ak->debit, 0, ',', '.')}}</td>
                            @endif
                            @if($ak->kredit != 0)
                            <td>{{$ak->account_name}}</td>
                            <td>Rp. {{number_format($ak->kredit, 0, ',', '.')}}</td>
                            @endif
                        </tr>
                        @php
                        $totalAktiva += $ak->debit + $ak->kredit;     
                        @endphp
                        @endforeach
                        <tr style="background-color: #FEA1A1;">
                            <td><b>TOTAL AKTIVA</b></td>
                            <td><b>Rp. {{number_format($totalAktiva, 0, ',', '.')}}</b></td>
                        </tr>
                    </table>
                    <p></p>
                </div>
                <div class="col-md-6">
                    <p style="background-color: #FEA1A1;">PASIVA</p>
                    <table width="100%">
                        <tr style="background-color: #FEA1A1;">
                            <b>Kewajiban</b>
                            @foreach($hutang as $ht)
                            <tr>
                                @if($ht->kredit != 0 )
                                <td>{{$ht->account_name}}</td>
                                @if($ht->account_name == 'Modal')
                                <td>Rp. {{number_format($ht->kredit+$laba, 0, ',', '.')}}</td>
                                @else
                                <td>Rp. {{number_format($ht->kredit, 0, ',', '.')}}</td>
                                @endif
                                @endif
                            </tr>
                            @php

                            $totalPasiva += $ht->kredit;
                            @endphp
                            @endforeach
                        </tr>
                        <tr style="background-color: #FEA1A1;">
                            <td><b>TOTAL PASIVA</b></td>
                            <td><b>Rp. {{number_format(($totalPasiva + $laba), 0, ',', '.')}}</b></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="text-end py-3">
                <a href="/" class="btn btn-secondary">Back</a>
            </div>
    </div>
</div>
@endsection
@section('script')
@endsection