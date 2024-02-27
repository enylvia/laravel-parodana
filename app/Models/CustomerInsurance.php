<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInsurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id','no_kontrak','duration','name_user','company','branch',
    ];

    // tablename 
    protected $table = 'customer_insurance';

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function nomorKontrak($customerID) {
        return 'ASR' . $customerID . now()->format('Ymd');
    }
}