<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDocument extends Model
{
    use HasFactory;
	protected $table = 'customer_document';
	
	public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
	
}
