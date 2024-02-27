<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Company;
use App\Models\Installment;
use App\Models\Customer;
use App\Models\Mailbox;
use Notification;
use App\Notifications\CorruptNotfication;
use Carbon\Carbon;

class InstallmentCorrupt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installment:corrupt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nasabah Kredit Macet';

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
		//$date = \Carbon\Carbon::now();
		//$lastMonth =  $date->subMonth()->format('m')
		$installments = Installment::where(
		'due_date', '<', Carbon::now())
		->where('status','=', 'UNPAID')
		->where('amount',0)
		->get();
		//->each(function ($workpermit) {
		//	DB::table('work_permit_notifications')->insert($workPermit->id);
		//});
		foreach($installments as $installment)
		{
			$date = date('F Y', strtotime($installment->due_date));
			$memberNumber = $installment->member_number;
			$customers = Customer::where('member_number',$memberNumber)->first();
			$branch = $customers->branch;
			//echo "Kredit Macet Bulan $date";	
			//$this->info('Kredit Macet a.n Nasabah : ' .$customers->name. ' Bulan : '  .$date );
			$subject = 'Kredit Macet No. Nasabah : ' .$memberNumber;
			$body = 'Kredit Macet a.n Nasabah : ' .$customers->name. ' Bulan : '  .$date;
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
			
		}
				
		$users = User::whereHas('companies', function ($query) {
			return $query->where('companies.id', '=', 1);
		})->get();		
			
		//$users = User::whereHas('roles', function ($q) {
		//	$q->whereIn('name', ['superadmin','owner', 'manager']);
		//})->get();		
		
		Notification::send($users, new CorruptNotfication($customers));
				
    }
}
