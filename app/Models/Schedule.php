<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    function train()
    {
        return $this->belongsTo(Train::class);
    }

    function orders(){
        return $this->hasMany(Order::class);
    }
}
