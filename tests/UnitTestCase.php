<?php

namespace VMorozov\LaravelLogTraces\Tests;

use Mockery;
use Orchestra\Testbench\TestCase;

class UnitTestCase extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
