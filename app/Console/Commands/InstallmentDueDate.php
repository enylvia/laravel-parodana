<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Company;
use App\Models\Installment;
use App\Models\Customer;
use App\Models\Mailbox;
use Notification;
use App\Notifications\DueDateNotification;

class InstallmentDueDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installment:duedate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Angsuran Jatuh Tempo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //return 0;
		//$users = User::with('companies')->where('id',auth()->user()->id)->get();
		//foreach($users as $user)
		//{
		//	foreach($user->companies as $company)
		//	{
		//		$companyID = $company->id;
		//	}
		//}
		$installments = Installment::where('due_date', now()->addDays(7))		
		->get();
		//->each(function ($workpermit) {
		//	DB::table('work_permit_notifications')->insert($workPermit->id);
		//});
		foreach($installments as $installment)
		{
			$dueDate = $installment->due_date;
			$memberNumber = $installment->member_number;
			$customers = Customer::where('member_number',$memberNumber)->first();
			$branch = $customers->branch;
			echo "Nasabah a.n $customers->name akan jatuh pada tanggal $dueDate";
			$subject = 'Jatuh Tempo No. Nasabah : ' .$memberNumber;
			$body = 'Nasabah a.n $customers->name akan jatuh pada tanggal $dueDate';
			//$sender = support@parodana-m.id;
			
			$mailbox = new Mailbox();
			$mailbox->subject = $subject;
			$mailbox->body = $body;
			//$mailbox->sender_id = $sender; 
			$mailbox->time_sent = now();
			$mailbox->read_at = NULL;
			$mailbox->branch = $branch;
			$mailbox->parent_id = 0;
			$mailbox->save();
			
			$users = User::whereHas('companies', function ($query) {
			//	$query->select('companies.id')->where('companies.id', '=', 1);
				return $query->where('companies.id', '=', $branch);
			})->get();
			
			Notification::send($users, new DueDateNotification($customer));
		}						
			
		//$users = User::whereHas('roles', function ($q) {
		//	$q->whereIn('name', ['superadmin','owner', 'manager']);
		//})->get();				
		
		//$this->info('Nasabah a.n : ' .$customers->name. ' akan jatuh tempo pada tanggal : '  .$dueDate .$branch .$users);
		
    }
}
