<?php

namespace Otrium\Core\Bootstrap;

use Dotenv\Dotenv;
use Illuminate\Support\Env;
use Otrium\Core\Application;
use Otrium\Core\Contracts\Bootstrap as BootstrapperInterface;

class LoadEnvironment implements BootstrapperInterface
{
    /**
     * Bootstrap the given application.
     *
     * @param \Otrium\Core\Application $app
     *
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        $this->createDotenv($app)->safeLoad();
    }

    /**
     * Create a Dotenv instance.
     *
     * @param \Otrium\Core\Application $app
     *
     * @return \Dotenv\Dotenv
     */
    protected function createDotenv(Application $app): Dotenv
    {
        return Dotenv::create(
            Env::getRepository(),
            $app->basePath(),
            $app->environmentFile()
        );
    }
}
