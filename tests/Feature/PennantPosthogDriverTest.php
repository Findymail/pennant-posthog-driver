<?php

namespace Tests\Feature;

use Findymail\PennantPosthogDriver\PosthogProxy;
use Illuminate\Support\Facades\Config;
use Laravel\Pennant\Feature;
use Mockery;
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
        $mock = $this->mock(PosthogProxy::class);
        $mock->shouldReceive('isFeatureEnabled')
            ->once()
            ->with('test-feature-flag', '')
            ->andReturn(true);

        // process
        $result = Feature::active('test-feature-flag', '');

        // test
        $this->assertTrue($result);

        Mockery::close();
    }

    /** @test */
    public function should_return_false_when_posthog_feature_flag_is_disabled(): void
    {
        // prepare
        $mock = $this->mock(PosthogProxy::class);
        $mock->shouldReceive('isFeatureEnabled')
            ->once()
            ->with('test-feature-flag', '')
            ->andReturnNull();

        // process
        $result = Feature::active('test-feature-flag', '');

        // test
        $this->assertFalse($result);

        Mockery::close();
    }

    /** @test */
    public function should_call_only_once_posthog_API_even_if_called_twice(): void
    {
        // prepare
        $mock = $this->mock(PosthogProxy::class);
        $mock->shouldReceive('isFeatureEnabled')
            ->once()
            ->with('test-feature-flag', '')
            ->andReturn(true);

        // process
        $result1 = Feature::active('test-feature-flag', '');
        $result2 = Feature::active('test-feature-flag', '');

        // tests
        $this->assertTrue($result1);
        $this->assertTrue($result2);

        Mockery::close();
    }

    /** @test */
    public function should_call_allAreActiveProperly(): void
    {
        // prepare
        $mock = $this->mock(PosthogProxy::class);
        $mock->shouldReceive('isFeatureEnabled')
            ->once()
            ->with('test-feature-flag1', '')
            ->andReturn(true);

        $mock->shouldReceive('isFeatureEnabled')
            ->once()
            ->with('test-feature-flag2', '')
            ->andReturn(true);


        // process
        $result = Feature::allAreActive(['test-feature-flag1', 'test-feature-flag2']);

        // tests
        $this->assertTrue($result);

    }

    /** @test */
    public function it_should_able_to_use_define_method_callable_true(): void
    {
        // prepare
        Feature::define('defined-ff', fn () => true);

        // process
        $result = Feature::active('defined-ff');

        // tests
        $this->assertTrue($result);
    }

    /** @test */
    public function it_should_able_to_use_define_method_callable_false(): void
    {
        // prepare
        Feature::define('defined-ff', fn () => false);

        // process
        $result = Feature::active('defined-ff');

        // tests
        $this->assertFalse($result);
    }

    /** @test */
    public function it_should_able_to_return_defined_value(): void
    {
        // prepare
        Feature::define('defined-ff', 'theValue');

        // process
        $result = Feature::active('defined-ff');

        // tests
        $this->assertEquals('theValue', $result);
    }

    /** @test */
    public function it_should_not_call_posthog_driver_when_feature_flag_is_defined()
    {
        // prepare
        $mock = $this->mock(PosthogProxy::class);
        $mock->shouldReceive('isFeatureEnabled')->never();

        Feature::define('defined-ff', fn () => true);

        // process
        $result = Feature::active('defined-ff');

        // tests
        $this->assertTrue($result);
    }
}
