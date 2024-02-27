@extends('layouts.app')
<meta name="_token" content="{!! csrf_token() !!}"/>
@section('content')

	<div class="row">
		<div class="col-md-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Inbox</h3>

					<div class="box-tools pull-right">
						<div class="has-feedback">
							<input type="text" class="form-control input-sm" placeholder="Search Mail">
							<span class="glyphicon glyphicon-search form-control-feedback"></span>
						</div>
					</div>
					<!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body no-padding">
					<div class="mailbox-controls">
						<!-- Check all button -->
						<button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
						</button>
						<div class="btn-group">
						  <button type="button" class="btn btn-default btn-sm delete_all" data-url="{{ url('mailbox/DeleteAll') }}"><i class="fa fa-trash-o"></i></button>						  
						  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
						  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
						</div>
						<!-- /.btn-group -->
						<button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
						<div class="pull-right">
						  1-50/200
						  <div class="btn-group">
							<button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
							<button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
						  </div>
						  <!-- /.btn-group -->
						</div>
						<!-- /.pull-right -->
					</div>
					<div class="table-responsive mailbox-messages">
						<table class="table table-hover table-striped">
							<tbody>
							
								@foreach($corrupts as $corrupt)
								<tr>
									<input type="hidden" name="corrupt">
									<td>
										<div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
											<input type="checkbox" style="position: absolute; opacity: 0;">
											<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
										</div>
									</td>
									<td class="mailbox-star">
										<a href="{{URL::to('mailbox/read', $corrupt->member_number)}}">
											<i class="fa fa-star text-yellow"></i>
										</a>
									</td>
									<td class="mailbox-name">
										<a href="{{URL::to('mailbox/read', $corrupt->member_number)}}">{{ $corrupt->name }}</a>
									</td>
									<td class="mailbox-subject">
										<b>Kredit Macet</b> - No. Anggota : {{ $corrupt->member_number }}
									</td>
									<td class="mailbox-attachment"></td>
									<td class="mailbox-date">{{$corrupt->created_at->diffForHumans()}}</td>
								</tr>
								@endforeach
								
								@foreach($duedates as $duedate)
								<tr>
									<input type="hidden" name="duedate">
									<td>
										<div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
											<input type="checkbox" style="position: absolute; opacity: 0;">
											<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
										</div>
									</td>
									<td class="mailbox-star">
										<a href="{{URL::to('mailbox/read', $duedate->member_number)}}">
											<i class="fa fa-star text-yellow"></i>
										</a>
									</td>
									<td class="mailbox-name">
										<a href="{{URL::to('mailbox/read', $duedate->member_number)}}">{{ $duedate->name }}</a>
									</td>
									<td class="mailbox-subject">
										<b>Jatuh Tempo</b> - No. Anggota : {{ $duedate->member_number }}
									</td>
									<td class="mailbox-attachment"></td>
									<td class="mailbox-date">{{$duedate->created_at->diffForHumans()}}</td>
								</tr>
								@endforeach
								
								@foreach($surveys as $survey)
								<tr>
									<input type="hidden" name="survey">
									<td>
										<div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
											<input type="checkbox" style="position: absolute; opacity: 0;">
											<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
										</div>
									</td>
									<td class="mailbox-star">
										<a href="{{URL::to('mailbox/read', $survey->reg_number)}}">
											<i class="fa fa-star text-yellow"></i>
										</a>
									</td>
									<td class="mailbox-name">
										<a href="{{URL::to('mailbox/read', $survey->reg_number)}}">{{ $survey->name }}</a>
									</td>
									<td class="mailbox-subject">
										<b>Surey</b> - No. Register : {{ $survey->reg_number }}
									</td>
									<td class="mailbox-attachment"></td>
									<td class="mailbox-date">{{$survey->created_at->diffForHumans()}}</td>
								</tr>
								@endforeach
								
								@foreach($notifications as $notifikasi)
								<tr>
									<td>{{ $notifikasi->data }}</td>
								</tr>
								@endforeach
								@foreach($mailboxs as $mailbox)
								<tr id="tr_{{$mailbox->id}}">
									<input type="hidden" name="mailbox">
									<td>
										<div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
											<input type="checkbox" style="position: absolute; opacity: 0;" class="sub_chk" data-id="{{$mailbox->id}}">
											<ins class="iCheck-helper" style="position: relative; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
										</div>
									</td>
									<td class="mailbox-star">
										<a href="{{URL::to('mailbox/read', $mailbox->id)}}">
											<i class="fa fa-star text-yellow"></i>
										</a>
									</td>
									<!--td class="mailbox-name">
										<a href="{{URL::to('mailbox/read', $mailbox->id)}}">{{ $mailbox->subject }}</a>
									</td-->
									<td class="mailbox-subject">
										<a href="{{URL::to('mailbox/read', $mailbox->id)}}">{{ $mailbox->subject }}</a>
									</td>
									<td class="mailbox-attachment"></td>
									<td class="mailbox-date">{{$mailbox->created_at->diffForHumans()}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<!-- /.table -->
					  </div>
					  <!-- /.mail-box-messages -->
				</div>
				<!-- /.box-body -->
				<div class="box-footer no-padding">
					<div class="mailbox-controls">
						<!-- Check all button -->
						<button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
						</button>
						<div class="btn-group">
						  <button type="button" class="btn btn-default btn-sm delete_all" data-url="{{ url('mailbox/DeleteAll') }}"><i class="fa fa-trash-o"></i></button>
						  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
						  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
						</div>
						<!-- /.btn-group -->
						<button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
						<div class="pull-right">
						  1-50/200
						  <div class="btn-group">
							<button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
							<button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
						  </div>
						  <!-- /.btn-group -->
						</div>
						<!-- /.pull-right -->
					</div>
				</div>
			</div>
			<!-- /. box -->
		</div>
	</div>
@endsection

@section('js')
<script>
  $(function () {
    //Enable iCheck plugin for checkboxes
    //iCheck for checkbox and radio inputs
    $('.mailbox-messages input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
      var clicks = $(this).data('clicks');
      if (clicks) {
        //Uncheck all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
      } else {
        //Check all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("check");
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
      }
      $(this).data("clicks", !clicks);
    });

    //Handle starring for glyphicon and font awesome
    $(".mailbox-star").click(function (e) {
      e.preventDefault();
      //detect type
      var $this = $(this).find("a > i");
      var glyph = $this.hasClass("glyphicon");
      var fa = $this.hasClass("fa");

      //Switch states
      if (glyph) {
        $this.toggleClass("glyphicon-star");
        $this.toggleClass("glyphicon-star-empty");
      }

      if (fa) {
        $this.toggleClass("fa-star");
        $this.toggleClass("fa-star-o");
      }
    });
  });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.icheckbox_flat-blue').on('click', function(e) {
         if($(this).is(':checked',true))  
         {
            $(".sub_chk").prop('checked', true);  
         } else {  
            $(".sub_chk").prop('checked',false);  
         }  
        });
        $('.delete_all').on('click', function(e) {
            var allVals = [];  
            $(".sub_chk:checked").each(function() {  
                allVals.push($(this).attr('data-id'));
            });  
            if(allVals.length <=0)  
            {  
                alert("Please select row.");  
            }  else {  
                var check = confirm("Are you sure you want to delete this row?");  
                if(check == true){  
                    var join_selected_values = allVals.join(","); 
                    $.ajax({
                        url: $(this).data('url'),
                        type: 'DELETE',
                        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                        data: 'ids='+join_selected_values,
                        success: function (data) {
                            if (data['success']) {
                                $(".sub_chk:checked").each(function() {  
                                    $(this).parents("tr").remove();
                                });
                                alert(data['success']);
                            } else if (data['error']) {
                                alert(data['error']);
                            } else {
                                alert('Whoops Something went wrong!!');
                            }
                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });
                  $.each(allVals, function( index, value ) {
                      $('table tr').filter("[data-row-id='" + value + "']").remove();
                  });
                }  
            }  
        });
        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function (event, element) {
                element.trigger('confirm');
            }
        });
        $(document).on('confirm', function (e) {
            var ele = e.target;
			//alert(ele);
            e.preventDefault();
            $.ajax({
                url: ele.href,
                type: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                success: function (data) {
                    if (data['success']) {
                        $("#" + data['tr']).slideUp("slow");
                        alert(data['success']);
                    } else if (data['error']) {
                        alert(data['error']);
                    } else {
                        alert('Whoops Something went wrong!!');
                    }
                },
                error: function (data) {
                    alert(data.responseText);
                }
            });
            return false;
        });
    });
</script>
@endsection