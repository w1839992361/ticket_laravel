<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    function from_station()
    {
        return $this->belongsTo(Station::class, 'from_station_id', 'id');
    }

    function to_station()
    {
        return $this->belongsTo(Station::class, 'to_station_id', 'id');
    }

    function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }


    function passengers()
    {
        return $this->belongsToMany(Passenger::class,'order_passengers');
    }

    function orderPassengers()
    {
        return $this->hasMany(OrderPassenger::class);
    }

}
