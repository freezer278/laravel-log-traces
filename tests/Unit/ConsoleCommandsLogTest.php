<?php

namespace VMorozov\LaravelLogTraces\Tests\Unit;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Mockery;
use VMorozov\LaravelLogTraces\Tests\UnitTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class ConsoleCommandsLogTest extends UnitTestCase
{
    public function test_it_logs_commands_when_enabled(): void
    {
        Log::shouldReceive('log')
            ->twice()
            ->with(
                'debug',
                Mockery::pattern('/Command (started|ended)/'),
                Mockery::type('array'),
            );

        Event::dispatch(new CommandStarting('test:command', new ArrayInput([]), new NullOutput()));
        Event::dispatch(new CommandFinished('test:command', new ArrayInput([]), new NullOutput(), 0));
    }

    public function test_it_skips_logging_for_configured_commands(): void
    {
        config(['laravel-log-traces.commands.skip_commands' => ['test:skipped']]);

        Log::shouldReceive('log')->never();

        Event::dispatch(new CommandStarting('test:skipped', new ArrayInput([]), new NullOutput()));
        Event::dispatch(new CommandFinished('test:skipped', new ArrayInput([]), new NullOutput(), 0));
    }

    public function test_it_skips_logging_for_schedule_run_by_default(): void
    {
        Log::shouldReceive('log')->never();

        Event::dispatch(new CommandStarting('schedule:run', new ArrayInput([]), new NullOutput()));
        Event::dispatch(new CommandFinished('schedule:run', new ArrayInput([]), new NullOutput(), 0));
    }
}
