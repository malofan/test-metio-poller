<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function aliases()
    {
        return $this->hasMany(CityAlias::class);
    }
}
