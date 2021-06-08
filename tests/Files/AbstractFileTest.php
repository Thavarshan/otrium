<?php

namespace Otrium\Tests\Files;

use Mockery as m;
use Otrium\Files\File;
use PHPUnit\Framework\TestCase;
use Otrium\Files\Contracts\Service;

class AbstractFileTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testInstantiation()
    {
        $file = new MockFile('./');

        $this->assertInstanceOf(File::class, $file);
    }

    public function testSetPrefix()
    {
        $file = new MockFile('.');

        $file->setPrefix('otrium');

        $this->assertEquals('./otrium_' . date('Y-m-d-hia') . '.csv', $file->fileName());
    }
}

class MockFile extends File
{
    /**
     * Create the respective service provided.
     *
     * @return \Otrium\Files\Contracts\Service
     */
    public function createService(): Service
    {
        return m::mock(Service::class);
    }
}
