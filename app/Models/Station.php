<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    function city()
    {
        return $this->belongsTo(City::class);
    }
}
