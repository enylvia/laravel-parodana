<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;
	protected $table = 'journals';
	// Non-aktifkan Timestamp
    public $timestamps = false;

    // kolom tabel untuk Mass Assingment
    protected $fillable = ['description', 'transaction_date', 'nominal', 'tipe', 'account_id'];

    // kolom akan disembunyikan dalam array
    protected $hidden = [''];

    // Relasi N-1 antara akun dengan jurnal
    public function account(){
        return $this->belongsTo(AccountGroup::class, 'account_id');
    }
}
