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

    public function getLocation($ip = null, $land = null)
    {
        if ($ip == null) {
            $ip = getIp::get();
        }

        if ($ip == "UNKNOWN") {
            return (object)['isgermany' => 0];
        }

        if ($land == null) {
            $land = config('geolocation.language');
        }

        if (!in_array($land, ['de', 'en'])) {
            throw new Exception("The localy " . $land . " is not available.");
        }

        $key = md5(config('geolocation.cache_key') . '-' . $land .  '-' . $ip);

        if (Cache::has($key)) {
            $record = Cache::get($key);
            return json_decode($record);
        }

        $reader = new Reader(base_path() . '/files/geoip/GeoIP2-City.mmdb');
        $record = $reader->city($ip);

        if (!isset($record->postal)) {
            throw new Exception("For IP: " . $ip . " can't find any records.");
        }

        $city = (isset($record->city->names[$land])) ? $record->city->names[$land] : $record->city->names['en'];
        $region = (isset($record->mostSpecificSubdivision->names[$land])) ? $record->mostSpecificSubdivision->names[$land] : $record->mostSpecificSubdivision->names['en'];
        $country = (isset($record->country->names[$land])) ? $record->country->names[$land] : $record->country->names['en'];

        $this->record = (object)[
            'postcode' => $record->postal->code,
            'city' => $city,
            'region' => $region,
            'country' => $country,
            'country_iso' => $record->country->isoCode,
            'lat' => $record->location->latitude,
            'lng' => $record->location->longitude,
            'isgermany' => ($record->country->isoCode == "DE") ? 1 : 0,
            'ip' => $ip
        ];

        Cache::put($key, json_encode($this->record), 365 * 24 * 60 * 60);
        return $this->record;
    }

    public function get($ip = null, $lang = null)
    {
        if ($this->record == null) {
            return $this->getLocation($ip, $lang);
        }
        return $this->record;
    }

}
