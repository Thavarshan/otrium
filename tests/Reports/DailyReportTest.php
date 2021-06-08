<?php

namespace Otrium\Tests\Reports;

use Mockery as m;
use Otrium\Reports\Report;
use Otrium\Reports\DailyReport;
use PHPUnit\Framework\TestCase;
use Otrium\Database\Contracts\Connection;
use Otrium\Reports\Contracts\Report as ReportContract;

class DailyReportTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testInstantiation()
    {
        $report = new DailyReport($this->getMockDatabase());

        $this->assertInstanceOf(Report::class, $report);
        $this->assertInstanceOf(ReportContract::class, $report);
    }

    public function testGenerateReport()
    {
        $db = $this->getMockDatabase();
        $db->shouldReceive('read')
            ->once()
            ->with('FAKE STATEMENT')
            ->andReturn(['today' => 3]);

        $report = new DailyReport($db);
        DailyReport::setQueryStatement('FAKE STATEMENT');

        $this->assertEquals(['today' => 3], $report->generate());
    }

    public function getMockDatabase()
    {
        return m::mock(Connection::class);
    }
}
