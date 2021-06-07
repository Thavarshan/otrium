<?php

namespace Otrium\Core;

abstract class ServiceProvider
{
    /**
     * The Otrium application instance.
     *
     * @var \Otrium\Core\Application
     */
    protected $app;

    /**
     * Create a new service provider instance.
     *
     * @param \Otrium\Core\Application $app
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    abstract public function register(): void;
}
