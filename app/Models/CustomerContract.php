<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerContract extends Model
{
    use HasFactory;
    protected $table = 'customer_contract';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function contractNumber($tanggalPengajuan, $kodePerusahaan)
    {
        $kd = $this->kontrak();
        $date = $tanggalPengajuan->format('Y-m-d');
        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        return $kodePerusahaan . $kd . $bulan . $tahun;
    }

    public  function kontrak()
	{
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomBayar = '';
		for ($i = 0; $i < 8; $i++) {
			$randomBayar .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomBayar;
	}
}
