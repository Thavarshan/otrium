<?php

namespace Otrium\Files;

use Otrium\Core\ServiceProvider;

class FileServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerWrite();
        $this->registerReader();
    }

    /**
     * Register CSV file writer.
     *
     * @return void
     */
    protected function registerWrite(): void
    {
        $this->app->singleton(Writer::class, function ($app) {
            return new Writer($app->basePath('reports'));
        });
    }

    /**
     * Register CSV file reader.
     *
     * @return void
     */
    protected function registerReader(): void
    {
        $this->app->singleton(Reader::class, function ($app) {
            return new Reader($app->basePath('reports'));
        });
    }
}
