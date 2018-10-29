<?php

namespace Awescode\geoLocation\Models;

use Illuminate\Database\Eloquent\Model;

class geoLocation extends Model
{
    //protected $fillable = [];

    public function getTable()
    {
        return config('geolocation.table_name');
    }
}
