<?php

namespace Awescode\geoLocation\Facades;

use Illuminate\Support\Facades\Facade;

class geoLocation extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'geolocation';
    }
}
