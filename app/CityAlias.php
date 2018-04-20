<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CityAlias extends Model
{
    public $timestamps = false;

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
