<?php

namespace Otrium\Core;

use Illuminate\Container\Container;
use Otrium\Files\FileServiceProvider;
use Otrium\Logger\LogServiceProvider;
use Otrium\Core\Bootstrap\LoadEnvironment;
use Otrium\Core\Bootstrap\LoadConfiguration;
use Otrium\Database\DatabaseServiceProvider;
use Symfony\Component\Console\Application as Console;
use Otrium\Core\Contracts\Application as ApplicationContract;

class Application extends Container implements ApplicationContract
{
    /**
     * The Otrium application version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * The base path for the Laravel installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The environment file to load during bootstrapping.
     *
     * @var string
     */
    protected $environmentFile = '.env';

    /**
     * Indicates if the application has "booted".
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * All of the registered service providers.
     *
     * @var \Otrium\Core\ServiceProvider[]
     */
    protected $serviceProviders = [];

    /**
     * The bootstrap classes for the application.
     *
     * @var string[]
     */
    protected $bootstrappers = [
        LoadEnvironment::class,
        LoadConfiguration::class,
    ];

    /**
     * Create a new Illuminate application instance.
     *
     * @param string|null $basePath
     *
     * @return void
     */
    public function __construct(?string $basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->bootstrapApplication();

        $this->registerBaseBindings();
        $this->registerServiceProviders();
    }

    /**
     * Set the base path for the application.
     *
     * @param string $basePath
     *
     * @return \Otrium\Core\Application
     */
    public function setBasePath(string $basePath): Application
    {
        $this->basePath = rtrim($basePath, '\/');

        $this->bind('path.base', $this->basePath);

        return $this;
    }

    /**
     * Get the base path of the Otrium application installation.
     *
     * @param string $path
     *
     * @return string
     */
    public function basePath(string $path = ''): string
    {
        return $this->basePath . ($path ? \DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param string $path
     *
     * @return string
     */
    public function configPath(string $path = ''): string
    {
        return $this->basePath . \DIRECTORY_SEPARATOR . 'config' . ($path ? \DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Bootstrap the application.
     *
     * @return void
     */
    public function bootstrapApplication(): void
    {
        foreach ($this->bootstrappers as $bootstrapper) {
            $this->resolve($bootstrapper)->bootstrap($this);
        }
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version(): string
    {
        return static::VERSION;
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings(): void
    {
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance(ApplicationContract::class, $this);
    }

    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerServiceProviders(): void
    {
        $this->register(new LogServiceProvider($this));
        $this->register(new FileServiceProvider($this));
        $this->register(new DatabaseServiceProvider($this));

        $this->registerConsole();

        $this->booted = true;
    }

    /**
     * Register the console interface provider.
     *
     * @return void
     */
    protected function registerConsole(): void
    {
        $this->singleton('console', function () {
            return new Console($this->config('app.name'), $this->version());
        });
    }

    /**
     * Register a service provider with the application.
     *
     * @param \Otrium\Core\ServiceProvider|string $provider
     *
     * @return \Otrium\Core\ServiceProvider
     */
    public function register($provider): ServiceProvider
    {
        if (is_string($provider)) {
            $provider = $this->resolveProvider($provider);
        }

        $provider->register();

        $this->markAsRegistered($provider);

        return $provider;
    }

    /**
     * Resolve a service provider instance from the class name.
     *
     * @param string $provider
     *
     * @return \Otrium\Core\ServiceProvider
     */
    public function resolveProvider(string $provider): ServiceProvider
    {
        return $this->make($provider);
    }

    /**
     * Mark the given provider as registered.
     *
     * @param \Otrium\Core\ServiceProvider $provider
     *
     * @return void
     */
    protected function markAsRegistered(ServiceProvider $provider): void
    {
        $this->serviceProviders[get_class($provider)] = $provider;
    }

    /**
     * Determine if the application has booted.
     *
     * @return bool
     */
    public function isBooted(): bool
    {
        return $this->booted;
    }

    /**
     * Get the environment file the application is using.
     *
     * @return string
     */
    public function environmentFile(): string
    {
        return $this->environmentFile ?: '.env';
    }

    /**
     * Get the application configurations.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public function config(?string $key = null, $default = null)
    {
        if (is_null($key)) {
            return $this['config'];
        }

        return $this['config']->get($key, $default);
    }

    /**
     * Get the service providers that have been loaded.
     *
     * @return array
     */
    public function getLoadedProviders(): array
    {
        return $this->serviceProviders;
    }

    /**
     * Determine if the given service provider is loaded.
     *
     * @param string $provider
     *
     * @return bool
     */
    public function providerIsLoaded(string $provider): bool
    {
        return isset($this->serviceProviders[$provider]);
    }
}
