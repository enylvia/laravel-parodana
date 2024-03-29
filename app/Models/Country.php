<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'country';
    public $timestamps = false;

    public function provinsis()
    {
        return $this->hasMany(Provinsi::class);
    }
}
