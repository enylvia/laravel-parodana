<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Savings extends Model
{
    use HasFactory;
	protected $table = 'savings';
	protected $fillable = ['end_balance'];
	
	//public function getTotalBalance() {
	//	return $this->buyDetails->sum(function($buyDetail) {
	//	  return $buyDetail->quantity * $buyDetail->price;
	//	});
	//  }

	public function nomorTabungan() {
		$characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomOrder = '';
        for ($i = 0; $i < 10; $i++) {
            $randomOrder .= $characters[rand(0, $charactersLength - 1)];
        }
        return 'SVG' . $randomOrder;
	}
}
