@extends('layouts.document_layout')
@section('content')
<div class="container py-5">
    <div class="card shadow p-3 mb-5 bg-white rounded" id="btn-cetak">
        <div class="card-body">
            <div class="text-center">
                <p>KOPERASI SIMPAN PINJAM PARODANA-M</p>
                <P>LAPORAN ASURANSI BERJALAN</P>
                <p>Periode {{Carbon\Carbon::parse($request->start_date)->format('d F Y')}} - {{Carbon\Carbon::parse($request->end_date)->format('d F Y')}}</p>
            </div>
            <div class="table-responsive">
                <div class="text-end">
                    <a href="/" class="btn btn-sm btn-secondary ">Back</a>
                </div>
                <table class="table mt-5">
                <tr>
                    <th>NO</th>
                    <th>NO PINJAMAN</th>
                    <th>NAMA</th>
                    <th>PERUSAHAAN</th>
                    <th>JW</th>
                    <th>PLAFON</th>
                    <th>ASURANSI</th>
                    <th>PERSEN ASURANSI</th>
                    <th>IURAN ASURANSI</th>
                </tr>
                @foreach ($data as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->loan_number}}</td>
                    <td>{{$item->name_user}}</td>
                    <td>{{$item->company}}</td>
                    <td>{{$item->duration}}</td>
                    <td>Rp. {{number_format($item->approve_amount)}}</td>
                    <td>{{$item->no_kontrak}}</td>
                    <td>{{$item->insurance}} %</td>
                    <td>Rp. {{number_format(($item->insurance/100) * $item->approve_amount)}}</td>
                </tr>
                @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection