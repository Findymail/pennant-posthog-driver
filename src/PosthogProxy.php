<?php

namespace Findymail\PennantPosthogDriver;

use PostHog\PostHog;

class PosthogProxy
{
    public function isFeatureEnabled(
        string $key,
        string $distinctId,
    ): ?bool {
        return PostHog::isFeatureEnabled(
            $key,
            $distinctId,
        );
    }
}
