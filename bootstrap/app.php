<?php

use Otrium\Core\Kernel;
use Otrium\Core\Application;
use Otrium\Core\Contracts\Kernel as KernelContract;

$app = new Application(dirname(__DIR__));

$app->singleton(KernelContract::class, function ($app) {
    return new Kernel($app);
});

return $app;
