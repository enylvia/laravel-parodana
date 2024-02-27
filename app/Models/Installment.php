<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;
	protected $table = 'installment';
	protected $fillable = ['loan_number','inst_to','member_number','pay_date','due_date',
				'pay_method','amount','late_charge','amount','status','branch'];				
	public function loan()
    {
        //return $this->belongsTo(Loan::class, 'member_number');
        return $this->belongsTo(Loan::class, 'loan_number');
    }
	
}
