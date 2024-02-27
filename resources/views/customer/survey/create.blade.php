@extends('layouts.app')
@section('content')

@include('error.error-notification')

	
	<div class="box">
	<form method="post" action="{{route('survey.store')}}" enctype="multipart/form-data">
	{{ csrf_field() }}
		<div class="box-body">
			<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<label>{{trans('survey.environment_condition')}}</label>
				<input type="hidden" value="{{$getID}}" name="customer_id" class="form-control">
				<input type="text" id="environment_condition" name="environment_condition" class="form-control">
			</div>
			<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<label>{{trans('survey.viability')}}</label>
				<input type="text" id="viability" name="viability" class="form-control">
			</div>
			<div class="table-responsive col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<table class="table table-responsive table-striped">
					<thead>
						<tr>
							<th>PENDAPATAN</th>
							<th>JUMLAH</th>
							<th>PENGELUARAN</th>
							<th>JUMLAH</th>
						</tr>
					</thead>
					<tbody>
					@foreach($customers as $survey)
						<tr>							
							<td>Gaji Bersih</td>
							<td align="right">Rp. {{ number_format($survey->net_salary, 0, ',' , '.') }}</td>
							<input type="hidden" class="form-control" onkeyup="sumPendapatan(); javascript:tandaPemisahTitik(this);" value="{{ $survey->net_salary }}" id="net_salary" onkeydown="return numbersonly(this, event);">
							<td>Biaya Anak</td>
							<td align="right">
							<input type="text" name="child_fee" class="form-control" onkeyup="sumPengeluaran(); sumPendapatan(); javascript:tandaPemisahTitik(this);" placeholder="Rp. 0" id="child_fee" onkeydown="return numbersonly(this, event);">
							<input type="hidden" name="reg_number" class="form-control" value="{{$survey->reg_number}}">
							</td>
						</tr>
						<tr>
							<td>Gaji Kotor</td>
							<td align="right">Rp. {{ number_format($survey->gross_salary, 0, ',' , '.') }}</td>
							<input type="hidden" class="form-control" onkeyup="sumPendapatan(); javascript:tandaPemisahTitik(this);" value="{{ $survey->gross_salary }}" id="gross_salary" onkeydown="return numbersonly(this, event);">
							<td>Biaya Listrik</td>
							<td align="right"><input type="text" name="electricity_cost" class="form-control" onkeyup="sumPengeluaran()" placeholder="Rp. 0" id="electricity_cost" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"></td>
						</tr>
						<tr>
							<td>Pendapatan Istri/Suami</td>
							<td align="right">Rp. {{ number_format($survey->husband_wife_income, 0, ',' , '.') }}</td>
							<input type="hidden" class="form-control" onkeyup="sumPendapatan(); javascript:tandaPemisahTitik(this);" value="{{ $survey->husband_wife_income }}" id="husband_wife_income" onkeydown="return numbersonly(this, event);" >
							<td>Biaya Air</td>
							<td align="right"><input type="text" name="water_cost" class="form-control" onkeyup="sumPengeluaran()" placeholder="Rp. 0" id="water_cost" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"></td>
						</tr>
						<tr>
							<td>Pendapatan Lain</td>
							<td align="right">Rp. {{ number_format($survey->other_income, 0, ',' , '.') }}</td>
							<input type="hidden" class="form-control" onkeyup="sumPendapatan(); javascript:tandaPemisahTitik(this);" value="{{ $survey->other_income }}" id="other_income" onkeydown="return numbersonly(this, event);">
							<td>Angsuran Lain</td>
							<td align="right"><input type="text" name="other_installment" class="form-control" onkeyup="sumPengeluaran()" placeholder="Rp. 0" id="other_installment" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"></td>
						</tr>
						<tr>
							<td></td>
							<td align="right"></td>
							<td>Biaya Hidup</td>
							<td align="right"><input type="text" name="cost_of_living" class="form-control" onkeyup="sumPengeluaran()" placeholder="Rp. 0" id="cost_of_living" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"></td>
							<td></td>
							<td align="right"></td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td align="right" colspan="2" style="font-weight: bold;">Total Pendapatan</td> <td colspan="2" align="right" style="font-weight: bold;"><input type="text" class="form-control" placeholder="Rp. 0" id="total_pendapatan" disabled></td>
						</tr>
						<tr>
							<td align="right" colspan="2" style="font-weight: bold;">Total Pengeluaran</td> <td colspan="2" align="right" style="font-weight: bold;"><input type="text" class="form-control" placeholder="Rp. 0" id="total_pengeluaran" disabled></td>
						</tr>
						<tr>
							<td align="right" colspan="2" style="font-weight: bold;">Pendapatan Bersih </td> <td colspan="2" align="right" style="font-weight: bold;"><input type="text" class="form-control" placeholder="Rp. 0" id="pendapatan_bersih" disabled></td>
						</tr>
					</tfoot>
					@endforeach
				</table>
			</div>
			
			<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label>{{trans('survey.note')}}</label>
				<!--input type="text" id="note" name="note" class="form-control"-->
				<textarea class="form-control" id="note" name="note" rows="4" cols="50">
				</textarea>
			</div>	
			<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<?php 
					$petas = App\Models\Customer::where('id',$getID)->get();
				?>
				@foreach($petas as $peta)
				<?php 
					$provinsi = App\Models\Provinsi::where('id',$peta->provinsi)->first();
					$kabupaten = App\Models\Kabupaten::where('id',$peta->kabupaten)->first();
					$kecamatan = App\Models\Kecamatan::where('id',$peta->kecamatan)->first();
					$kelurahan = App\Models\Kelurahan::where('id',$peta->kelurahan)->first();
				?>
				<iframe frameborder="0" height="400" scrolling="no" src="//maps.google.com/maps?q={{$peta->address}},
					&amp{{ !empty($kelurahan->nama) ? '' : '$kelurahan->nama' }},
					&amp{{ !empty($kecamatan->nama) ? '' : '$kecamatan->nama' }},
					&amp{{ !empty($kabupaten->nama) ? '' : '$kabupaten->nama' }},
					&amp{{ !empty($provinsi->nama)  ? '' : '$kecamatan->nama' }}&amp;
					num=1&amp;t=m&amp;ie=UTF8&amp;z=14&amp;output=embed" width="100%">
				</iframe>
				@endforeach
			</div>				
		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-primary">{{trans('general.submit')}}</button>
		</div>
	</form>
	</div>

@endsection
@section('js')
	
	<script type="text/javascript">						    
		CKEDITOR.replace('note', {
			"filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
		});						    
	</script>
	<script>		
		function sumPendapatan(){
			var net_salary = document.getElementById("net_salary").value;
			var gross_salary = document.getElementById("gross_salary").value;
			var wife_income = document.getElementById("husband_wife_income").value;		
			var other_income = document.getElementById("other_income").value;
			if (net_salary == "") {
				var gaji_bersih = 0;
			} else {
				var gaji_bersih = net_salary.split('.').join('');
			}
			if (gross_salary == "") {
				var gaji_kotor = 0;
			} else {
				var gaji_kotor = gross_salary.split('.').join('');
			}
			if (other_income == "") {
				var pendapatan_lain = 0;
			} else {
				var pendapatan_lain = other_income.split('.').join('');
			}
			if (wife_income == "") {
				var istri_suami = 0; 
			} else {
				var istri_suami = wife_income.split('.').join('');
			}
			var total_pendapatan = parseInt(gaji_bersih) + parseInt(istri_suami) + parseInt(pendapatan_lain);
			// alert(total_pendapatan);
			if (!isNaN(total_pendapatan)) {
				var pendapatan = Math.ceil(total_pendapatan);
				var	pendapatan_string = pendapatan.toString(),
					sisa 	= pendapatan_string.length % 3,
					pendapatan_rupiah 	= pendapatan_string.substr(0, sisa),
					pendapatan_ribuan 	= pendapatan_string.substr(sisa).match(/\d{3}/g);
						
				if (pendapatan_ribuan) {
					separator = sisa ? '.' : '';
					pendapatan_rupiah += separator + pendapatan_ribuan.join('.');
				}
				document.getElementById('total_pendapatan').value = pendapatan_rupiah;
			}
			sumBersih();
		}
				
		function sumPengeluaran(){
			var child_fee = document.getElementById("child_fee").value;			
			var electricity_cost = document.getElementById("electricity_cost").value;			
			var water_cost = document.getElementById("water_cost").value;			
			var other_installment = document.getElementById("other_installment").value;
			var cost_of_living = document.getElementById("cost_of_living").value;
			if (other_installment == "") {
				var angsuran_lain = 0;
			} else {
				var angsuran_lain = other_installment.split('.').join('');
			}
			if (water_cost == "") {
				var biaya_air = 0;
			} else {
				var biaya_air = water_cost.split('.').join('');
			}
			if (electricity_cost == "") {
				var biaya_listrik = 0;
			} else {
				var biaya_listrik = electricity_cost.split('.').join('');
			}
			if (child_fee == "") {
				var biaya_anak = 0;
			} else {
				var biaya_anak = child_fee.split('.').join('');
			}
			if (cost_of_living == "") {
				var biaya_hidup = 0;
			} else {
				var biaya_hidup = cost_of_living.split('.').join('');
			}
			var total_pengeluaran = parseInt(biaya_anak) + parseInt(biaya_listrik)+parseInt(biaya_air) + parseInt(angsuran_lain) + parseInt(biaya_hidup);
			//alert(total_pendapatan);
			if (!isNaN(total_pengeluaran)) {
				var pengeluaran = Math.ceil(total_pengeluaran);
				var	pengeluaran_string = pengeluaran.toString(),
					sisa 	= pengeluaran_string.length % 3,
					pengeluaran_rupiah 	= pengeluaran_string.substr(0, sisa),
					pengeluaran_ribuan 	= pengeluaran_string.substr(sisa).match(/\d{3}/g);
						
				if (pengeluaran_ribuan) {
					separator = sisa ? '.' : '';
					pengeluaran_rupiah += separator + pengeluaran_ribuan.join('.');
				}
				document.getElementById('total_pengeluaran').value = pengeluaran_rupiah;
			}
			sumBersih();
		}						
		
		function sumBersih(){
			var tot_pendapatan = document.getElementById("total_pendapatan").value;
			var tot_pengeluaran = document.getElementById("total_pengeluaran").value;
			if (tot_pendapatan == "") {
				var x = 0;
			} else {
				var x = tot_pendapatan.split('.').join('');
			}
			if (tot_pengeluaran == "") {
				var y = 0;
			} else {
				var y = tot_pengeluaran.split('.').join('');
			}
			var total = parseInt(x) - parseInt(y);
			//alert(total);
			
			if (!isNaN(total)) {
				var bersih = Math.ceil(total);
				var	bersih_string = bersih.toString(),
					sisa 	= bersih_string.length % 3,
					bersih_rupiah 	= bersih_string.substr(0, sisa),
					bersih_ribuan 	= bersih_string.substr(sisa).match(/\d{3}/g);
						
				if (bersih_ribuan) {
					separator = sisa ? '.' : '';
					bersih_rupiah += separator + bersih_ribuan.join('.');
				}
				
				document.getElementById('pendapatan_bersih').value = bersih_rupiah;
			}
		}
	</script>
@endsection