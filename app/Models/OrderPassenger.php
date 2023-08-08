<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OrderPassenger extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }

    function order()
    {
        return $this->belongsTo(Order::class);
    }

    function scopeFilter(Builder $query, $schedule_id,$from_station_ids,$to_station_ids){
        return $query->whereHas('order',function ($query) use ($schedule_id,$from_station_ids,$to_station_ids){
            $query->where('schedule_id', $schedule_id)
                ->whereIn('from_station_id',$from_station_ids)
                ->whereIn('to_station_id',$to_station_ids);
        })->where('status',1);
    }
}
