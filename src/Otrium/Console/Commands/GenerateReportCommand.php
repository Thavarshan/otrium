<?php

namespace Otrium\Console\Commands;

use Otrium\Files\Writer;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Otrium\Console\Command;
use League\Csv\InvalidArgument;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateReportCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('report:generate')
            ->setDescription('Generate sales report')
            ->addArgument('name', InputArgument::REQUIRED);
    }

    /**
     * Execute the command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        if (! Arr::exists($this->availableReports(), $name)) {
            throw new InvalidArgument("Reports cannot be generated for {$name}.");
        }

        $this->generateReportFiles($name, $this->availableReports($name));

        $name = Str::ucfirst($name);

        $output->writeln("<info>{$name} report generated. You may find them in the [reports] directory.</info>");

        return 0;
    }

    /**
     * Get one or all available report types.
     *
     * @param string|null $name
     *
     * @return string|array
     */
    protected function availableReports(?string $name = null)
    {
        $availableReports = $this->app->config('reports.available_reports');

        if (! is_null($name)) {
            return $availableReports[$name];
        }

        return $availableReports;
    }

    /**
     * Generate the requested report and write it to a CSV file.
     *
     * @param string $name
     * @param string $report
     *
     * @return void
     */
    public function generateReportFiles(string $name, string $report): void
    {
        $report = new $report($this->app['db']);

        $this->app->make(Writer::class)->write($name, $report->generate());
    }
}
