@extends('layouts.document_layout')
@section('content')

<div class="container mt-5">
    <div class="text-center">
        <p style="margin-bottom: 3px;">
        Koperasi Simpan Pinjam PARODANA-M
        </p>
       <p style="margin-bottom: 3px;">Jl. Raya Serang - Jakarta km 72 ,Ruko Sembilan kav 4 </p>
       <p style="margin-bottom: 3px;">Kecamatan Kibin PT. Nikomas</p>
       <p style="margin-bottom: 3px;">Serang - Banten</p>
    </div>
    <hr>
    <table style="width: 100%;" >
    <tr>
        <td>
            <a href="/" class="btn btn-sm btn-secondary">Back</a>
        </td>
        <td>
            <div class="d-flex align-items-center justify-content-end mt-5">
                <label for="nama" style="margin-right:20px; font-size:16px;">Nama:</label> 
                <select name="nama" id="namaNasabah" class="form-control" style="width: 50%;">
                    @foreach($loans as $item)
                    <option value="{{$item->loan_number}}">({{$item->loan_number}}) - {{$item->name}}</option>
                    @endforeach
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td class="pt-5">
            <div class="d-flex align-items-center">
                <p style="font-size:16px;">Angsuran :</p>
                <p id="angsuran" style="font-size:16px;"></p>
            </div>
        </td>
        <td class="pt-5">
            <div class="d-flex align-items-center justify-content-end">
                <p style="font-size:16px;">No Tabungan : </p>
                <p id="noTabungan" style="font-size:16px;"></p>
            </div>
        </td>
    </tr>
    <tr>
        <td class="pt-5">
            <div class="d-flex align-items-center">
                <p style="font-size:16px;">Besar Pinjaman : </p>
                <p id="besarPinjaman" style="font-size:16px;"> </p>
            </div>
        </td>
        <td class="pt-5">
            <div class="d-flex align-items-center justify-content-end">
                <p style="font-size:16px;">Perusahaan : </p>
                <p id="perusahaan" style="font-size:16px;"> </p>
            </div>
        </td>
    </tr>
    </table>

    <div id="table-list">

	</div>
</div>
</div>
@endsection