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
                             <p>{{Carbon\Carbon::now()->firstOfMonth()->format('d F Y')}} - {{Carbon\Carbon::parse(request()->input('date_trx'))->format('d F Y')}}</p><p>
                        </div>
                    </td>
                </tr>
            </table>

            <table width="100%" class="table-bordered text-center">
            <tr>
                <td rowspan="2" style="padding: 1.5em;">NO</td>
                <td rowspan="2">NO ANGGOTA</td>
                <td rowspan="2">NAMA ANGGOTA</td>
                <td colspan="4">TUNGGAKAN</td>
                <td rowspan="2">TOTAL</td>
            </tr>
            <tr>
                <td style="padding: 1.5em;">POKOK</td>
                <td>BUNGA</td>
                <td>TABUNGAN</td>
                <td>TEMPO</td>
            </tr>
            @php
            $no = 1;
            $total = 0;
            @endphp
            @foreach($unpaidData as $paid)
            @php 
            $total = $paid->pay_principal+$paid->pay_rates+$paid->saving+$paid->t_tempo;
            @endphp
            <tr>
                <td style="padding: 1.5em;">{{$no++}}</td>
                <td>{{$paid->member_number}}</td>
                <td>{{$paid->name}}</td>
                <td>{{number_format($paid->pay_principal, 0, ',', '.')}}</td>
                <td>{{number_format($paid->pay_rates, 0, ',', '.')}}</td>
                <td>{{number_format($paid->saving, 0, ',', '.')}}</td>
                <td>{{number_format($paid->t_tempo, 0, ',', '.')}}</td>
                <td>{{number_format($total, 0, ',', '.')}}</td>
            </tr>
            @endforeach
            </table>
            <div class="text-end py-3" id="action">
                    <a href="/transaction/report/running/index" class="btn btn-sm btn-primary">Close</a>
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