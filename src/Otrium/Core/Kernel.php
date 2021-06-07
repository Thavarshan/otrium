<?php

namespace Otrium\Core;

use Throwable;
use Otrium\Console\Commands\GenerateReportCommand;
use Otrium\Core\Contracts\Kernel as KernelContract;
use Symfony\Component\Console\Input\InputInterface;
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
        $console = $this->app->make('console');

        $console->add(new GenerateReportCommand($this->app));

        try {
            $console->doRun($input, $output);
        } catch (Throwable $e) {
            $this->reportException($e);

            $console->renderThrowable($e, $output);

            return 1;
        }

        return 0;
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
