<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar sidebarscroll" style="float:left">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (!Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ asset('img/logo/logo-small.png') }}" class="logo-image-mini" width="25" />
                </div>
                <div class="pull-left info">
                    <p>{{ auth()->user()->name }}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
        @endif

{{--        <!-- search form (Optional) -->--}}
{{--        <form action="#" method="get" class="sidebar-form">--}}
{{--            <div class="input-group">--}}
{{--                <input type="text" name="q" class="form-control" placeholder="Search..." />--}}
{{--                <span class="input-group-btn">--}}
{{--                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i--}}
{{--                            class="fa fa-search"></i></button>--}}
{{--                </span>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--        <!-- /.search form -->--}}

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <!--this code create by --->
            <li class="treeview">
                <a href="/">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview menu-open">
                <a href="">
                    <i class="fa fa-clipboard"></i>
                    <span>Nasabah</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
{{--                    <li class="active">--}}
{{--                        <a href="/savings-form">--}}
{{--                            <i class="fa fa-cil-money"></i>--}}
{{--                            <span>Formulir Simpanan</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li class="active">
                        <a href="/customer/form">
                            <i class="fa fa-address-book"></i>
                            <span>Formulir Pinjaman</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/customer/survey">
                            <i class="fa fa-globe"></i>
                            <span>Survey</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/customer/approve">
                            <i class="fa fa-handshake-o"></i>
                            <span>Pengesahan</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/customer/contract">
                            <i class="fa fa-pencil"></i>
                            <span>Akad</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/customer/document">
                            <i class="fa fa-upload"></i>
                            <span>Unggah Berkas</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/customer/list">
                            <i class="fa fa-users"></i>
                            <span>Daftar Nasabah</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/customer/survey/approve">
                            <i class="fa fa-check"></i>
                            <span>Pengesahan Survey</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/customer/handover">
                            <i class="fa fa-handshake-o"></i>
                            <span>Serah Terima</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/customer/balance">
                            <i class="fa fa-bank"></i>
                            <span>Saldo</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/customer/contract/list">
                            <i class="fa fa-file-code-o"></i>
                            <span>Daftar Kontrak</span>
                        </a>
                    </li>
{{--                    <li class="active">--}}
{{--                        <a href="/customer/reloan">--}}
{{--                            <i class=""></i>--}}
{{--                            <span>Pinjam Uang</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                </ul>
            </li>
            <li class="treeview menu-open">
                <a href="">
                    <i class=""></i>
                    <span>Tabungan</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="active">
                        <a href="/deposit">
                            <i class="fa fa-credit-card"></i>
                            <span>Tabungan</span>
                        </a>
                    </li>
{{--                     <li class="active">--}}
{{--                        <a href="/withdrawal">--}}
{{--                            <i class="fa fa-money"></i>--}}
{{--                            <span>Withdraw</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                </ul>
            </li>
            <li class="treeview menu-open">
                <a href="">
                    <i class=""></i>
                    <span>Pinjam</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="active">
                        <a href="/installment">
                            <i class="fa fa-cil-money"></i>
                            <span>Cicilan</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/installment/lunas">
                            <i class=""></i>
                            <span>Lunas</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="/employee">
                    <i class=""></i>
                    <span>Pegawai</span>
                </a>
            </li>
            <li class="treeview menu-open">
                <a href="">
                    <i class=""></i>
                    <span>Transaksi</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="active">
                        <a href="{{ route('transaction.history') }}">
                            <i class="fa fa-pencil"></i>
                            <span>Riwayat Transaksi</span>
                        </a>
                    </li>

                    {{-- <li class="active">
                        <a href="/receipt">
                            <i class="fas fa-file-o"></i>
                            <span>Kwitansi</span>
                        </a>
                    </li> --}} {{-- ini di hidden sementara jika butuhkan kembali silahkan di uncomment --}}

                    <li class="active">
                        <a href="/operational">
                            <i class=""></i>
                            <span>Operasional</span>
                        </a>
                    </li>

                    {{-- <li class="active">
                        <a href="/transaction/payment">
                            <i class="fa fa-money"></i>
                            <span>Pembayaran</span>
                        </a>
                    </li> --}} {{-- ini di hidden sementara, jika dibutuhkan silahkan di uncomment --}}

                    <li class="active">
                        <a href="/transaction/purchase">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Pembelian</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview menu-open">
                <a href="">
                    <i class=""></i>
                    <span>Tempo</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="active">
                        <a href="/transaction/tempo">
                            <span>Pengajuan</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/transaction/tempo/kesepakatan">
                            <span>Kesepakatan</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/transaction/tempo/berjalan">
                            <span>Tempo Berjalan</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview menu-open">
                <a href="">
                    <i class=""></i>
                    <span>Pengaturan</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="active">
                        <a href="/company">
                            <i class="cil-building"></i>
                            <span>Perusahaan</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/role">
                            <i class="cil-x"></i>
                            <span>Jabatan</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/user/management">
                            <i class=""></i>
                            <span>Manajemen Pengguna</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/interest/rate">
                            <i class=""></i>
                            <span>Suku Bunga</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="">
                            <i class="/menu/management"></i>
                            <span>Manajemen Menu</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/setting/tax">
                            <i class="fa fa-money"></i>
                            <span>Pajak</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/transaction/type">
                            <i class=""></i>
                            <span>Transaction Type</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/setting/application">
                            <i class=""></i>
                            <span>Application</span>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- after update --}}
            <li class="treeview menu-open">
                <a href="">
                    <i class=""></i>
                    <span>Laporan Keuangan</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="active">
                        <a href="/transaction/report">
                            <i class=""></i>
                            <span>Laporan Harian</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/ledger">
                            <i class="fa fa-book"></i>
                            <span>Buku Besar</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/neraca/saldo">
                            <i class="fa fa-balance-scale"></i>
                            <span>Neraca Sistem</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="{{route('neraca.index.saldo')}}">
                            <i class="fa fa-balance-scale"></i>
                            <span>Neraca Saldo</span>
                        </a>
                    </li>
                    <li class="active">
                        <!-- <a href="/report/profitloss"> -->
                        <a href="/report/profitloss">
                            <i class=""></i>
                            <span>Laba Rugi</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/report/index-perubahan-modal">
                            <i class="fa fa-money"></i>
                            <span>Laporan Perubahan Modal</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="{{ route('index.report.keuangan') }}">
                            <i class="fa fa-money"></i>
                            <span>Laporan Posisi Keuangan</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview menu-open">
                <a href="">
                    <i class=""></i>
                    <span>Laporan Pinjaman</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="active">
                        <a href="/transaction/buku-hutang">
                            <i class="fa fa-book"></i>
                            <span>Buku Piutang</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/transaction/report/running/index">
                            <i class=""></i>
                            <span>Laporan Angsuran Berjalan</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="{{ route('transaction.not_running') }}">
                            <i class=""></i>
                            <span>Laporan Angsuran Tak Tertagih</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/transaction/baki">
                            <i class=""></i>
                            <span>Laporan Baki</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/transaction/report/new-nasabah/index">
                            <i class=""></i>
                            <span>Laporan Pinjaman Baru</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="{{ route('insur') }}">
                            <i class=""></i>
                            <span>Laporan Asuransi</span>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- end after update --}}

            <li class="treeview">
                <a href="/import">
                    <i class=""></i>
                    <span>Import Data</span>
                </a>
            </li>
            <li class="treeview">
                <a href="/simulation/credit">
                    <i class=""></i>
                    <span>Simulation Credit</span>
                </a>
            </li>
            <li class="treeview">
                <a href="/mailbox">
                    <i class="fa fa-envelope"></i>
                    <span>Mailbox</span>
                </a>
            </li>

        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
