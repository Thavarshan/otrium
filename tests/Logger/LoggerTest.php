<?php

namespace Otrium\Tests\Logger;

use Throwable;
use Mockery as m;
use Otrium\Logger\Logger;
use Monolog\Logger as Monolog;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testMethodsPassErrorAdditionsToMonolog()
    {
        $writer = new Logger($monolog = m::mock(Monolog::class));
        $monolog->shouldReceive('error')->once()->with('foo', []);

        try {
            $writer->error('foo');
        } catch (Throwable $e) {
            $this->fail($e->getMessage());

            return;
        }

        $this->assertTrue(true);
    }
}
