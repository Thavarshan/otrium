<?php

namespace Otrium\Tests\Console;

use Otrium\Files\Writer;
use Otrium\Core\Application;
use Otrium\Database\Connection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;
use Otrium\Tests\Concerns\InteractsWithSystem;
use Otrium\Console\Commands\GenerateReportCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application as Console;

class GenerateReportCommandTest extends TestCase
{
    use InteractsWithSystem;

    /**
     * The otrium application.
     *
     * @var \Otrium\Core\Contracts\Application
     */
    protected $app;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->isWindows()) {
            $this->markTestSkipped('Skipping since operating system is Windows');
        }

        $this->app = new Application(__DIR__ . '/../../');

        $this->app->bind(
            Writer::class,
            fn () => new Writer($this->app->basePath('reports'))
        );

        $this->app->bind('db', function ($app) {
            $connection = new Connection($app->config('database'));

            $connection->connect();

            return $connection;
        });
    }

    public function testItCanGenerateRequestedReport()
    {
        $console = new Console('Otrium');
        $console->add(new GenerateReportCommand($this->app));

        $tester = new CommandTester($console->find('report:generate'));

        $statusCode = $tester->execute(['name' => 'daily']);

        $finder = new Finder();
        $finder->in(__DIR__ . '/../../reports')->files()->name('*.csv');

        $this->assertSame(0, $statusCode);
        foreach ($finder as $file) {
            $this->assertTrue(file_exists($file->getPathname()));
        }
    }
}
