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
                       <div id="all-chat-message"> 

                       </div> 
                    </ul>
                  
                </div>
               
            </div>
        </div>
    </div>
</div>
@endsection