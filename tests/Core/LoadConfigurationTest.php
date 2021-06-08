<?php

namespace Otrium\Tests\Core;

use Mockery as m;
use Otrium\Core\Application;
use PHPUnit\Framework\TestCase;
use Otrium\Core\Bootstrap\LoadConfiguration;

class LoadConfigurationTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testCanLoad()
    {
        (new LoadConfiguration())->bootstrap($app = $this->getAppplication());

        $this->assertSame('bar', $app->config('app.foo'));
    }

    protected function getAppplication()
    {
        $app = new Application(__DIR__ . '/../Fixtures/');

        return $app;
    }
}
