@extends('layouts.document_layout')
@section('content')
<div class="container py-5">
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
                                <p>LAPORAN HARIAN PER {{Carbon\Carbon::parse(request()->input('date_trx'))->format('d F Y')}}</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p>DATA KAS AWAL</p>
                    </td>
                    <td>Rp</td>
                    <td style="text-align:right;">
                        <p>{{number_format($kas->end_balance, 0, ',', '.')}}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <p>UANG FISIK</p>
                    </td>
                </tr>
                <tr>
                    <td class="py-2" colspan="2" style="text-align: center;">
                    100000
                    </td>
                    <td>Rp</td>
                    <td style="text-align: right;">0</td>
                </tr>
                <tr>
                    <td class="py-2" colspan="2" style="text-align: center;">
                    50000
                    </td>
                    <td>Rp</td>
                    <td style="text-align: right;">0</td>
                </tr>
                <tr>
                    <td class="py-2" colspan="2" style="text-align: center;">
                    20000
                    </td>
                    <td>Rp</td>
                    <td style="text-align: right;">0</td>
                </tr>
                <tr>
                    <td class="py-2" colspan="2" style="text-align: center;">
                    10000
                    </td>
                    <td>Rp</td>
                    <td style="text-align: right;">0</td>
                </tr>
                <tr>
                    <td class="py-2" colspan="2" style="text-align: center;">
                    5000
                    </td>
                    <td>Rp</td>
                    <td style="text-align: right;">0</td>
                </tr>
                <tr>
                    <td class="py-2" colspan="2" style="text-align: center;">
                    2000
                    </td>
                    <td>Rp</td>
                    <td style="text-align: right;">0</td>
                </tr>
                <tr>
                    <td class="py-2" colspan="2" style="text-align: center;">
                    1000
                    </td>
                    <td>Rp</td>
                    <td style="text-align: right;">0</td>
                </tr>
                <tr>
                    <td class="py-2" colspan="2" style="text-align: center;">
                    500
                    </td>
                    <td>Rp</td>
                    <td style="text-align: right;">0</td>
                </tr>
                <tr>
                    <td class="py-2" colspan="2" style="text-align: center;">
                    200
                    </td>
                    <td>Rp</td>
                    <td style="text-align: right;">0</td>
                </tr>
                <tr>
                    <td class="py-2" colspan="2" style="text-align: center;">
                    100
                    </td>
                    <td>Rp</td>
                    <td style="text-align: right;">0</td>
                </tr>
                <tr>
                    <td class="py-3">DEBET</td>
                </tr>
                @php
                    $totalDebit = 0;
                    $totalKredit = 0;
                @endphp
                @foreach ($data as $item)
                        @if($item->status == 'd')
                            <tr>
                                <td colspan="2" class="py-2" style="text-align: center;">{{$item->account_type}}</td>
                                <td>Rp</td>
                                <td style="text-align: right;">{{number_format($item->debit, 0, ',', '.')}}</td>
                            </tr>
                            @php
                                $totalDebit += $item->debit;
                            @endphp
                        @endif
                @endforeach
                    <tr>
                        <td colspan="2" style="text-align: center;">Total Debit:</td>
                        <td>Rp</td>
                        <td style="text-align: right;">{{number_format($totalDebit, 0, ',', '.')}}</td>
                    </tr>
                <tr>
                    <td class="py-3">KREDIT</td>
                </tr>
                @foreach ($data as $item)
                    @if($item->status == 'k')
                    <tr>
                        <td colspan="2" class="py-2" style="text-align: center;">{{$item->account_type}}</td>
                        <td>Rp</td>
                        <td style="text-align: right;">{{number_format($item->kredit, 0, ',', '.')}}</td>
                    </tr>
                    @php
                   
                                $totalKredit += $item->kredit;
                    @endphp
                    @endif
                @endforeach
                <tr>
                    <td colspan="2" class="py-2" style="text-align:center">Total Kredit:</td>
                    <td>Rp</td>
                    <td style="text-align: right;">{{number_format($totalKredit, 0, ',', '.')}}</td>
                </tr>
                <tr>
                    <td class="py-2" colspan="2">DATA KAS AKHIR</td>
                    <td>Rp</td>
                    <td style="text-align: right;">{{number_format(0, 0, ',', '.')}}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right" class="py-5">Serang, Banten {{Carbon\Carbon::now()->format('d M Y')}}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;" class="py-5">(................)</td>
                    <td>(................)</td>
                    <td>(................)</td>
                </tr>
            </table>
            <div class="text-end py-3" id="action">
                    <a href="/?page=1" class="btn btn-sm btn-primary">Back</a>
                    <a href="{{ request()->url() }}?jenis_laporan={{ request()->input('jenis_laporan') }}&date_trx={{ request()->input('date_trx') }}&page=paid" class="btn btn-sm btn-primary">Next</a>
                    <button onclick="Cetak()" class="btn btn-sm btn-primary">Cetak</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    function Cetak() {
        const td = document.getElementById('action');
        var prtContent = document.getElementById("btn-cetak");
        prtContent.className = "p-3 mb-5 bg-white rounded";
        td.style.display = 'none';
        window.print();
        td.style.display = 'block';
        prtContent.className = "card shadow p-3 mb-5 bg-white rounded";
    }
</script>
@endsection