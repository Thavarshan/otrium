<?php

namespace Otrium\Database;

use Otrium\Core\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerConnectionServices();
    }

    /**
     * Register the primary database bindings.
     *
     * @return void
     */
    protected function registerConnectionServices(): void
    {
        $this->app->bind('db', function ($app) {
            $connection = new Connection($app['config']->get('database'));

            $connection->connect();

            return $connection;
        });
    }
}
