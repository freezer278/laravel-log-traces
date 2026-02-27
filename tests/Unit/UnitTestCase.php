<?php

namespace VMorozov\Prometheus\Tests\Unit;

use Mockery;

class UnitTestCase extends \Orchestra\Testbench\TestCase
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
