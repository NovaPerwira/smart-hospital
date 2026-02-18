<?php

namespace App\Providers;

use App\Support\Filesystem\WindowsFriendlyFilesystem;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('files', fn() => new WindowsFriendlyFilesystem());
        $this->app->singleton(Filesystem::class, fn() => $this->app->make('files'));
    }

    public function boot(): void
    {
        //
    }
}
