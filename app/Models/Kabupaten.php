<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'kabupaten';
    public $timestamps = false;
    protected $with = ['provinsi'];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class);
    }
}
