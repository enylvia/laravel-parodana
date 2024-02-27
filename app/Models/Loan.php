<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
	protected $table = 'loans';
	protected $primaryKey = 'id';
	protected $fillable = [
		'customer_id','loan_number','member_number','contract_number','contract_date','start_month',
		'loan_amount', 'time_period', 'pay_date','interest_rate',
		'pay_principal','pay_interest','pay_month','company_id','loan_remaining','total_principal','total_interest','status'
	];
	
	public function installment()
    {
        //return $this->hasMany(Installment::class, 'member_number', 'id');
        return $this->hasMany(Installment::class, 'loan_number', 'id');
    }
	
}
