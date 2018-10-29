<?php

namespace Awescode\geoLocation\Tests;

use Awescode\geoLocation\Providers\geoLocationServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class AbstractTestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            geoLocationServiceProvider::class,
        ];
    }
}
