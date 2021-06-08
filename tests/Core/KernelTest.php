<?php

namespace Otrium\Tests\Core;

use Mockery as m;
use Otrium\Core\Kernel;
use PHPUnit\Framework\TestCase;
use Otrium\Core\Contracts\Application;
use Otrium\Console\Commands\GenerateReportCommand;

class KernelTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testGetCommands()
    {
        $kernel = new Kernel($this->getApplication());

        $this->assertEquals([
            GenerateReportCommand::class,
        ], $kernel->getCommands());
    }

    /**
     * @return \Otrium\Core\Contracts\Application
     */
    protected function getApplication()
    {
        return m::mock(Application::class);
    }
}
