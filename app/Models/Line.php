<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    use HasFactory;

    function from_city()
    {
        return $this->hasOneThrough(City::class, Station::class, 'id', 'id', 'from_station_id', 'city_id');
    }

    function to_city(){
        return $this->hasOneThrough(City::class,Station::class,'id','id','to_station_id','city_id');
    }

    function from_station(){
        return $this->belongsTo(Station::class,'from_station_id','id');
    }

    function to_station(){
        return $this->belongsTo(Station::class,'to_station_id','id');
    }
}
