@extends('layouts.document_layout')
@section('content')
    <div class="container py-5">
        <div class="card shadow p-3 mb-5 bg-white rounded" id="btn-cetak">
            <div class="card-body">
                <table class="" width="100%">
                    <tr>
                        <td class="py-2" width="20%" style="height: 250px;"><img
                                src="{{ asset('img/logo/logo-small.png') }}" width="155" /></td>
                        <td class="py-2 text-center" style="line-height: 100%;">
                            <b>

                                <p>KOPERASI SIMPAN PINJAM PARODAN-M</p>
                                <p>BADAN HUKUM: </p>
                                <p>JL. Raya Serang - Jakarta Km 72, Ruko Sembilan kav 4</p>
                                <p>Kecamatan Kibin PT. Nikomas</p>
                                <p>Serang - Banten</p>
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-center"><b>SURAT PERJANJIAN</b></td>
                    </tr>
                    <tr>
                        <td>1. Yang Bertandatangan di bawah ini :</td>
                    </tr>
                    <tr>
                        <td style="text-indent: 32px;">Nama</td>
                        <td>: {{ $customer->name }}</td>
                    </tr>
                    <tr>
                        <td style="text-indent: 32px;">Alamat Rumah</td>
                        <td>: {{ $customer->address }}</td>
                    </tr>
                    <tr>
                        <td style="text-indent: 32px;">Kantor</td>
                        <td>: NIKOMAS GEMILANG</td>
                    </tr>
                    <tr>
                        <td style="text-indent: 32px;">Pekerjaan</td>
                        <td>: {{ $customer->part }}</td>
                    </tr>
                    <tr>
                        <td style="text-indent: 32px;">No Rekening</td>
                        <td>: {{ $customer->bank_number }}</td>
                    </tr>
                    <tr>
                        <td style="text-indent: 32px;">No Telepon</td>
                        <td>: {{ $customer->mobile_phone }} </td>
                    </tr>

                </table>
                <table width="100%">
                    <tr>
                        <td>
                            <p style="text-align: justify;">2. Pimpinan Koperasi Simpan Pinjam PARODANA-M dalam hal tersebut
                                untuk dan atas nama Koperasi Simpan Pinjam PARODANA-M JL. Raya Serang - Jakarta km 72, Ruko
                                Sembilan kav 4 Kecamatan Kibin. PT. Nikomas Serang - Banten selanjutnya disebut dengan PIHAK
                                KE-II (DUA)(KOPERASI)</p>
                            <p>KEDUA BELAH PIHAK MENGADAKAN PERJANJIAN SEBAGAI BERIKUT: </p>

                        </td>
                    </tr>
                </table>
                <table width="100%">
                    @php
                        if ($saving == null) {
                            $saving = 0;
                        } else {
                            $saving = $saving->amount;
                        }
                    @endphp
                    <tr>
                        <td style="text-align:justify; width:3%;" class="text-center">1. </td>
                        <td style="text-align:justify;">PIHAK KE-I (SATU) Mengaku telah pinjam uang dari PIHAK KE-II (DUA)
                            sebesar Rp. {{ number_format($approveCustomer->approve_amount, 0, ',', '.') }} dengan jasa atau
                            suku bunga {{ $cc->provision }} % per bulan flat (secara terus menerus)</td>
                    </tr>
                    <tr>
                        <td style="text-align:justify;" class="text-center">2. </td>
                        <td style="text-align:justify;">PIHAK KE-I (SATU) menyetujui potongan tabungan Rp.
                            {{ number_format($saving) }} dan biaya tata laksana 3% dari jumlah pinjaman yang diberikan.
                            Sebagai Jaminan yang diberikan PIHAK KE-I (SATU) menyerahkan</td>
                    </tr>
                    <tr>
                        <td style="text-align:justify;" class="text-center">3. </td>
                        <td style="text-align:justify;">PIHAK KE-I (SATU) menyetujui penggesekan ATM atau pengambilan gaji
                            melalui ATM penggajian dan dipotong sesuai angsuran dengan no rekening
                            {{ $customer->bank_number }} A/N {{ $customer->bank_name }}. Apabila ada pergantian ATM dan
                            BUKU TABUNGAN penggajian dari pihak perusahaan maka PIHAK KE-I (SATU) bersedia menyerahkan ATM
                            dan BUKU TABUNGAN tersebut kpeada PIHAK KE-II (DUA).</td>
                    </tr>
                    <tr>
                        <td style="text-align:justify;" class="text-center">4. </td>
                        <td style="text-align:justify;">Untuk pembayaran pinjaman ini, PIHAK KE-I (SATU) bersedia dipotong
                            gaji oleh PIHAK KE-II (DUA) melalui transferan gaji PIHAK KE-I (SATU) sebesar
                            {{ $memberNumber->pay_month }} x {{ $approveCustomer->time_period }} Bulan,</td>
                    </tr>
                    <tr>
                        <td style="text-align:justify;" class="text-center">5. </td>
                        <td style="text-align:justify;">Apabila karena hal, pembayaran gaji PIHAK KE-I (SATU) diberhentikan
                            atau PHK baik karena sebab lain, maka PIHAK KE-I (SATU) wajib melunasi pinjaman (termasuk
                            denda),</td>
                    </tr>
                    <tr>
                        <td style="text-align:justify;" class="text-center">6. </td>
                        <td style="text-align:justify;">Apabila PIHAK KE-I (SATU) mengajukan akad kredit kepada pihak lain
                            maka PIHAK KE-I (SATU) wajib melunasi pinjaman kepada PIHAK KE-II (DUA),</td>
                    </tr>
                    <tr>
                        <td style="text-align:justify;" class="text-center">7. </td>
                        <td style="text-align:justify;">Apabila PIHAK KE-I (SATU) melanggar surat perjanjian ini, maka
                            bersedia dituntut dihadapan Pengadilan Negeri Serang - Banten dengan catatan seluruh biaya
                            perkara ditanggung PIHAK KE-I (SATU).</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align:justify;">Demikian surat perjanjian ini dibuat dengan pikiran sehat dan tenang
                            tanpa ada unsur paksaan dari pihak manapun.</td>
                    </tr>
                    <tr style="height: 130px ;">
                        <td></td>
                        <td class="text-center">Serang - Banten, 29 Nov 2022</td>
                    </tr>
                </table>
                <table class="" style="width: 100%;">
                    <tr>
                        <td width="50%" class="text-end">
                            <p>({{ $customer->name }})</p>
                        </td>
                        <td class="text-end">
                            <p>(Materai 6000)</p>
                            <p></p>
                            <p>Nama</p>
                        </td>
                    </tr>
                </table>
                <div class="text-end py-3" id="action">
                    <td style="width:30% ;" class="text-end">
                        <a href="/customer/contract/detail/{{ $customer->id }}/?page=2"
                            class="btn btn-sm btn-primary">Back</a>
                        <button onclick="Cetak()" class="btn btn-sm btn-primary">Cetak</button>
                        <a href="/customer/contract/detail/{{ $customer->id }}/?page=4"
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
