<?php

namespace Otrium\Tests\Console;

use Mockery as m;
use Otrium\Console\Command;
use PHPUnit\Framework\TestCase;
use Otrium\Core\Contracts\Application;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class CommandTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testInstantiation()
    {
        $command = new Command($this->getAppMock());

        $this->assertInstanceOf(SymfonyCommand::class, $command);
    }

    protected function getAppMock()
    {
        return m::mock(Application::class);
    }
}
