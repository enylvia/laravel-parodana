<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class AccountGroup extends Model
{
    use HasFactory;
	protected $table = 'account_group';
	//protected $fillable = ['id','account_name','account_group'];
	// kolom tabel untuk Mass Assingment
    protected $fillable = ['id','account_name', 'account_number','header'];
    // relasi 1-N dengan Jurnal
    public function journal(){
        return $this->hasMany(Journal::class, 'account_id');
    }
    // Non-aktifkan Timestamp
    public $timestamps = false;
}
