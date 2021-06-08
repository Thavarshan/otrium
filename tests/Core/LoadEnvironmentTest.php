<?php

namespace Otrium\Tests\Core;

use Mockery as m;
use Otrium\Core\Application;
use PHPUnit\Framework\TestCase;
use Otrium\Core\Bootstrap\LoadEnvironment;

class LoadEnvironmentTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($_ENV['FOO'], $_SERVER['FOO']);
        putenv('FOO');
        m::close();
    }

    public function testCanLoad()
    {
        $this->expectOutputString('');

        (new LoadEnvironment())->bootstrap($this->getAppMock('.env'));

        $this->assertSame('BAR', env('FOO'));
        $this->assertSame('BAR', getenv('FOO'));
        $this->assertSame('BAR', $_ENV['FOO']);
        $this->assertSame('BAR', $_SERVER['FOO']);
    }

    protected function getAppMock($file)
    {
        $app = m::mock(Application::class);

        $app->shouldReceive('basePath')
            ->once()->with()->andReturn(__DIR__ . '/../Fixtures');
        $app->shouldReceive('environmentFile')
            ->once()->with()->andReturn($file);

        return $app;
    }
}
