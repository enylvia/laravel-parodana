<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        * {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('./css/surat-perjanjian.css') }}">

</head>

<body>
    @yield('content')

    @yield('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#namaNasabah').select2();
        });
    </script>
    <script>
        function formatCurrency(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(number);
        }

        $('#namaNasabah').on('change', function() {
            var selectedValue = $(this).val();
            var besarPinjaman = document.getElementById('besarPinjaman');
            var angsuran = document.getElementById('angsuran');
            var noTabungan = document.getElementById('noTabungan');
            var perusahaan = document.getElementById('perusahaan');

            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                },
                url: "{{ url('transaction/get-buku-hutang') }}/" + selectedValue,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var htmlData = response.html;
                    var loanData = response.loan;
                    var approvedAmount = response.loan_amount;

                    $('#table-list').html(htmlData);
                    var formattedLoanAmount = formatCurrency(approvedAmount);
                    var formattedPayMonth = formatCurrency(loanData.pay_month);
                    besarPinjaman.textContent = formattedLoanAmount;
                    angsuran.textContent = formattedPayMonth.concat(" x ", loanData.time_period,
                        " Bulan");
                    noTabungan.textContent = loanData.member_number;
                    perusahaan.textContent = "NIKOMAS";
                },
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>
</body>

</html>
