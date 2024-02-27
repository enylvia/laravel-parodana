@extends('layouts.document_layout')
@section('content')
    <div class="container py-5">

        <div class="letter__agreement-container" id="btn-cetak">
            <div class="letter-agreement">
                <div class="header__letter-agreement">
                    <div class="header__letter-left">
                        <img class="header__letter-logo" src="{{ asset('img/logo/right_logo.jpeg') }}"
                            width="155" />
                    </div>
                    <div class="header__letter-center">
                        <h3 class="header__letter-title">KOPERASI SIMPAN PINJAM PARODANA <span class="label">M</span>
                        </h3>
                        <h3 class="header__letter-title">BADAN HUKUM : 38 / BH / XI / KUMKM/ I/ 2016</h3>
                        <h3 class="header__letter-title">PAD NOMOR AHU-0001687.AH.01.38. TAHUN 2022</h3>
                        <h3 class="header__letter-subtitle">RUKO 9 KavIV.Kp,GARDU RT 006 /001 Ds.TAMBAKKec,KIBIN
                        </h3>
                        <h3 class="header__letter-subtitle">SERANG - BANTENP</h3>
                        <h3 class="header__letter-subtitle">TELP. 02547950187 – HP. 082311273250</h3>
                    </div>
                    <div class="header__letter-right">
                        <img class="header__letter-logo" src="{{ asset('img/logo/left_logo.jpeg') }}"
                            width="155" />
                    </div>
                </div>
                <div class="body__letter-agreement">
                    <h3 class="body__letter-title">SURAT PERJANJIAN KREDIT</h3>
                    <p class="body__letter-data">Yang bertanda tangan di bawah ini :</p>
                    <div class="body__letter__data-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th class="letter__record-nasabah">NAMA</th>
                                    <th>: {{ $customer->name }}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="letter__record-nasabah">ALAMAT RUMAH</th>
                                    <th>: {{ $customer->address }}</th>
                                </tr>
                                <tr>
                                    <th class="letter__record-nasabah">PEKERJAAN</th>
                                    <th>: {{ $customer->part }}</th>
                                </tr>
                                <tr>
                                    <th class="letter__record-nasabah">PERUSAHAAN</th>
                                    <th>: INDAH KIAT</th>
                                </tr>
                                <tr>
                                    <th class="letter__record-nasabah">NOMOR REKENING</th>
                                    <th>: {{ $customer->bank_number }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="body__letter-agreement-rules">
                        <p class="body__header-rules">Selanjutnya disebut <b>PIHAK KE 1 (SATU) (YANG BERHUTANG)</b><br />
                            Pimpinan koperasi PARODANA M dalam hal tersebut untuk dan atas Nama Koperasi <b>PARODANA
                                M.</b> Ruko 9
                            KAV.4 KP GARDU RT .006/RW,001 DESA TAMBAK KEC. KIBIN KAB. SERANG BANTEN. Selanjutnya dsebut
                            dengan <b>PIHAK KE II (DUA) (KOPERASI).</b><br />
                            <b>KEDUA BELAH PIHAK MENGADAKAN PERJANJIAN KREDIT SEBAGAI BERIKUT :</b>
                        </p>
                        <ol class="body__letter-rules-items">
                            <li>PIHAK KE 1 (SATU) mengaku telah pinjam uang
                                dari PIHAK KE
                                II (DUA) Rp. {{ number_format($approveCustomer->approve_amount, 0, ',', '.') }} dengan jasa
                                atau suku bunga 3% (tiga persen) perbulan flat (secara terus
                                menerus).</li>
                            <li>PIHAK KE I (SATU) menyetujui potongan tabungan (Rp {{ number_format($approveCustomer->m_savings) }}) dan biaya
                                tata laksana 3% dari jumlah pinjaman yang di berikan. Sebagai JAMNAN atas pinjaman yang
                                diberikan
                                PIHAK KE 1 (SATU) telah menyerahkan IJAZAH, JAMSOSTEK, BUKU REKENING + ATM PENGGAJIAN dan
                                selengkapnya tertera di serah terima berkas.</li>
                            <li>PIHAK KE I ( SATU) menyetujui penggesekan ATM atau pengambilan gaji melalui atm penggajian
                                dan dipotong sesuai Angsuran dengan No Rek: {{ $customer->bank_number }} A/N
                                {{ $customer->bank_name }}. Saya bersedia menyerahkan
                                ATM dan BUKU TABUNGAN tersebut kepada PIHAK KOPERASI SIMPAN PINJAM PARODANA M.</li>
                            <li>Untuk pembayaran pinjaman ini, PIHAK KE I (SATU) bersedia di potong gaji oleh PIHAK KE II (
                                DUA ) melalui transferan gaji PIHAK KE I (SATU) sebesar Rp {{ $memberNumber->pay_month }} x
                                {{ $approveCustomer->time_period }} Bulan.</li>
                            <li>PIHAK KE 1 (SATU) wajib menabung sebesar Rp 48.000 setiap bulannya.</li>
                            <li>Apabila karena hal pembayaran gaji PIHAK KE I ( SATU ) diberhentikan atau PHK baik karena
                                sebab lain, maka PIHAK KE I ( SATU) WAJIB melunasi pinjaman ( termasuk denda) dari PESANGON
                                atau dari SALDO JAMSOSTEK. </li>
                            <li>Apabila selama perjanjian ini berjalan dan PIHAK KE I (SATU) mengajukan akad kredit. kepada
                                pihak lain maka PIHAK I (SATU) wajib melunasi pinjaman kepada PIHAK KE II (DUA), dan apabila
                                tidak mencukupi untuk pelunasan, maka PIHAK KE I (SATU) WAJIB menabung sebasar 25% dari
                                pencairan.</li>
                            <li>Apabila PIHAK KE I (SATU) terbukti menggandakan berkas maka PIHAK KE 1 (SATU) WAJIB melunasi
                                pinjaman kepada PIHAK II (DUA) sesuai perjanjian yang sudah disepakati.</li>
                            <li>Apabila dikemudian hari terjadi perselisihan atas perjanjian yang sudah disepakati
                                tersebut diatas, maka PIHAK KE I (SATU) wajib menyelesaikan dengan cara musyawarah dan
                                mufakat tanpa ada campur tangan dari pihak atau lembaga-lembaga dari manapun. Apabila tidak
                                ada penyelesaian akibat dari perselisihan tersebut diatas, akan diselesaikan di Pengadilan
                                Negeri Serang-Banten dengan seluruh biaya perkara ditanggung oleh PIHAK I (SATU).</li>
                        </ol>
                        <p><b>Demikianlah surat perjanjian ini dibuat dan ditandatangani dengan pikiran sehat dan tenang
                                tanpa ada unsur paksaan dari pihak manapun.</b></p>
                    </div>
                    <p class="signature-date"><b>Kibin, 10 November 2023</b></p>
                    <div class="signature">
                        <div class="left-signature">
                            <p><b><u>PIHAK II (DUA) KOPERASI PARODANA M</u></b></p>
                            <h3>(.....................................)</h3>
                        </div>
                        <div class="right-signature">
                            <p><b><u>PIHAK KE I (SATU) (YANG BERHUTANG)</u></b></p>
                            <div>
                                <p>Materai 10000</p>
                                <h3 class="name-signature">({{ strtoupper($customer->name) }})</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="letter__action-button">
                    <a href="/customer/contract/detail/{{ $customer->id }}/?page=2" class="btn btn-sm btn-primary">Back</a>
                    <button onclick="Cetak()" class="btn btn-sm btn-primary">Cetak</button>
                    <a href="/customer/contract/detail/{{ $customer->id }}/?page=4" class="btn btn-sm btn-primary">Next</a>
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
