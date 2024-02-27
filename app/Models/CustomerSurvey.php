<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSurvey extends Model
{
    use HasFactory;
	protected $table = 'customer_survey';
	
	public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
	
}
