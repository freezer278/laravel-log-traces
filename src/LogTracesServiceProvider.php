<?php

namespace VMorozov\LaravelLogTraces;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Log\Context\Repository;
use Illuminate\Support\Facades\Context;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vmorozov\LaravelLogTraces\Middleware\ContinueTraceMiddleware;
use Vmorozov\LaravelLogTraces\Tracing\RandomIdGenerator;
use Vmorozov\LaravelLogTraces\Tracing\TraceStorage;

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
    }

    public function packageBooted()
    {
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
}
