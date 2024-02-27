@extends('layouts.document_layout')
@section('content')
<div class="container py-5">
    <div class="card shadow p-3 mb-5 bg-white rounded" id="btn-cetak">
        <div class="card-body">
            <table class="table text-center" width="100%">
                <tr>
                    <td class="py-2" width="20%" style="height: 250px;"><img src="{{asset('img/logo/logo-small.png')}}" width="155" /></td>
                    <td class="py-2" style="line-height: 100%;">
                        <div class="" style="text-align:center;">
                            <b>

                                <p>KOPERASI SIMPAN PINJAM PARODAN-M</p>
                                <p>BADAN HUKUM: </p>
                                <p>JL. Raya Serang - Jakarta Km 72, Ruko Sembilan kav 4</p>
                                <p>Kecamatan Kibin PT. Nikomas</p>
                                <p>Serang - Banten</p>
                            </b>
                            <hr>
                        </div>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered">
                <tr>
                    <td>Nama</td>
                    <td>: {{$customer->name}}</td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>: {{$customer->company_name}}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>: {{$customer->address}}</td>
                </tr>
                <tr>
                    <td>No.Telp</td>
                    <td>: {{$customer->mobile_phone}}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: {{now()->format('d-M-Y')}}</td>
                </tr>
            </table>
            <table class="table table-bordered">
                <tr class="text-center">
                   <td>No</td> 
                   <td>Berkas</td> 
                   <td colspan="2">Status</td> 
                   <td>Keterangan</td>  
                </tr>
                <tr class="text-center">
                    <td></td>
                    <td></td>
                    <td>Asli</td>
                    <td>Fotokopi</td>
                    <td></td> 
                </tr>
                <!-- looping disini -->
                @php 
                    $no = 1;
                @endphp
                @foreach ($document as $item)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$item->berkas}}</td>
                    @if ($item->status == "copy")
                    <td class="text-center"></td>
                    <td class="text-center">Ya</td>
                    @else
                    <td class="text-center">Ya</td>
                    <td class="text-center"></td>
                    @endif
                    <td>{{$item->keterangan}}</td>
                </tr>
                @endforeach
            </table>
            <table class="table table-bordered">
                <tr class="text-center">
                    <td colspan="4"><b>SERAH TERIMA BERKAS</b></td>
                </tr>
                <tr class="text-center">
                    <td colspan="2"><b>ANGGOTA</b></td>
                    <td><b>ADM</b></td>
                    <td colspan="2"><b>PIMPINAN</b></td>
                </td>
                </tr>
                <tr>
                    <td colspan="2" style="height: 75px;"></td>
                    <td style="height: 75px;"></td>
                    <td colspan="2" style="height: 75px;"></td>
                </tr>
                <tr class="text-center">
                    <td colspan="2">{{$customer->name}}</td>
                    <td></td>
                    <td colspan="2">....................</td>
                </tr>
            </table>
            <div class="text-end py-3" id="action">
                <td style="width:30% ;" class="text-end">
                    <a href="/customer/contract/detail/{{$customer->id}}/?page=4" class="btn btn-sm btn-primary">Back</a>
                    <button onclick="Cetak()" class="btn btn-sm btn-primary">Cetak</button>
                    <a href="/customer/contract/detail/{{$customer->id}}/?page=6" class="btn btn-sm btn-primary">Next</a>
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