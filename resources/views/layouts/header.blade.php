<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="#" class="logo"><b>Parodana-M</b></a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 0 messages</li>
                        <li>
                            <!-- inner menu: contains the messages -->
                            <ul class="menu">
                                <li><!-- start message -->
                                    <a href="#">
                                        <div class="pull-left">
                                            <!-- User Image -->
                                            <img src="#" class="img-circle" alt="User Image"/>
                                        </div>
                                        <!-- Message title and timestamp -->
                                        <h4>
                                            Support Team
                                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                        </h4>
                                        <!-- The message -->
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li><!-- end message -->
                            </ul><!-- /.menu -->
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li><!-- /.messages-menu -->

                <!-- Notifications Menu -->
                <li class="dropdown notifications-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">{{ auth()->user()->unreadNotifications->count() }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Anda memiliki {{ auth()->user()->unreadNotifications->count() }} notifikasi</li>
                        <li>
                            <!-- Inner Menu: contains the notifications -->
                            <ul class="menu">
								@foreach (auth()->user()->unreadNotifications as $notification) 
									@if($notification->type == 'App\Notifications\DueDateNotification')
										<li><!-- start notification -->
											<a href="{{URL::to('/mailbox/read/' .$notification->data['member_number'])}}">
												<i class="fa fa-users text-aqua"></i> Jatuh Tempo No. Anggota : {{ $notification->data['member_number'] }}
											</a>
										</li><!-- end notification -->
									@endif
									@if($notification->type == 'App\Notifications\SurveyPlan')
										<li><!-- start notification -->
											<a href="{{URL::to('/customer/survey/plan/' .$notification->data['reg_number'])}}">
												<i class="fa fa-users text-aqua"></i> Survey REG No : {{ $notification->data['reg_number'] }}
											</a>
										</li><!-- end notification -->
									@endif
									@if($notification->type == 'App\Notifications\CorruptNotfication')
										<li><!-- start notification -->
											<a href="{{URL::to('/mailbox/read/' .$notification->data['member_number'])}}">
												<i class="fa fa-users text-aqua"></i> Kredit Macet No. Anggota : {{ $notification->data['member_number'] }}
											</a>
										</li><!-- end notification -->
									@endif
								@endforeach
                            </ul>
                        </li>
                        <li class="footer"><a href="{{URL::to('/mailbox')}}">View all</a></li>
                    </ul>
                </li>
                <!-- Tasks Menu -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <!--img alt="" src="{{asset('img/flags')}}"-->
                        <span class="username">{{ LaravelLocalization::getCurrentLocaleName() }}</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            @if ($localeCode != App::getLocale())
                            <li>
                                <a href="{{LaravelLocalization::getLocalizedURL($localeCode) }}" hreflang="{{$localeCode}}"><img alt="" src="{{asset('img/flags')}}/{{$localeCode}}.png" width="22px">{{{ $properties['native'] }}}</a>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{ asset('uploads/photo/' .auth()->user()->avatar) }}" class="user-image" alt="Avatar"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ auth()->user()->username }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ asset('uploads/photo/' .auth()->user()->avatar) }}" class="img-circle" alt="User Image" />
                            <p>
                                {{ auth()->user()->name }}
                                <small>Member since {{ auth()->user()->created_at }}</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{URL::to('/employee/profile/' .auth()->user()->name)}}" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
