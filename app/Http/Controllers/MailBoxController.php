<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Notifications;
use App\Notifications\DueDateNotification;
use App\Notifications\CorruptNotification;
use App\Notifications\SurveyPlan;
use App\Models\Customer;
use App\Models\Installment;
use App\Models\Mailbox;
use Carbon\Carbon;
use DB;

class MailBoxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function inbox()
    {
		foreach (auth()->user()->Notifications as $notification) 
		{
			if($notification->type == 'App\Notifications\CorruptNotfication')								
			{
				$corruptNumber = $notification->data['member_number'];
			}
			if($notification->type == 'App\Notifications\DueDateNotification')								
			{
				$dueDateNumber = $notification->data['member_number'];
			}
			if($notification->type == 'App\Notifications\SurveyPlan')								
			{
				$regNumber = $notification->data['reg_number'];
			}
		}
				 
		$corrupts = Customer::where('member_number',$corruptNumber)->get();		
		$duedates = Customer::where('member_number',$dueDateNumber)->get();
		$surveys = Customer::where('reg_number',$regNumber)->get();
		
		$notifications = DB::table('notifications')->get();
		//$dataId = $notifications->data->id;
		//or $dataId = json_decode($notifications->data)->id;
		$mailboxs = Mailbox::all();
        return view('mailbox.inbox',compact('corrupts','duedates','surveys','notifications','mailboxs'));
    }
	
	public function read(Request $request, $id)
    {		
		$corruptNumber = $id;
		$dueDateNumber = $id;
		$regNumber = $id;
		
		foreach (auth()->user()->Notifications as $notification) 
		{			
			if($notification->type == 'App\Notifications\CorruptNotfication')								
			{
				$corruptNumber = $id;
				//$installments = Installment::where('member_number',$id)
				//->where('due_date', '<', Carbon::now())
				//->where('status','=', 'UNPAID')
				//->where('amount',0)
				//->get();
			}			
			
			if($notification->type == 'App\Notifications\DueDateNotification')								
			{
				$dueDateNumber = $id;
				//$duedates = Installment::where('member_number',$id)
				//->where('due_date', now()->addDays(7))		
				//->get();
			}
						
			if($notification->type == 'App\Notifications\SurveyPlan')								
			{
				$regNumber = $id;				
			}
			
		}
		
		$corrupts = Customer::where('member_number',$corruptNumber)->get();		
		$duedates = Customer::where('member_number',$dueDateNumber)->get();
		$surveys = Customer::where('reg_number',$regNumber)->get();
		//$notfications = Notifications::all();
        return view('mailbox.read',compact('corrupts','duedates'));
    }
	
	public function compose()
    {
        return view('mailbox.compose');
    }
	
	public function delete($id)
	{	
		//$ids = $id;		
		//MailBox::where('id',$id)->delete();
		DB::table("mailbox")->delete($id);
		return response()->json(['success'=>"Data Deleted successfully.", 'tr'=>'tr_'.$id]);
	}
	
	public function deleteAll(Request $request)
    {
        //$ids = $request->ids;
        //DB::table("notifactions")->whereIn('id',explode(",",$ids))->delete();
        //return response()->json(['success'=>"Data Deleted successfully."]);
		
		$ids = $request->ids;
        //MailBox::whereIn('id',explode(",",$ids))->delete();
		DB::table("mailbox")->whereIn('id',explode(",",$ids))->delete();
        return response()->json(['success'=>"Data Deleted successfully."]);
    }
	
}
