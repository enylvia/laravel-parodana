@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Read Mail</h3>
					<div class="box-tools pull-right">
						<a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Previous"><i class="fa fa-chevron-left"></i></a>
						<a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Next"><i class="fa fa-chevron-right"></i></a>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body no-padding">
					@foreach($corrupts as $corrupt)
					<div class="mailbox-read-info">
						<h3>Kredit Macet</h3>
						<h5>From: support@parodana-m.id</h5>
						<span class="mailbox-read-time pull-right">{{$corrupt->created_at}}</span></h5>
					</div>
					<!-- /.mailbox-read-info -->
					<div class="mailbox-controls with-border text-center">
						<div class="btn-group">
							<button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="" data-original-title="Delete">
								<i class="fa fa-trash-o"></i></button>
							<button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="" data-original-title="Reply">
								<i class="fa fa-reply"></i>
							</button>
							<button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="" data-original-title="Forward">
								<i class="fa fa-share"></i>
							</button>
						</div>
						<!-- /.btn-group -->
						<button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="" data-original-title="Print">
						<i class="fa fa-print"></i></button>
					</div>
					<!-- /.mailbox-controls -->
					<div class="mailbox-read-message">
						<p> {{ date('F Y', strtotime($corrupt->due_date))}} </p>
					</div>
					<!-- /.mailbox-read-message -->
					@endforeach
					@foreach($duedates as $duedate)
					<div class="mailbox-read-info">
						<h3>Reminder : Jatuh Tempo</h3>
						<h5>From: support@parodana-m.id</h5>
						<span class="mailbox-read-time pull-right">{{$duedate->created_at->format('d-m-Y H:i:s')}}</span></h5>
					</div>
					<!-- /.mailbox-read-info -->
					<div class="mailbox-controls with-border text-center">
						<div class="btn-group">
							<button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="" data-original-title="Delete">
								<i class="fa fa-trash-o"></i></button>
							<button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="" data-original-title="Reply">
								<i class="fa fa-reply"></i>
							</button>
							<button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="" data-original-title="Forward">
								<i class="fa fa-share"></i>
							</button>
						</div>
						<!-- /.btn-group -->
						<button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="" data-original-title="Print">
						<i class="fa fa-print"></i></button>
					</div>
					<!-- /.mailbox-controls -->
					<div class="mailbox-read-message">
					<p> {{date('F Y', strtotime($duedate->due_date)) }} </p>
					</div>
					<!-- /.mailbox-read-message -->
					@endforeach
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<ul class="mailbox-attachments clearfix">
						<li>
						  <span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>

						  <div class="mailbox-attachment-info">
							<a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> Sep2014-report.pdf</a>
								<span class="mailbox-attachment-size">
								  1,245 KB
								  <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
								</span>
						  </div>
						</li>
						<li>
							<span class="mailbox-attachment-icon"><i class="fa fa-file-word-o"></i></span>

							<div class="mailbox-attachment-info">
								<a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> App Description.docx</a>
								<span class="mailbox-attachment-size">
									1,245 KB
									<a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
								</span>
							</div>
						</li>
						<li>
							<span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo1.png" alt="Attachment"></span>
	 
							<div class="mailbox-attachment-info">
								<a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> photo1.png</a>
								<span class="mailbox-attachment-size">
									 2.67 MB
									<a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
								</span>
							</div>
						</li>
						<li>
							<span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo2.png" alt="Attachment"></span>

							<div class="mailbox-attachment-info">
								<a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> photo2.png</a>
								<span class="mailbox-attachment-size">
								  1.9 MB
								  <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
								</span>
							</div>
						</li>
					</ul>
				</div>
				<!-- /.box-footer -->
				<div class="box-footer">
					<div class="pull-right">
						<button type="button" class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
						<button type="button" class="btn btn-default"><i class="fa fa-share"></i> Forward</button>
					</div>
					<button type="button" class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</button>
					<button type="button" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
				</div>
				<!-- /.box-footer -->
			</div>
			<!-- /. box -->
		</div>
	</div>
@endsection