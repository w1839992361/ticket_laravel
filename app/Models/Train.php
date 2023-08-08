<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    function lines()
    {
        return $this->hasMany(Line::class);
    }

    function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    function orders()
    {
        return $this->hasManyThrough(Order::class, Schedule::class);
    }

    function scopeThrough($from_id, $to_id, $date)
    {
        $out_query = self::query();
            return $out_query->whereHas('lines.from_city', function ($query) use ($from_id) {
            $query->where('cities.id', $from_id);
        })->whereHas('lines.to_city', function ($query) use ($to_id) {
            $query->where('cities.id', $to_id);
        })->whereHas('schedules', function ($query) use ($date) {
            $query->where('departure_date', $date);
        })->with(['schedules' => function ($query) use ($date) {
            $query->where('departure_date', $date);
        }]);
    }



}
