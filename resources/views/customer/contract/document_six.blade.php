@extends('layouts.document_layout')
@section('content')
<div class="container py-5">
    <div class="card shadow p-3 mb-5 bg-white rounded" id="btn-cetak">
        <div class="card-body">
            <table class="" width="100%">
                <tr>
                    <td class="py-2" width="35%" style="height: 250px;"><img src="{{asset('img/logo/logo-small.png')}}" width="155" /></td>
                    <td class="py-2" style="line-height: 100%;">
                        <div class="d-flex jutify-content-left" style="text-align:center;">
                            <b>

                                <p>KOPERASI SIMPAN PINJAM PARODAN-M</p>
                                <p>BADAN HUKUM: </p>
                                <p>JL. Raya Serang - Jakarta Km 72, Ruko Sembilan kav 4</p>
                                <p>Kecamatan Kibin PT. Nikomas</p>
                                <p>Serang - Banten</p>
                            </b>
                        </div>
                    </td>
                </tr>
                @php
                if ($saving == null) {
                    $saving = 0;
                    } else {
                        $saving = $saving->amount;
                    }
                $total = $approveCustomer->approve_amount - ($approveCustomer->approve_amount * ($cc->provision/100)) - $saving - ($approveCustomer->approve_amount * ($cc->insurance/100)) - $cc->stamp;
                @endphp
                <tr>
                    <td>Pinjaman</td>
                    <td>: Rp. {{ number_format($approveCustomer->approve_amount, 0, ',' , '.') }} </td>
                </tr>
                <tr>
                    <td>Provisi</td>
                    <td>: Rp. {{number_format($approveCustomer->approve_amount * ($cc->provision/100), 0, ',' , '.')}} </td>
                </tr>
                <tr>
                    <td>Tabungan</td>
                    <td>: Rp. {{number_format($saving)}} </td>
                </tr>
                <tr>
                    <td>Asuransi</td>
                    <td>: Rp. {{number_format($approveCustomer->approve_amount * ($cc->insurance/100), 0, ',' , '.')}}</td>
                </tr>
                <tr>
                    <td>Materai (3)</td>
                    <td>: Rp. {{number_format($cc->stamp, 0, ',' , '.')}}</td>
                </tr>
                <tr>
                    <td>Take Over Pelunasan</td>
                    <td>: Rp. </td>
                </tr>
                <tr>
                    <td></td>
                    <td><hr style="width: 50%;"></td>
                </tr>
                <tr>
                    <td>Terima Bersih</td>
                    <td>: Rp. {{number_format($total, 0, ',' , '.')}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="" style="height: 150px ;">Serang - Banten, {{date_format(now(),"d-m-Y")}}</td>
                </tr>
            </table>
            <table class="" style="width: 100%; line-height:100%;">
                <tr>
                    <td width="50%" class="text-center">
                        <p>({{$customer->name}})</p>
                    </td>
                    <td class="text-center">
                        <p></p>
                        <p>({{auth()->user()->name}})</p>
                    </td>
                </tr>
            </table>
            <div class="text-end py-3" id="action">
                <td style="width:30% ;" class="text-end">
                    <a href="{{route('customer')}}" class="btn btn-sm btn-primary">Close</a>
                    <a href="/customer/contract/detail/{{$customer->id}}/?page=5" class="btn btn-sm btn-primary">Back</a>
                    <button onclick="Cetak()" class="btn btn-sm btn-primary">Cetak</button>
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