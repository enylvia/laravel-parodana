@extends('layouts.document_layout')
@section('content')
<div class="container py-5">

    <div class="card shadow p-3 mb-5 bg-white rounded" id="btn-cetak">
        <div class="card-body">
            <table>
                <tr>
                    <td class="py-3">
                        <b>A. DATA PRIBADI</b>
                    </td>
                </tr>
                <tr>
                <td>Nama Lengkap Sesuai KTP : {{$customer->name}} </td>
                <td>Nama Panggilan : </td>
            </tr>
            <tr>
                <td>Tempat/Tgl Lahir : {{$customer->birth_place}}, {{$customer->date_of_birth}}</td>
                <td>No KTP : {{$customer->card_number}} </td>
            </tr>
            <tr>
                <td>Nama Suami/Istri Sesuai KTP : {{$customer->husband_wife}} </td>
                <td>Nama Panggilan : {{$customer->alias_husband_wife}}</td>
            </tr>
            <tr>
                <td>Tempat/Tgl Lahir : {{$customer->family_address}} </td>
                <td>No KTP : {{$customer->family_card_number}}</td>
            </tr>
            <tr>
                <td>No Telepon : {{$customer->mobile_phone}} </td>
                <td>Alamat Sekarang : {{$customer->address}} </td>
            </tr>
            <tr>
                @if($customer->maritial == 2)
                <td>Status Pernikahan : Tidak Menikah </td>
                @else
                <td>Status Pernikahan : Menikah </td>
                @endif
                <td>Status Rumah : {{$customer->husband_wife_home_status}} </td>
            </tr>
            <tr>
                <td class="py-3"><p>Dalam keadaan darurat siapa yang perlu kami hubungi selain keluarga serumah?</p></td>
            </tr>
            <tr>
                <td>Nama : {{$customer->connection_name}} </td>
            </tr>
            <tr>
                <td>Alamat : {{$customer->connection_address}} </td>
            </tr>
            <tr>
                <td>No Telepon : {{$customer->connection_address}} </td>
            </tr>
            <tr>
                <td>Saudara : {{$customer->family_connection}}</td>
            </tr>
            <tr>
                <td class="py-3"><b>B. PEKERJAAN</b></td>
            </tr>
            <tr>
                <td>Pekerjaan : {{$customer->part}} </td>
                <td>Nama Perusahaan : {{$customer->company_name}} </td>
            </tr>
            <tr>
                <td>NIK : {{$customer->kpk_number}} </td>
                <td>Telepon Perusahaan : </td>
            </tr>
            <tr>
                <td>Department : {{$customer->department}} </td>
                <td>Nama HRD : {{$customer->personalia_name}} </td>
            </tr>
            <tr>
                <td class="py-3"><b>C. PENGHASILAN</b></td>
            </tr>
            <tr>
                <td>Gaji Kotor : {{$customer->gross_salary}} </td>
                <td>Bank Payroll : {{$customer->bank_name}} </td>
            </tr>
            <tr>
                <td>Penghasilan : {{$customer->net_salary}}  </td>
                <td>No Rek Tabungan : {{$customer->bank_number}}</td>
            </tr>
            <tr>
                <td>Penghasilan Istri/Suami : {{$customer->husband_wife_income}} </td>
                <td>No PIN Tabungan : {{$customer->bank_pin}}</td>
            </tr>

            {{-- data keluarga --}}
            <tr>
                <td class="py-3"><b>D. DATA KELUARGA</b></td>
            </tr>
            <tr>
                <td>Nama Orang Tua : {{$customer->family_father}} (Bapak) {{$customer->family_mother}} (Ibu) </td>
            </tr>
            <tr>
                <td>Alamat : {{$customer->family_address}} </td>
            </tr>
            <tr>
                <td>No. Telpon :   </td>
            </tr>
            <tr>
                <td>Nama Mertua : {{$customer->in_law_father}} (Bapak) {{$customer->in_law_mother}} (Ibu)  </td>
            </tr>
            <tr>
                <td>Alamat : {{$customer->in_law_address}} </td>
            </tr>
            <tr>
                <td>No. Telpon : {{$customer->in_law_phone}}  </td>
            </tr>
        </table>
        <div class="text-end py-3" id="action">
                <td style="width:30% ;" class="text-end">
                <a href="/customer/contract/detail/{{$customer->id}}/?page=" class="btn btn-sm btn-primary">Back</a>
					<button onclick="Cetak()" class="btn btn-sm btn-primary">Cetak</button>
					<a href="/customer/contract/detail/{{$customer->id}}/?page=2" class="btn btn-sm btn-primary">Next</a>
				</td>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script>
    function Cetak(){
        const td = document.getElementById('action');
		var prtContent = document.getElementById("btn-cetak");
        prtContent.className = "card p-3 mb-5 bg-white rounded";
        td.style.display = 'none';
        window.print();
        td.style.display = 'block';
        prtContent.className = "card shadow p-3 mb-5 bg-white rounded";

	}
</script>
@endsection