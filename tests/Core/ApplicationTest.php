<?php

namespace Otrium\Tests\Core;

use Mockery as m;
use Otrium\Core\Application;
use PHPUnit\Framework\TestCase;
use Otrium\Core\ServiceProvider;
use Otrium\Tests\Fixtures\AbstractClass;
use Otrium\Tests\Fixtures\ConcreteClass;
use Otrium\Tests\Fixtures\ApplicationBasicServiceProviderStub;

class ApplicationTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testServiceProvidersAreCorrectlyRegistered()
    {
        $provider = m::mock(ApplicationBasicServiceProviderStub::class);
        $class = get_class($provider);
        $provider->shouldReceive('register')->once();
        $app = new Application('./');
        $app->register($provider);

        $this->assertArrayHasKey($class, $app->getLoadedProviders());
    }

    public function testClassesAreBoundWhenServiceProviderIsRegistered()
    {
        $app = new Application('./');
        $app->register($provider = new class($app) extends ServiceProvider {
            public function register(): void
            {
                $this->app->bind(AbstractClass::class, ConcreteClass::class);
            }
        });

        $this->assertArrayHasKey(get_class($provider), $app->getLoadedProviders());

        $instance = $app->make(AbstractClass::class);

        $this->assertInstanceOf(ConcreteClass::class, $instance);
        $this->assertNotSame($instance, $app->make(AbstractClass::class));
    }

    public function testServiceProvidersAreCorrectlyRegisteredWhenRegisterMethodIsNotFilled()
    {
        $provider = m::mock(ServiceProvider::class);
        $class = get_class($provider);
        $provider->shouldReceive('register')->once();
        $app = new Application('./');
        $app->register($provider);

        $this->assertArrayHasKey($class, $app->getLoadedProviders());
    }

    public function testServiceProvidersCouldBeLoaded()
    {
        $provider = m::mock(ServiceProvider::class);
        $class = get_class($provider);
        $provider->shouldReceive('register')->once();
        $app = new Application('./');
        $app->register($provider);

        $this->assertTrue($app->providerIsLoaded($class));
        $this->assertFalse($app->providerIsLoaded(ApplicationBasicServiceProviderStub::class));
    }

    public function testAccessConfiguratoins()
    {
        $app = new Application(__DIR__ . '/../Fixtures');

        $this->assertEquals('bar', $app->config('app.foo'));
    }

    public function testDefaultApplicationEnvFile()
    {
        $app = new Application('.');

        $this->assertEquals('.env', $app->environmentFile());
    }

    public function testDetermineBootStatus()
    {
        $app = new Application('.');

        $this->assertTrue($app->isBooted());
    }

    public function testGetApplicationBasePath()
    {
        $app = new Application('./');

        $this->assertEquals('.', $app->basePath());
    }
}
