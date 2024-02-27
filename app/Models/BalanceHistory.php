<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceHistory extends Model
{
    use HasFactory;
    protected $table = 'balance_history';
    protected $fillable = [
        'account_number',
        'branch',
        'trx_date',
        'mutasi_debet',
        'mutasi_kredit',
    ];
}
