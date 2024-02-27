<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'provinsi';
    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function kabupaten()
    {
        return $this->hasMany(Kabupaten::class);
    }
}
