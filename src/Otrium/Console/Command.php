<?php

namespace Otrium\Console;

use Otrium\Core\Contracts\Application;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class Command extends SymfonyCommand
{
    /**
     * The Otrium application instance.
     *
     * @var \Otrium\Core\Contracts\Application
     */
    protected $app;

    /**
     * The input interface implementation.
     *
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * The output interface implementation.
     *
     * @var \Illuminate\Console\OutputStyle
     */
    protected $output;

    /**
     * Create new instance of.
     *
     * @param \Otrium\Core\Contracts\Application $app
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        parent::__construct();

        $this->app = $app;
    }
}
