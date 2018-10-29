<?php

namespace Awescode\geoLocation;

use Awescode\geoLocation\Contracts\geoLocation as geoLocationContract;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class geoLocation implements geoLocationContract
{
    /** @var Model */
    protected $model;

    public function __construct()
    {
        $modelClassName = config('geolocation.model');

        $this->model = new $modelClassName;
    }

    public function lowerStr(string $str): string
    {
        return strtolower($str);
    }

    public function count(): int
    {
        return $this->model
            ->count();
    }
}
