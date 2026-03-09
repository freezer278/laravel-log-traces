<?php

namespace VMorozov\LaravelLogTraces\Tests;

use Mockery;
use Orchestra\Testbench\TestCase;
use VMorozov\LaravelLogTraces\LogTracesServiceProvider;

class UnitTestCase extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    protected function getPackageProviders($app)
    {
        return [
            LogTracesServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
