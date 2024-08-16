<?php

declare(strict_types=1);

namespace Findymail\PennantPosthogDriver\Providers;

use Findymail\PennantPosthogDriver\Driver\PostHogDriver;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;

class PennantPosthogDriverServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Feature::extend('posthog', function (Application $app) {
            return $app->make(PostHogDriver::class);
        });
    }

    public function register(): void
    {
        $this->app->singleton(PostHogDriver::class, function (Application $app) {
            return new PostHogDriver([]);
        });
    }
}
