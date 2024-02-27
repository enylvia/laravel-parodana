@extends('layouts.app')

@section('content')
<?php
$recever=Route::input('id');	
$id=Auth::id();
$user = DB::table('users')->where('id',$recever)->first();
?>
<link href="{{ asset('public/css/style.css') }}" rel="stylesheet">
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
            
                <div class="panel-heading" style="height:40px;">
                  <div class="pull-left">
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
                    <div class="pull-right"> 
                        {{$user->name}}
                    </div>
                </div>
            

                <div class="panel-body" id="scrolltoheight">
                <ul class="chat">
                       <div id="chat-message"> 
                       </div>

                </ul>
                
                </div>
               <div class="typing"><p id="typing"></p></div> 
                <div class="panel-footer">
                <form id="message-submit" action="{{URL::to('/send-message')}}" method="post"> 
                   <div class="input-group">
             
                    <input type="hidden" name="_token"id="token-message"value="{{csrf_token()}}">
                        <input onkeyup="typing()" id="message" type="text"name="message"required class="form-control input-sm" placeholder="Type your message here..." />
                        <span class="input-group-btn">
                            <input   type="submit" class="btn btn-warning btn-sm" id="btn-chat"value="Send"/>
                              
                        </span>
                    </div>
              
                
                </form>
                  
                </div>
            </div>
        </div>
    </div>
</div>

<script> 
 
 setInterval(ajaxCall,1000); 
 setInterval(typinc_receve,1000); 
 function ajaxCall() {
    
    var oldMessage=$("#chat-message li").length;
    var oldscrollHeight = $("#scrolltoheight").prop("scrollHeight");
     $.ajax({
         type:'get',
         url:'{{URL::to('/chat')}}/'+<?php echo $recever;?>,
         datatype:'html',
         success:function(response){
                $('#chat-message').html(response);
                var newscrollHeight = $("#scrolltoheight").prop("scrollHeight"); //Scroll height after the request
                var newMessage=$("#chat-message li").length;
                if(newscrollHeight > oldscrollHeight){
					$("#scrolltoheight").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                }
              
            }
         });
 }
 function typinc_receve() {
     $.ajax({
         type:'get',
         url:'{{URL::to('/typing-receve')}}/'+<?php echo $recever;?>,
         datatype:'html',
         success:function(response){
                if(response==0){
                  $('#typing').hide('slow');
                }else{
                    $('#typing').show();
                    $('#typing').html(response);
                }
               
              
            }
         });
 }
 function typing() {
    var text=$('#message').val();
    var token=$('#token-message').val();
   
     $.ajax({
         type:'post',
         url:'{{URL::to('/typing')}}',
         datatype:'html',
         success:function(response){
               console.log(response); 
            },
         data:{
                 text:text,
                 recever:<?php echo $recever;?>,
                 _token:token,
                 
             }
         });
 }
function deleteMessage(id){
    $('#'+id).hide();
    var sender=id;
    $.ajax({
        type:'get',
        url:'{{URL::to('/deletemessage')}}/'+sender,
        datatype:'html'
        });
}
 $('#message-submit').on('submit',function(e){
    $('#message').focus();
 e.preventDefault();
 var message=$('#message').val();
 var token=$('#token-message').val();
     $.ajax({
             type:'post',
             url:'{{URL::to('/send-message')}}',
             data:{
                 message:message,
                 recever:<?php echo $recever;?>,
                 sender:<?php echo $id;?>,
                 _token:token,
                 
             }
         
             });
             document.getElementById('message-submit').reset();
           
 });
   </script>
   

@endsection