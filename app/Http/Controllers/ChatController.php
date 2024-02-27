<?php

namespace App\Http\Controllers;

use App\Chat;
use App\typing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use URL;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function store(Request $request){
        $chat = new Chat;
        $chat->message = $request->message;
        $chat->sender = $request->sender;
        $chat->recever = $request->recever;
        $chat->save();
        typing::where('recever',$chat->recever)
              ->where('sender', $chat->sender)
              ->update(['check_status' => 0]);
        return back();
    }
    function callmessage($id){
        $user = DB::table('users')->where('id',$id)->first();
       $user->name;
        $auth_id=Auth::id();
        $chats = chat::where('sender',$auth_id)
                     ->where('recever',$id)
                     ->Orwhere('sender',$id)
                     ->where('recever',$auth_id)
                     ->get();
        foreach($chats as $chat){
            if($chat->sender != $auth_id){
echo '<li class="left clearfix"><span class="chat-img pull-left">
<img src="http://placehold.it/50/55C1E7/fff&text='. mb_substr($user->name , 0, 1) .' " alt="User Avatar" class="img-circle" />
</span>
<div class="chat-body clearfix">
    <div class="header">
        <strong class="primary-font">'.$user->name  . '</strong> <small class="pull-right text-muted">
            <span class="glyphicon glyphicon-time"></span>' . $chat->created_at->diffForHumans() .'</small>
    </div>
    <div class="alert alert-success ">
    '. $chat->message .' </div>
                  </div>
                  </li>';
            }else{
                echo '<li id="'.$chat->id.'" class="right clearfix"><span class="chat-img pull-right">
                <img src="http://placehold.it/50/FA6F57/fff&text='. mb_substr(Auth::user()->name , 0, 1) .'" alt="User Avatar" class="img-circle" />
            </span>
                <div class="chat-body clearfix">
                    <div class="header">
                        <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>'. $chat->created_at->diffForHumans() .'</small>
                        <strong class="pull-right primary-font">'.Auth::user()->name  .'</strong>
                    </div>
                    <div class="alert alert-info ">
  <span onclick="deleteMessage('.$chat->id.')" class="close"  aria-label="close">&times;</span>'. $chat->message .' </div>
                </div>
                </li>';
            }
        };
    }
    public function soundCheck(){
        $auth_id=Auth::id();
        $chats = chat::where('recever',$auth_id)
        ->get()
        ->count();
        print_r($chats );
    }
    public function seenMessage(){
        $auth_id=Auth::id();
        $chats = chat::where('recever',$auth_id)
        ->where('is_seen',1)
        ->get()
        ->count();     
        print_r($chats );
    }
    public function seenUpdate(){
        $auth_id=Auth::id();
        $chats = chat::where('recever',$auth_id)
        ->where('is_seen',1)
        ->update(['is_seen' => 0]);
        
    }
    public function singleSeenUpdate($id){
        $auth_id=Auth::id();
        chat::where('recever',$auth_id)
                ->where('is_user_seen',1)
                ->where('sender',$id)
                ->update(['is_user_seen' => 0]);
        chat::where('recever',$auth_id)
                ->where('is_seen',1)
                ->where('sender',$id)
                ->update(['is_seen' => 0]);
    }
    public function typing(Request $request){
        $auth_id=Auth::id();
       echo $id= $request->recever;
       echo $text= $request->text;
        chat::where('recever',$auth_id)
                ->where('is_user_seen',1)
                ->where('sender',$id)
                ->update(['is_user_seen' => 0]);
        chat::where('recever',$auth_id)
                ->where('is_seen',1)
                ->where('sender',$id)
                ->update(['is_seen' => 0]);
        $typing_check = DB::table('typings')->where('recever',$id)
                                  ->where('sender',$auth_id)
                                  ->first();
        if($typing_check){
            DB::table('typings')->where('recever',$id)
                ->where('sender',$auth_id)
                ->update(['check_status' => $request->text]);
        }else{
            $typing = new typing;
            $typing->recever = $id;
            $typing->sender = Auth::id();
           
            $typing->save();
        }
    }
    public function deletemessage($id){
        DB::table('chats')->where('id',$id)
                          ->delete();
      
    }
   public function typinc_receve($id){
        $typing_receve= DB::table('typings')->where('recever',Auth::id())
                            ->where('sender',$id)
                            ->first();
           if(isset( $typing_receve)){
               return  $typing_receve->check_status;
           }            

   }
    public function allMessageView(){
        $url=URL::to('/message/');
        $users = DB::table('users')->get();
       
        foreach($users as $user){
            if(Auth::id()!=$user->id){
                
                $message = DB::table('chats')->where('recever',Auth::id())
                                             ->where('sender',$user->id)
                                             ->orderBy('id','desc')
                                             ->first();
                $msgcount = DB::table('chats')->where('recever',Auth::id())
                                              ->where('sender',$user->id)
                                              ->where('is_user_seen',1)
                                              ->get()
                                              ->count();
                                             
                if($msgcount>0){
                    $msg="(". $msgcount  .")";
                    $start_b='<b>';
                    $end_b='</b>';
                }else{
                    $msg="";
                    $start_b='';
                    $end_b='';
                }
              if(isset($message)){
           $srtmessage=Str::limit($message->message, 40);
                echo '
                <a onclick="singleSeenUpdate('.$user->id.')" href="'.$url.'/'.$user->id.'"> 
                <li class="left clearfix">
                        <span class="chat-img pull-left">
                        <img alt="User Avatar" class="img-circle" src="http://placehold.it/25/55C1E7/fff&amp;text=U"></span>
                        <div class="chat-body clearfix">
                            <div class="header">
                             <strong class="primary-font">' . $user->name . $msg .'</strong>
                             <p style="color:black">
                             
                            '. $start_b . $srtmessage .$end_b.'
                              
                             </p>
                            </div>
                        
                        </div>
                    </li>                   
                </a>
                
                
                
                
                ';
              }
          
            }
        }
    
    }
}
