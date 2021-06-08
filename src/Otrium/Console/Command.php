<?php

namespace Otrium\Console;

use Otrium\Core\Contracts\Application;
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

    /**
     * Get the value of a command argument.
     *
     * @param string|null $key
     *
     * @return string|array|null
     */
    public function argument(?string $key = null)
    {
        if (is_null($key)) {
            return $this->input->getArguments();
        }

        return $this->input->getArgument($key);
    }

    /**
     * Get all of the arguments passed to the command.
     *
     * @return array
     */
    public function arguments(): array
    {
        return $this->argument();
    }

    /**
     * Get the value of a command option.
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function option(?string $key = null)
    {
        if (is_null($key)) {
            return $this->input->getOptions();
        }

        return $this->input->getOption($key);
    }

    /**
     * Get all of the options passed to the command.
     *
     * @return array
     */
    public function options(): array
    {
        return $this->option();
    }
}
