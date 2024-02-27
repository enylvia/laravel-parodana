<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentHandover extends Model
{
    use HasFactory;
	protected $table = 'handover_document';
	// kolom tabel untuk Mass Assingment
    protected $fillable = ['reg_number', 'company_id', 'berkas', 'status', 'keterangan','created_by'];
}
