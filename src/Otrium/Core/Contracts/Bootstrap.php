<?php

namespace Otrium\Core\Contracts;

use Otrium\Core\Application;

interface Bootstrap
{
    /**
     * Bootstrap the given application.
     *
     * @param \Otrium\Core\Application $app
     *
     * @return void
     */
    public function bootstrap(Application $app): void;
}
