@extends('layouts.document_layout')
@section('content')
<div class="container py-5">
    <div class="card shadow p-3 mb-5 bg-white rounded" id="btn-cetak">
        <div class="card-body">
            <table class="" width="100%">
                <tr>
                    <td class="py-2" width="20%" style="height: 250px;"><img src="{{asset('img/logo/logo-small.png')}}" width="155" /></td>
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
                <tr>
                    <td></td>
                    <td>
                        <div class="d-flex justyfy-content-center py-5" style="text-align:center;">
                        <div>

                            <p>ASURANSI KREDIT PINJAMAN</p>
                            <p>ASURANSI PARODANA-M</p>
                        </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>No Kontak</td>
                    <td>: {{$ci->no_kontrak}}</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>: {{$ci->name_user}}</td>
                </tr>
                <tr>
                    <td>Perusahaan</td>
                    <td>: {{$ci->company}}</td>
                </tr>
                <tr>
                    <td>Jangka Waktu</td>
                    <td>: {{$ci->duration}} Bulan </td>
                </tr>
                <tr>
                    <td>Besar Pinjaman</td>
                    <td>: Rp. {{ number_format($approveCustomer->approve_amount, 0, ',' , '.') }}</td>
                </tr>
                <tr>
                    <td>Pertanggungan</td>
                    <td>: Rp. {{ number_format($approveCustomer->approve_amount * ($cc->insurance/100), 0, ',' , '.') }}</td>
                </tr>
                <tr>
                    <td>Habis Kontrak</td>
                    <td>: {{$ci->duration}} Bulan kedepan setelah akad kredit</td>
                </tr>
                <tr>
                    <td>Persyaratan : </td>
                </tr>
                <tr>
                    <td style="width: 40%;">
                        <ul>
                            <li>Membawa surat kematian 1 lembar (ASLI)</li>
                            <li>Surat keterangan meninggal dari pemerintah setempat/surat penguburan</li>
                            <li>Photocopy KK dan KTP</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-end">Serang - Banten, {{date_format(now(),"d-m-Y")}}</td>
                </tr>
            </table>
            <table class="" style="width: 100%; line-height:100%;">
                <tr>
                    <td class="text-end">
                        <!-- blankspace -->
                        &nbsp;
                        <p>{{auth()->user()->name}}</p>
                        <p>(ASURANSI PARODANA-M)</p>
                    </td>
                </tr>
            </table>
            <div class="text-end py-3" id="action">
                <td style="width:30% ;" class="text-end">
                    <a href="/customer/contract/detail/{{$customer->id}}/?page=3" class="btn btn-sm btn-primary">Back</a>
                    <button onclick="Cetak()" class="btn btn-sm btn-primary">Cetak</button>
                    <a href="/customer/contract/detail/{{$customer->id}}/?page=5" class="btn btn-sm btn-primary">Next</a>
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