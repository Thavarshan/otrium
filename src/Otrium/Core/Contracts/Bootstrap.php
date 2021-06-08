<?php

namespace Otrium\Core\Contracts;

interface Bootstrap
{
    /**
     * Bootstrap the given application.
     *
     * @param \Otrium\Core\Contracts\Application $app
     *
     * @return void
     */
    public function bootstrap(Application $app): void;
}
