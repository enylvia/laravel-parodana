@extends('layouts.document_layout')
@section('content')
<div class="container py-5">
    <div class="card shadow p-3 mb-5 bg-white rounded" id="btn-cetak">
        <div class="card-body">
            <h4 class="text-center mb-5" style="font-weight: 500; font-size:16px;">Periode {{$start_date}} s/d {{$end_date}}</h4>
            <h5 class="text-center mb-5" style="font-weight: 600; font-size:20px;">LAPORAN BAKI</h5>
            <div class="table-responsive">
                <table class="table table-bordered" style="width: 100%;">
                    <thead>
                        <tr class="text-center">
                            <th rowspan="2" style="vertical-align: middle;">TANGGAL</th>
                            <th rowspan="2" style="vertical-align: middle;">BAKI</th>
                            <th colspan="5" style="vertical-align: middle;">ANGSURAN</th>
                            <th rowspan="2" style="vertical-align: middle;">PEMBERIAN KREDIT</th>
                            <th rowspan="2" style="vertical-align: middle;">BAKI AKHIR</th>
                        </tr>
                        <tr>
                            <th style="vertical-align: middle;" class="text-center">POKOK</th>
                            <th style="vertical-align: middle;" class="text-center">BUNGA</th>
                            <th style="vertical-align: middle;" class="text-center">TABUNGAN</th>
                            <th style="vertical-align: middle;" class="text-center">DENDA</th>
                            <th style="vertical-align: middle;" class="text-center">JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $ctr = 0;
                            $totalBaki = 0;
                            $totalPokok = 0;
                            $totalBunga = 0;
                            $totalTabungan = 0;
                            $totalDenda = 0;
                            $totalJumlah = 0;
                            $totalPK = 0;
                            $totalBakiAkhir= 0;
                        @endphp
                        @foreach ($groupedData as $dateTrx => $data)
                            @foreach ($data as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ Carbon\Carbon::parse($dateTrx)->format('d F Y') }}</td>
                                        @if ($ctr == 0)
                                            <td class="text-center">Rp. {{ number_format($item->start_balance ) }}</td>
                                        @else
                                            <td class="text-center">Rp. {{ number_format($item->end_balance + $item->kredit_pinjaman - $item->debit_pinjaman) }}</td>
                                        @endif
                                        <td class="text-center">Rp. {{number_format($item->kredit_pinjaman)}}</td>
                                        <td class="text-center">Rp. {{number_format($item->kredit_bunga)}}</td>
                                        <td class="text-center">Rp. {{number_format($item->kredit_tabungan)}}</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">Rp. {{number_format($item->kredit_pinjaman + $item->kredit_bunga + $item->kredit_tabungan)}}</td>
                                        <td class="text-center">Rp. {{number_format($item->debit_pinjaman)}}</td>
                                        @if ($ctr == 0)
                                            <td class="text-center">Rp. {{number_format($item->start_balance - $item->kredit_pinjaman + $item->debit_pinjaman)}}</td>
                                        @else
                                            <td class="text-center">Rp. {{ number_format((($item->end_balance + $item->kredit_pinjaman - $item->debit_pinjaman) - $item->kredit_pinjaman)+$item->debit_pinjaman) }}</td>
                                        @endif
                                    </tr>
                                    @php 
                                    if ($ctr == 0 ){
                                        $totalBaki += $item->start_balance;
                                    }else{
                                        $totalBaki += $item->end_balance + $item->kredit_pinjaman - $item->debit_pinjaman;
                                    }
                                        $totalPokok = $totalPokok + $item->kredit_pinjaman;
                                        $totalBunga = $totalBunga + $item->kredit_bunga;
                                        $totalTabungan = $totalTabungan + $item->kredit_tabungan;
                                        $totalDenda = $totalDenda + 0;
                                        $totalJumlah = $totalJumlah + $item->kredit_pinjaman + $item->kredit_bunga + $item->kredit_tabungan;
                                        $totalPK = $totalPK + $item->debit_pinjaman;
                                    if ($ctr == 0 ){
                                        $totalBakiAkhir += $item->start_balance - $item->kredit_pinjaman + $item->debit_pinjaman;
                                    }else{
                                        $totalBakiAkhir += (($item->end_balance + $item->kredit_pinjaman - $item->debit_pinjaman) - $item->kredit_pinjaman)+$item->debit_pinjaman;
                                    }
                                    $ctr++;
                                    @endphp
                            @endforeach
                        @endforeach
                        <tr class="bg-danger text-center">
                            <td>JUMLAH</td>
                            <td>Rp. {{number_format($totalBaki)}}</td>
                            <td>Rp. {{number_format($totalPokok)}}</td>
                            <td>Rp. {{number_format($totalBunga)}}</td>
                            <td>Rp. {{number_format($totalTabungan)}}</td>
                            <td>Rp. {{number_format($totalDenda)}}</td>
                            <td>Rp. {{number_format($totalJumlah)}}</td>
                            <td>Rp. {{number_format($totalPK)}}</td>
                            <td>Rp. {{number_format($totalBakiAkhir)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
