<?php

namespace Findymail\PennantPosthogDriver\Driver;

use Illuminate\Support\Collection;
use Laravel\Pennant\Contracts\Driver;
use PostHog\PostHog;

class PostHogDriver implements Driver
{
    /**
     * @param  array<string, bool>  $localState
     */
    public function __construct(private array $localState) {}

    public function define(string $feature, callable $resolver): void {}

    public function defined(): array
    {
        return [];
    }

    /**
     * @param  array<string, array<int, mixed>>  $features
     */
    public function getAll(array $features): array
    {
        return Collection::make($features)
            ->map(
                fn ($scopes, $feature) => Collection::make($scopes)
                    ->map(fn ($scope) => $this->get($feature, $scope))
                    ->all()
            )->toArray();
    }

    public function get(string $feature, mixed $scope): mixed
    {
        if (isset($this->localState[$feature])) {
            return $this->localState[$feature];
        }

        $isEnabled = PostHog::isFeatureEnabled($feature, '') === true;

        if (!isset($this->localState[$feature])) {
            $this->localState[$feature] = (bool) $isEnabled;
        }

        return $isEnabled;
    }

    public function set(string $feature, mixed $scope, mixed $value): void
    {
        // TODO: Implement set() method.
    }

    public function setForAllScopes(string $feature, mixed $value): void
    {
        // TODO: Implement setForAllScopes() method.
    }

    public function delete(string $feature, mixed $scope): void
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param  array<string>  $features
     */
    public function purge(?array $features): void
    {
        // TODO: Implement purge() method.
    }
}
