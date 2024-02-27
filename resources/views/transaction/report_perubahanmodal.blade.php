@extends('layouts.document_layout')
@section('content')
    <div class="container py-5">
        <div class="card shadow p-3 mb-5 bg-white rounded" id="btn-cetak">
            <div class="card-body">
                <table class="" width="100%">
                    <tr>
                        <td>
                        <td class="py-2" width="10%" style="height: 250px;"><img
                                src="{{ asset('img/logo/logo-small.png') }}" width="155" /></td>
                        </td>
                        <td class="py-2" style="line-height: 100%;">
                            <div class="" style="text-align:center;">
                                <b>
                                    <p>KOPERASI SIMPAN PINJAM PARODAN-M</p>
                                    <p>LAPORAN PERUBAHAN MODAL </p>
                                </b>
                            </div>
                        </td>
                        <td class="py-2" width="10%" style="height: 250px;"><img
                                src="{{ asset('img/logo/logo-small.png') }}" width="155" /></td>
                    </tr>
                </table>
                <table width="50%">
                    <hr>
                    <tr>
                        <td>MODAL AWAL</td>
                    </tr>
                    @php
                        $total_modal = 0;
                        $modal_akhir = 0;
                    @endphp
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $d->account_name }}</td>
                            @if ($d->kredit == 0)
                                <td class="text-end">-</td>
                            @else
                                <td class="text-end">Rp. {{ number_format($d->kredit, 0, ',', '.') }}</td>
                            @endif
                        </tr>

                        @php
                            $total_modal += $d->kredit;
                            $modal_akhir = $total_modal + $laba;
                        @endphp
                    @endforeach

                    <tr>
                        <td class="py-3"><b>TOTAL MODAL</b></td>
                        <td class="text-end"><b>Rp. {{ number_format($total_modal, 0, ',', '.') }}</b></td>
                    </tr>
                    <tr>
                        <td class="py-3"><b>LABA</b></td>
                        <td class="text-end"><b>Rp. {{ number_format($laba, 0, ',', '.') }}</b></td>
                    </tr>
                    <tr style="background-color: #FEA1A1;">
                        <td><b>MODAL AKHIR</b></td>
                        <td class="text-end"><b>Rp. {{ number_format($modal_akhir, 0, ',', '.') }}</b></td>
                    </tr>

                    <tr class="py-3">
                        <td class="text-end">Rp. {{ number_format($modal + $laba, 0, ',', '.') }}</td>
                    </tr>
                </table>
                <div class="text-end py-3">
                    <a href="/" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
