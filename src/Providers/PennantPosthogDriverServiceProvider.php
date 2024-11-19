<?php

declare(strict_types=1);

namespace Findymail\PennantPosthogDriver\Providers;

use Findymail\PennantPosthogDriver\Driver\PostHogDriver;
use Findymail\PennantPosthogDriver\PosthogProxy;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;
use PostHog\PostHog;

class PennantPosthogDriverServiceProvider extends ServiceProvider
{
    public const API_CONFIG_KEY = "posthog.api_key";
    public const HOST_CONFIG_KEY = "posthog.host";

    public function boot(): void
    {
        Feature::extend('posthog', function (Application $app) {
            return $app->make(PostHogDriver::class);
        });
    }

    public function register(): void
    {
        $this->app->singleton(PostHogDriver::class, function (Application $app) {
            $postHogProxy = $app->make(PosthogProxy::class);

            return new PostHogDriver([], $postHogProxy);
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/posthog.php', 'posthog'
        );
    }
}
