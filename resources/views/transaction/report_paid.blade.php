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
                                <p>LAPORAN ANGSURAN PER {{Carbon\Carbon::parse(request()->input('date_trx'))->format('d F Y')}}</p>
                        </div>
                    </td>
                </tr>
            </table>

            <table width="100%" class="table-bordered text-center">
            <tr>
                <td rowspan="2" style="padding: 1.5em;">NO</td>
                <td rowspan="2">NO PINJAMAN</td>
                <td rowspan="2">NAMA</td>
                <td rowspan="2">NO TAB</td>
                <td rowspan="2">CARA BAYAR</td>
                <td rowspan="2">UANG MASUK</td>
                <td colspan="5">ANGSURAN</td>
                <td colspan="3">TEMPO</td>
                <td rowspan="2">TOTAL</td>
                <td rowspan="2">SISA</td>
            </tr>
            <tr>
                <td style="padding: 1.5em;">POKOK</td>
                <td>BUNGA</td>
                <td>TABUNGAN</td>
                <td>DENDA</td>
                <td>TOTAL</td>
                <td>BUNGA TEMPO</td>
                <td>POKOK TEMPO</td>
                <td>TOTAL TEMPO</td>
            </tr>
            @php
            $no = 1;
            $totalTransferin = 0;
            $totalPayPrincipal =0;
            $totalPayRate=0;
            $totalSaving=0;
            $totaltinstall=0;
            $totalttempo=0;
            $totalsisa=0;
            $totalBtempo=0;
            $totalPtempo=0;
            @endphp
            @foreach($paidData as $paid)
            <tr>
                <td style="padding: 1.5em;">{{$no++}}</td>
                <td>{{$paid->loan_number}}</td>
                <td>{{$paid->name}}</td>
                <td>{{$paid->member_number}}</td>
                <td>{{$paid->pay_method}}</td>
                <td>{{number_format($paid->transfer_in, 0, ',', '.')}}</td>
                <td>{{number_format($paid->pay_principal, 0, ',', '.')}}</td>
                <td>{{number_format($paid->pay_rates, 0, ',', '.')}}</td>
                <td>{{number_format($paid->saving, 0, ',', '.')}}</td>
                <td>0</td>
                <td>{{number_format($paid->t_installment, 0, ',', '.')}}</td>
                <td>{{number_format($paid->b_tempo, 0, ',', '.')}}</td>
                <td>{{number_format($paid->p_tempo, 0, ',', '.')}}</td>
                <td>{{number_format($paid->t_tempo, 0, ',', '.')}}</td>
                <td>{{number_format($paid->t_installment, 0, ',', '.')}}</td>
                <td>{{number_format($paid->sisa, 0, ',', '.')}}</td>
            </tr>
            @php
            $totalTransferin += $paid->transfer_in;
            $totalPayPrincipal += $paid->pay_principal;
            $totalPayRate += $paid->pay_rates;
            $totalSaving += $paid->saving;
            $totaltinstall += $paid->t_installment;
            $totalBtempo += $paid->b_tempo;
            $totalPtempo += $paid->p_tempo;
            $totalttempo += $paid->t_tempo;
            $totalsisa += $paid->sisa;
            @endphp
            @endforeach
            <tr>
                <td colspan="5">TOTAL</td>
                <td>{{number_format($totalTransferin, 0, ',', '.')}}</td>
                <td>{{number_format($totalPayPrincipal, 0, ',', '.')}}</td>
                <td>{{number_format($totalPayRate, 0, ',', '.')}}</td>
                <td>{{number_format($totalSaving, 0, ',', '.')}}</td>
                <td>0</td>
                <td>{{number_format($totaltinstall, 0, ',', '.')}}</td>
                <td>{{number_format($totalBtempo, 0, ',', '.')}}</td>
                <td>{{number_format($totalPtempo, 0, ',', '.')}}</td>
                <td>{{number_format($totalttempo, 0, ',', '.')}}</td>
                <td>{{number_format($totaltinstall, 0, ',', '.')}}</td>
                <td>{{number_format($totalsisa, 0, ',', '.')}}</td>
            </tr>
            </table>
            <div class="text-end py-3" id="action">
                    <a href="{{ request()->url() }}?jenis_laporan={{ request()->input('jenis_laporan') }}&date_trx={{ request()->input('date_trx') }}" class="btn btn-sm btn-primary">Back</a>
                    <a href="{{ request()->url() }}?jenis_laporan={{ request()->input('jenis_laporan') }}&date_trx={{ request()->input('date_trx') }}&page=unpaid" class="btn btn-sm btn-primary">Next</a>
                    <button onclick="Cetak()" class="btn btn-sm btn-primary">Cetak</button>
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