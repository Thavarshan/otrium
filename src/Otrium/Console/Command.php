<?php

namespace Otrium\Console;

use Otrium\Core\Application;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class Command extends SymfonyCommand
{
    /**
     * The Otrium application instance.
     *
     * @var \Otrium\Core\Application
     */
    protected $app;

    /**
     * Create new instance of.
     *
     * @param \Otrium\Core\Application $app
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        parent::__construct();

        $this->app = $app;
    }
}
