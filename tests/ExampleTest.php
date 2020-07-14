<?php

namespace Larafast\Fastapi\Tests;

use Larafast\Fastapi\FastapiServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [FastapiServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}