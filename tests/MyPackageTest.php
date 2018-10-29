<?php

namespace Awescode\geoLocation\Tests;

use Awescode\geoLocation\geoLocation;
use InvalidArgumentException;

class geoLocationTest extends AbstractTestCase
{
    public function test_validate_method_lowerStr()
    {
		$this->assertEquals('some text', (new geoLocation)->lowerStr('Some Text'));
    }
}
