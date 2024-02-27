@extends('layouts.app')
@section('content')

<div class="container-fluid">
	@include('error.error-notification')
    <div class="row">
	@foreach($users as $user)
		<?php 
			$employees = App\Models\Employee::where('user_id',$user->id)->first();
			$educations = App\Models\Education::where('id',$employees->education)->get();
		?>		
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary box-outline">
				<div class="box-body box-profile">
					<div class="text-center">
					  <img class="profile-user-img img-fluid img-circle" src="{{ asset('./img/'.$user->avatar) }}" alt="User profile picture">
					</div>

					<h3 class="profile-username text-center">{{$user->name}}</h3>

					<p class="text-muted text-center"></p>

					<ul class="list-group list-group-unbordered mb-3">
						<li class="list-group-item">
							<b>Saldo</b> <a class="float-right">0</a>
						</li>
					</ul>
                <!--a href="#" class="btn btn-primary btn-block"><b>Follow</b></a-->
				</div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title">About Me</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <strong><i class="fas fa-book mr-1"></i> Education</strong>

                <p class="text-muted">
				@if(!empty($employees->education))
					@foreach($educations as $education)
						{{$education->code}} ( {{$education->name}} )
					@endforeach
				@else
					-
				@endif				
                </p>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                <p class="text-muted">
				@if(!empty($employees->address))
					{{$employees->address}}
				@else
					-
				@endif				
				</p>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="box">
              <div class="box-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
				  <li class="nav-item"><a class="nav-link active" href="#changePassword" data-toggle="tab">Change Password</a></li>
                </ul>
              </div><!-- /.box-header -->
              <div class="box-body">
                <div class="tab-content">                                

                  <div class="tab-pane" id="settings">
                    <form class="form-horizontal">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" id="inputName" placeholder="Name">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="inputName2" placeholder="Name">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>
                        <div class="col-sm-10">
                          <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Skills</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
					<div class="tab-pane active" id="changePassword">
						<form method="post" action="{{ route('employee.changePassword') }}">
						@csrf
							<div class="form-group row">
								<label for="inputName" class="col-sm-2 col-form-label">Password Lama</label>
								<div class="col-sm-10">
								  <input type="password" name="current-password" class="form-control" id="inputCurrentPassword" placeholder="Password Lama">
								</div>
							</div> 
							<div class="form-group row">
								<label for="inputEmail" class="col-sm-2 col-form-label">Password Baru</label>
								<div class="col-sm-10">
								  <input type="password" name="new-password" class="form-control" id="inputNewPassword" placeholder="Password Baru">
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail" class="col-sm-2 col-form-label">Konfirmasi Password</label>
								<div class="col-sm-10">
								  <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Konfirmasi Password" required autocomplete="new-password">
								</div>
							</div>
							<div class="form-group row">
								<div class="offset-sm-2 col-sm-10">
								  <button type="submit" class="btn btn-danger">Submit</button>
								</div>
							</div>
						</form>
					</div>
				</div>
                <!-- /.tab-content -->
              </div><!-- /.box-body -->
            </div>
            <!-- /.nav-tabs-custom -->		
        </div>
          <!-- /.col -->		
	@endforeach
	</div>
	<!-- /.row -->
</div>
@endsection