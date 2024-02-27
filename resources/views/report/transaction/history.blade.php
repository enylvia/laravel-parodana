@extends('layouts.app')

@section('title')
    <title>Laporan History Transaksi</title>
@endsection

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active">Laporan Riwayat Transaksi</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Laporan Riwayat Transaksi
                            </h4>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <!-- FORM UNTUK FILTER BERDASARKAN DATE RANGE -->
                            <form action="#" method="get">
                                <div class="form-group col-sm-6">
                                    <input type="text" id="created_at" name="date" class="form-control">
                                </div>
								<div class="form-group col-sm-6">						
                                    <a target="_blank" class="btn btn-primary ml-2" id="exportpdf">View PDF</a>
                                </div>
                            </form>
                            <!--div class="table-responsive">                                
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">NO</th>
                                            <th rowspan="2">NO PINJAMAN</th>
                                            <th rowspan="2">NAMA</th>
                                            <th rowspan="2">NO TAB</th>
                                            <th rowspan="2">CARA BAYAR</th>
                                            <th rowspan="2">UANG MASUK</th>
                                            <th colspan="5">ANGSURAN</th>
                                        </tr>                                   
                                        <tr>
                                            <th>BUNGA</th>
                                            <th>POKOK</th>
                                            <th>TABUNGAN</th>
                                            <th>DENDA</th>
                                            <th>TOTAL</th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2">TEMPO</th>
                                            <th rowspan="2">TOTAL</th>
                                            <th rowspan="2">SISA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

<!-- KITA GUNAKAN LIBRARY DATERANGEPICKER -->
@section('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
        //KETIKA PERTAMA KALI DI-LOAD MAKA TANGGAL NYA DI-SET TANGGAL SAA PERTAMA DAN TERAKHIR DARI BULAN SAAT INI
        $(document).ready(function() {
            let start = moment().startOf('month')
            let end = moment().endOf('month')

            //KEMUDIAN TOMBOL EXPORT PDF DI-SET URLNYA BERDASARKAN TGL TERSEBUT
            $('#exportpdf').attr('href', '/report/history/transaction/print/' + start.format('YYYY-MM-DD') + '+' + end.format('YYYY-MM-DD'))

            //INISIASI DATERANGEPICKER
            $('#created_at').daterangepicker({
                startDate: start,
                endDate: end
            }, function(first, last) {
                //JIKA USER MENGUBAH VALUE, MANIPULASI LINK DARI EXPORT PDF
                $('#exportpdf').attr('href', '/report/history/transaction/print/' + first.format('YYYY-MM-DD') + '+' + last.format('YYYY-MM-DD'))
            })
        })
    </script>
@endsection()