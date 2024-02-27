@extends('layouts.document_layout')
@section('content')
{{-- @dd($approveCustomer) --}}
{{-- @dd($memberNumber) --}}
{{-- @dd($approveCustomer) --}}
    <div class="container py-5">
        <div class="card shadow p-3 mb-5 bg-white rounded" id="btn-cetak">
            <div class="card-body">
                <b>E. JAMINAN</b>
                <p>Jenis barang yang saya jaminkan secara kepercayaan, sbb :</p>
                <table style="width:100%;" class="table-bordered text-center">
                    <tr>
                        <td>No.</td>
                        <td>Nama Barang</td>
                        <td>Merek</td>
                        <td>Nilai Sekarang</td>
                        <td>Kondisi Barang</td>
                    </tr>
                </table>

                <b>F. DENAH TEMPAT TINGGAL</b>
                <div class="" style="border: 1px solid black; height:300px;">

                </div>
                <p>Saya menyatakan bahwa semua informasi yang diberikan untuk tujuan permohonan kredit dan dengan ini saya
                    mengijinkan koperasi untuk menelaah dan mememeriksa informasi yang diperlukan.</p>
                <br>
                <br>
                <table style="width: 100%;">
                    <tr>
                        <td class="text-center">
                            <p>Calon Kreditur</p>
                            <br>
                            <br>
                            <br>
                            <p>({{ $customer->name }})</p>
                        </td>
                        <td class="text-center">
                            <p>Penanggung Jawab</p>
                            <br>
                            <br>
                            <br>
                            <p>(...............)</p>
                        </td>
                        <td class="text-center">
                            <p>Surveyor</p>
                            <br>
                            <br>
                            <br>
                            <p>(................)</p>
                        </td>
                    </tr>
                    <tr style="font-size: 10px;">
                        <td class="d-flex justify-content-start">Menyetujui: </td>
                        <td style="line-height: 100%;">
                            <p>Hari, Tanggal : {{\Illuminate\Support\Carbon::parse($customer->created_at)->translatedFormat('l, j F Y')}}</p>
                            {{-- <p>ACC : , Fiat : , Bunga : </p> --}}
                            <p>ACC : Rp. {{number_format($approveCustomer->approve_amount, 2, ",", ".")}}, Fiat : {{$approveCustomer->time_period}} Bulan, Bunga : {{$approveCustomer->interest_rate}}%</p>
                            {{-- <p>ANG : Hutang + Tabungan </p> --}}
                            <p>ANG : Rp. {{(number_format($memberNumber->pay_principal + $memberNumber->pay_interest))}} + Tabungan Rp. {{number_format($approveCustomer->m_savings)}} </p>
                            <p>Total Angsuran : Rp. {{number_format($memberNumber->pay_month, 2, ",", ".")}}</p>
                        </td>
                    </tr>
                </table>
                <div class="text-end py-3" id="action">
                    <td style="width:30% ;" class="text-end">
                        <a href="/customer/contract/detail/{{ $customer->id }}/?page=1"
                            class="btn btn-sm btn-primary">Back</a>
                        <button onclick="Cetak()" class="btn btn-sm btn-primary">Cetak</button>
                        <a href="/customer/contract/detail/{{ $customer->id }}/?page=3"
                            class="btn btn-sm btn-primary">Next</a>
                    </td>
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
