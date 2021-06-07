<?php

namespace Otrium\Console;

use Otrium\Core\Application;
use Otrium\Core\ServiceProvider;
use Otrium\Console\Application as ConsoleApplication;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('console', function ($app) {
            return $this->createConsoleApplication($app);
        });
    }

    /**
     * Create the Otrium console interface.
     *
     * @param \Otrium\Core\Application $app
     *
     * @return \Otrium\Console\Application
     */
    protected function createConsoleApplication(Application $app): ConsoleApplication
    {
        return new ConsoleApplication($app['config']->get('app.name'), $this->app::VERSION);
    }
}
