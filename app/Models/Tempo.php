<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tempo extends Model
{
    use HasFactory;

    protected $fillable = [
            'cust_id',
			'tempo_date',
			'member_number',
			'inst_to',
			'branch',
			'amount',
			'rates',
			'rate_count',
			'total_amount',
			'status',
			'created_by',
			'keterangan',
			'is_paid'
    ];
}
