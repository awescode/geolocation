<?php

namespace Awescode\geoLocation;

use Cache;
use Awescode\geoLocation\Contracts\geoLocation as geoLocationContract;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use GeoIp2\Database\Reader;

class geoLocation implements geoLocationContract
{
    /** @var Model */
    protected $model;
    protected $record = null;

    public function __construct()
    {
        $modelClassName = config('geolocation.model');

        $this->model = new $modelClassName;
    }

    public function getLocation($land = 'de')
    {
        $ip = getIp::get();

        if ($ip == "UNKNOWN") {
            return (object)['isgermany' => 0];
        }

        if (!in_array($land, ['de', 'en'])) {
            throw new Exception("The localy " . $land . " is not available.");
        }

        $key = md5(config('geolocation.cache_key') . '-' . $land .  '-' . $ip);

        if (Cache::has($key)) {
            $record = Cache::get($key);
            return json_decode($record);
        }

        $reader = new Reader(base_path() . '/GeoIP2-City.mmdb');
        $record = $reader->city($ip);

        if (!isset($record->postal)) {
            throw new Exception("For IP: " . $ip . " can't find any records.");
        }

        $this->record = (object)[
            'postcode' => $record->postal->code,
            'city' => $record->city->names[$land],
            'region' => $record->mostSpecificSubdivision->names[$land],
            'country' => $record->country->names[$land],
            'country_iso' => $record->country->isoCode,
            'lat' => $record->location->latitude,
            'lng' => $record->location->longitude,
            'isgermany' => ($record->country->isoCode == "DE") ? 1 : 0
        ];

        Cache::put($key, json_encode($this->record), 365 * 24 * 60 * 60);
        return $this->record;
    }

    public function get()
    {
        if ($this->record == null) {
            return $this->getLocation();
        }
        return $this->record;
    }

}
