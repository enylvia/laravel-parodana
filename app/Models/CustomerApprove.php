<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerApprove extends Model
{
    use HasFactory;
	protected $table = 'customer_approve';
	
	public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
	
}
