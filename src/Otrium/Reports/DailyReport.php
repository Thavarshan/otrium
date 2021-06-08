<?php

namespace Otrium\Reports;

use Otrium\Reports\Contracts\Report as ReportContract;

class DailyReport extends Report implements ReportContract
{
    /**
     * The custom raw query statement use to generate report data.
     *
     * @var string
     */
    protected static $rawStatement = 'SELECT id, date, turnover - (turnover * 0.21 / 100) FROM gmv WHERE date >= DATE(NOW()) + INTERVAL - 7 DAY';

    /**
     * Generate the report.
     *
     * @return mixed
     */
    public function generate()
    {
        return $this->db->read(static::queryStatement());
    }
}
