<?php

namespace Otrium\Tests\Reports;

use Mockery as m;
use Otrium\Reports\Report;
use Otrium\Reports\BrandReport;
use PHPUnit\Framework\TestCase;
use Otrium\Database\Contracts\Connection;
use Otrium\Reports\Contracts\Report as ReportContract;

class BrandReportTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testInstantiation()
    {
        $report = new BrandReport($this->getMockDatabase());

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

        $report = new BrandReport($db);
        BrandReport::setQueryStatement('FAKE STATEMENT');

        $this->assertEquals(['today' => 3], $report->generate());
    }

    public function getMockDatabase()
    {
        return m::mock(Connection::class);
    }
}
