<?php

namespace VMorozov\LaravelLogTraces;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Log\Context\Repository;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VMorozov\LaravelLogTraces\Middleware\ContinueTraceMiddleware;
use VMorozov\LaravelLogTraces\Tracing\RandomIdGenerator;
use VMorozov\LaravelLogTraces\Tracing\TraceStorage;

class LogTracesServiceProvider extends PackageServiceProvider
{
    public const CONFIG_KEY = 'laravel-log-traces';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(self::CONFIG_KEY)
            ->hasConfigFile(self::CONFIG_KEY)
            ->hasCommands([
            ]);
    }

    public function packageRegistered(): void
    {
        $this->initTracing();
        $this->registerConsoleEventListeners();
    }

    public function packageBooted()
    {

    }

    private function registerConsoleEventListeners(): void
    {
        Event::listen(CommandStarting::class, function (CommandStarting $event) {
            if ($this->shouldLogCommand($event->command)) {
                Log::log(
                    $this->consoleCommandsLogLevel(),
                    'Command started',
                    [
                        'signature' => $event->command,
                    ],
                );
            }
        });

        Event::listen(CommandFinished::class, function (CommandFinished $event) {
            if ($this->shouldLogCommand($event->command)) {
                Log::log(
                    $this->consoleCommandsLogLevel(),
                    'Command ended',
                    [
                        'signature' => $event->command,
                    ],
                );
            }
        });
    }

    private function initTracing(): void
    {
        $this->app->singleton(TraceStorage::class, function () {
            return new TraceStorage($this->app->make(RandomIdGenerator::class));
        });
        $this->app->make(TraceStorage::class);

        /** @var Kernel $kernel */
        $kernel = $this->app->make(Kernel::class);
        $kernel->prependMiddleware(ContinueTraceMiddleware::class);

        Context::hydrated(function (Repository $context) {
            $traceStorage = $this->app->make(TraceStorage::class);
            $traceStorage->startNewSpan();
        });
    }

    private function shouldLogCommand(?string $command): bool
    {
        if (!$this->consoleCommandsLogEnabled()) {
            return false;
        }

        if (!$command) {
            return true;
        }

        $skipCommands = config(self::CONFIG_KEY . '.commands.skip_commands', []);

        return !in_array($command, $skipCommands);
    }

    private function consoleCommandsLogEnabled(): bool
    {
        return config(self::CONFIG_KEY . '.commands.enabled', true);
    }

    private function consoleCommandsLogLevel(): string
    {
        return config(self::CONFIG_KEY . '.commands.log_level', 'debug');
    }
}
