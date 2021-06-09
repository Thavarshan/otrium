<?php

namespace Otrium\Tests\Reports;

use Mockery as m;
use Otrium\Reports\Report;
use PHPUnit\Framework\TestCase;
use Otrium\Database\Contracts\Connection;
use Otrium\Reports\Contracts\Report as ReportContract;

class ReportTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testInstantiation()
    {
        $report = new MockReport($this->getMockDatabase());

        $this->assertInstanceOf(Report::class, $report);
        $this->assertInstanceOf(ReportContract::class, $report);
    }

    public function testSetCustomQuery()
    {
        MockReport::setQueryStatement($query = 'SELECT * FROM users');

        $this->assertEquals($query, MockReport::queryStatement());
    }

    public function getMockDatabase()
    {
        return m::mock(Connection::class);
    }
}

class MockReport extends Report implements ReportContract
{
    /**
     * Generate the report.
     *
     * @param string|null $from
     *
     * @return mixed
     */
    public function generate(?string $from = null)
    {
    }
}
