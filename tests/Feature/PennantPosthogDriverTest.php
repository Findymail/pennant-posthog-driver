<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Laravel\Pennant\FeatureManager;
use Mockery;
use PostHog\PostHog;
use Tests\TestCase;

class PennantPosthogDriverTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config::set('pennant.stores.posthog.driver', 'posthog');
        config::set('pennant.default', 'posthog');
    }

    /** @test */
    public function it_should_true_when_posthog_feature_flag_is_enable(): void
    {
        // prepare
        $featureManager = $this->app->make(FeatureManager::class);

        $mock = Mockery::mock('alias:' . PostHog::class);

        $mock->shouldReceive('isFeatureEnabled')
            ->once()
            ->with('test-feature-flag', '')
            ->andReturn(true);

        $mock->shouldReceive('init');

        // process
        $result = $featureManager->active('test-feature-flag', '');

        // test
        $this->assertTrue($result);
    }

    /** @test */
    public function should_return_false_when_posthog_feature_flag_is_disabled(): void
    {
        // prepare
        $featureManager = $this->app->make(FeatureManager::class);

        $mock = Mockery::mock('alias:' . PostHog::class);

        $mock->shouldReceive('isFeatureEnabled')
            ->once()
            ->with('test-feature-flag', '')
            ->andReturn(null);

        $mock->shouldReceive('init');

        // process
        $result = $featureManager->active('test-feature-flag', '');

        // test
        $this->assertFalse($result);
    }

    /** @test */
    public function should_call_only_once_posthog_API_even_if_called_twice(): void
    {
        // prepare
        $featureManager = $this->app->make(FeatureManager::class);

        $mock = Mockery::mock('alias:' . PostHog::class);

        $mock->shouldReceive('isFeatureEnabled')
            ->once()
            ->with('test-feature-flag', '')
            ->andReturn(true);

        $mock->shouldReceive('init');

        // process
        $result1 = $featureManager->active('test-feature-flag', '');
        $result2 = $featureManager->active('test-feature-flag', '');

        // tests
        $this->assertTrue($result1);
        $this->assertTrue($result2);
    }

    /** @test */
    public function should_call_allAreActiveProperly(): void
    {
        // prepare
        $featureManager = $this->app->make(FeatureManager::class);

        $mock = Mockery::mock('alias:' . PostHog::class);

        $mock->shouldReceive('isFeatureEnabled')
            ->once()
            ->with('test-feature-flag1', '')
            ->andReturn(true);

        $mock->shouldReceive('isFeatureEnabled')
            ->once()
            ->with('test-feature-flag2', '')
            ->andReturn(true);

        $mock->shouldReceive('init');

        // process
        $result = $featureManager->allAreActive(['test-feature-flag1', 'test-feature-flag2']);

        // tests
        $this->assertTrue($result);
    }
}
