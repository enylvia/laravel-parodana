<script>
$(document).on('change', '#page', function(e){	 
  var page = $('#page option:selected').val();
  //alert(page);
  <?php 
	$page = $_GET["page"];
	$customers = App\Models\Customer::paginate($page);
  ?>
 });
 
});
</script>
<div class="box-footer">
	<div class="pull-left">
		{{ $customers->links('vendor.pagination.bootstrap-4') }}
	</div>
</div>