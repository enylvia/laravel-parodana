@extends('layouts.app')
@section('content')
	@include('error.error-notification')
	
	<div class="box">
	<div class="box-body">
			<div class="table-responsive">
				<table class="table table-responsive">
					<tr>
						<td>Total Tab. Pokok</td> <td align="right">Rp. {{ number_format($pokok, 0, ',' , '.') }}</td> <td>Total Tab. Sukarela</td> <td align="right">Rp. {{ number_format($sukarela, 0, ',' , '.') }}</td> 
					</tr>
					<tr>
						<td>Total Tab. Wajib</td> <td align="right">Rp. {{ number_format($wajib, 0, ',' , '.') }}</td> <td>Total Tabungan</td> <td align="right">Rp. {{ number_format($tabungan, 0, ',' , '.') }}</td> 
					</tr>
				</table>
			</div> 
		</div>
	</div>
		<div class="card">
			<div class="card-body">

				<div class="table-responsive">
					<table class="table table-responsive-sm table-striped" id="savings">
						<thead>
							<tr>
								<th>No</th>
								<th>{{trans('installment.customer_number')}}</th>
								<th>{{trans('installment.customer_name')}}</th>
								<th>Keterangan</th>
							</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="saving" tabindex="-1">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <form method="post" action="" id="savingForm" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="modal-header">
						<h4 class="modal-title">Tambahkan Tabungan</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" id="bodyFree">
						<div id="done-message" class="hide">
		                    <div class="alert alert-info alert-dismissible fade in" role="alert">
		                      <button type="button" class="close">
		                        <span>×</span>
		                      </button>
		                      <strong>Success!</strong>
		                    </div>
		                </div>
						<div class="form-group">
							<label for="memberNumber">No Anggota</label>
							<input type="text" class="form-control" name="memberNumber" value="" id="member_number">
						</div>
						<div class="form-group">
							<label for="status">Jenis Transaksi</label>
							<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="status" id="status" required>								
								<option value="setor">SETOR</option>
								<option value="tarik">TARIK</option>
							</select>
						</div>
						<div class="form-group">
							<label for="tipe">Jenis Tabungan</label>
							<select class="input select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="tipe" id="tipe" required>								
								<option value="wajib">WAJIB</option>
								<option value="pokok">POKOK</option>
								<option value="sukarela">SUKARELA</option>
							</select>
						</div>
						<div class="form-group">
							<label for="amount">Transfer Masuk</label>
							<input type="text" class="form-control" placeholder="Rp. " name="amount" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" id="amount" required>
						</div>
						<div class="form-group">
							<label for="description">Deskripsi</label>
							<textarea name="description" id="description" class="form-control"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">{{trans('general.close')}}</button>
						<button type="submit" class="btn btn-primary" id="saveBtnFree">Bayar</button>
					</div>
				</form>
	        </div>
	    </div>
	</div>


	<!-- modal mutasi -->
	<div class="modal fade" id="mutasi" tabindex="-1">
	    <div class="modal-dialog">
	        <div class="modal-content">
	           <div class="modal-body" id="mutasibody">
				<input type="hidden" id="member_number" name="member_number" value"" class="form-control">
					<div class="form-group">
						<label for="search">Pilih Limit</label>
						<select name="search" id="search" class="form-control">
							<option value="10">10 Transaksi Terakhir</option>
							<option value="5">5 Transaksi Terakhir</option>
							<option value="1">1 Transaksi Terakhir</option>
						</select>
					</div>
					<button class="btn btn-sm btn-success" onclick="ShowDataMutasi()">Submit</button>
					<div id="table-list">

					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btnAngsur" data-dismiss="modal">Tutup</button>
				</div>
	        </div>
	    </div>
	</div>
@endsection
@section('js')
<script type="text/javascript">
	function formatNumber(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        $(document).ready(function() {
            //var template = Handlebars.compile($("#details-template").html());
            var dataTables = $('#savings').DataTable({
                "processing": true,
                "serverSide": true,
                "deferRender": true,
                "ajax": {
                    url: "{{ route('deposit.json') }}",
                    cache: true
                },

                "columns": [
                    {
                        render: function (data,type,row,meta){
                            return meta.row + meta.settings._iDisplayStart+1;
                        },
                    },
                    {data: "member_number", name: "member_number"},
                    {data: "name", name: "name"},
					{
                        render: function (data,type,row){
                            return '<a onClick="ShowModalMutasi(this)" data-members="'+row.member_number+'" data-toggle="modal">Mutasi | </a> <a id="create" onClick="ShowModalSaving(this)" data-members="'+row.member_number+'" data-toggle="modal">Transaksi | </a> <a href="/deposit/card/'+row.member_number+'" target="_blank">Kartu</a>';
                        }
                    },
                ],
            });
        });

function ShowModalSaving(elem){
	var memberNumbers = $(elem).data("members");
	console.log(memberNumbers);
	var action = "{{URL::to('/deposit/store')}}";
	$('#member_number').val(memberNumbers);
	$('#savingForm').attr('action', action);
		$('#saving').modal('show');
}

function ShowModalMutasi(elem){
	var memberNumbers = $(elem).data("members");
	$('#member_number').val(memberNumbers);
	var limit = $('#search').find(":selected").val();
	$('#mutasi').modal('show');
	$('#table-list').html('');
}
function ShowDataMutasi(elem){
	var memberNumbers = $('#member_number').val();
	console.log(memberNumbers);
	var limit = $('#search').find(":selected").val();
	$.ajax({
			headers: {
				'X-CSRF-Token': $('meta[name="_token"]').attr('content')
			},
			url: "{{ url('deposit/getSaving') }}/"+limit+"/"+memberNumbers,
			type: 'get',
			success: function(response){
				//$('.modal-body').html(response);
				$('#table-list').html(response);
				$('#mutasi').modal('show');
			},
			error: function(error) {
				console.log(error);
			}
		});
}
</script>
@endsection