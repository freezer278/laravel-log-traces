<?php

namespace VMorozov\LaravelLogTraces;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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

    }

    public function packageBooted()
    {
    }
}
