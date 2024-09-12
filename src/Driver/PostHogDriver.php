<?php

namespace Findymail\PennantPosthogDriver\Driver;

use Findymail\PennantPosthogDriver\PosthogProxy;
use Illuminate\Support\Collection;
use Laravel\Pennant\Contracts\Driver;
use PostHog\PostHog;
use Throwable;

class PostHogDriver implements Driver
{

    /**
     * @var array <string, string|callable>
     */
    private array $featureStateResolvers = [];

    /**
     * @param  array<string, bool>  $localState
     */
    public function __construct(private array $localState, private readonly PosthogProxy $posthogProxy) {}

    public function define(string $feature, mixed $resolver): void
    {
        $this->featureStateResolvers[$feature] = $resolver;
    }

    public function defined(): array
    {
        return array_keys($this->featureStateResolvers);
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

    public function get(string $feature, mixed $scope = null): mixed
    {
        if (isset($this->featureStateResolvers[$feature])) {
            return is_callable($this->featureStateResolvers[$feature])
                ? $this->featureStateResolvers[$feature]($scope)
                : $this->featureStateResolvers[$feature];
        }

        if (isset($this->localState[$feature])) {
            return $this->localState[$feature];
        }

        try {
            $isEnabled = $this->posthogProxy->isFeatureEnabled($feature, $scope ?? '') === true;
        } catch (Throwable) {
            return false;
        }

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
