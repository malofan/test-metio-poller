<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Sensor extends Model
{
    protected $fillable = [
        'url',
    ];

    public $timestamps = false;

    public function sensorData()
    {
        return $this->hasMany(SensorData::class);
    }
}