<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    protected $fillable = [
        'sensor_id',
        'date',
        'city_id',
        'day_temperature',
        'night_temperature',
        'day_humidity',
        'night_humidity',
    ];

    public $timestamps = false;

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
