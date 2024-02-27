<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceAccount extends Model
{
    use HasFactory;
	protected $table = 'account_balance';

    protected $fillable = ['id','mutation_date','branch','transaction_type','amount','created_by','start_balance','end_balance','account_number'];
}
