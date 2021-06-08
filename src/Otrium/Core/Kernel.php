<?php

namespace Otrium\Core;

use Throwable;
use Otrium\Core\Contracts\Application;
use Otrium\Console\Commands\GenerateReportCommand;
use Otrium\Core\Contracts\Kernel as KernelContract;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Application as Console;
use Symfony\Component\Console\Output\OutputInterface;

class Kernel implements KernelContract
{
    /**
     * The application implementation.
     *
     * @var \Otrium\Core\Application
     */
    protected $app;

    /**
     * All commands provided by otrium application.
     *
     * @var array
     */
    protected $commands = [
        GenerateReportCommand::class,
    ];

    /**
     * Create new Otrium application kernel instance.
     *
     * @param \Otrium\Core\Application $app
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming console command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface        $input
     * @param \Symfony\Component\Console\Output\OutputInterface|null $output
     *
     * @return int
     */
    public function handle(InputInterface $input, ?OutputInterface $output = null): int
    {
        $console = $this->loadCommands($this->app->make('console'));

        try {
            $status = $console->doRun($input, $output);
        } catch (Throwable $e) {
            $this->reportException($e);

            if ($this->app->config('app.debug')) {
                $console->renderThrowable($e, $output);
            }

            return 1;
        }

        return $status;
    }

    /**
     * Load all registered console commands.
     *
     * @param \Symfony\Component\Console\Application $console
     *
     * @return \Symfony\Component\Console\Application
     */
    protected function loadCommands(Console $console): Console
    {
        foreach ($this->commands as $command) {
            $console->add($this->app->make($command));
        }

        return $console;
    }

    /**
     * Report the exception to the exception handler.
     *
     * @param \Throwable $e
     *
     * @return void
     */
    protected function reportException(Throwable $e): void
    {
        $this->app['logger']->error($e->getMessage(), ['exception' => $e]);
    }
}
