<?php

namespace Otrium\Tests\Files;

use Otrium\Files\Writer;
use PHPUnit\Framework\TestCase;

class WriterTest extends TestCase
{
    public function testCreateService()
    {
        $service = new Writer(__DIR__ . '../Fixtures/stub.csv');

        $this->assertInstanceOf(Writer::class, $service);
    }
}
