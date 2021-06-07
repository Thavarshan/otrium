<?php

namespace Otrium\Logger;

use Psr\Log\LoggerInterface;
use Monolog\Logger as Monolog;
use Otrium\Core\ServiceProvider;
use Monolog\Handler\StreamHandler;

class LogServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('logger', function ($app) {
            return new Logger(
                $this->createLogDriver($app['config']->get('logging'))
            );
        });
    }

    /**
     * Create a default log driver.
     *
     * @param array $config
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function createLogDriver(array $config): LoggerInterface
    {
        $driver = new Monolog($config['channel']);

        $driver->pushHandler(new StreamHandler($config['path'], $config['level']));

        return new Logger($driver);
    }
}
