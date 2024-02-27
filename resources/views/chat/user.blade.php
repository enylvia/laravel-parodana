@extends('layouts.app')

@section('content')
<?php 
$users = DB::table('users')->get();
?>
<link href="{{ asset('public/css/style.css') }}" rel="stylesheet">

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">
               
               
                <a onclick="seenUpdate()" style="color:#fff;text-decoration:none; margin-left: 12px;" href="{{URL::to('/')}}"> 
                     <span class="glyphicon glyphicon-comment"> 
                     
                     
                     </span> <b class="smsnum"id="smsnum"></b> Message
                </a>
               
               
                <a style="color:#fff;text-decoration:none; margin-left: 12px;" href="{{URL::to('/users')}}"> 
                     <span class="glyphicon glyphicon-user"></span> User
                </a>
                </a>
                <a style="color:#fff; text-decoration:none;    margin-left: 12px;" href="{{URL::to('/')}}"> 
                     <span class="glyphicon glyphicon-search"></span> Search 
                </a>
                   
                    
                </div>
                <div class="panel-body">
                    
               
                    <ul class="chat">
                    @foreach($users as $user)
                     @if($user->id != Auth::id())
                        <a href="{{URL::to('/message/'.$user->id)}}"> 
                        <li class="left clearfix">
                                <span class="chat-img pull-left">
                                <img alt="User Avatar" class="img-circle" src="http://placehold.it/25/55C1E7/fff&amp;text=U"></span>
                                <div class="chat-body clearfix">
                                    <div class="header">
                                        <strong class="primary-font">{{$user->name}}</strong>
                                    </div>
                                </div>
                            </li>                   
                        </a>
                        @endif
                      @endforeach
                    </ul>
                  
                </div>
               
            </div>
        </div>
    </div>
</div>
@endsection