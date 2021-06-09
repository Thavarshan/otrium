<?php

namespace Otrium\Reports;

use Otrium\Reports\Contracts\Report as ReportContract;

class DailyReport extends Report implements ReportContract
{
    /**
     * The custom raw query statement use to generate report data.
     *
     * First %s represents the "to" date and the second is the "from" date.
     *
     * @var string
     */
    protected static $rawStatement = "SELECT id, date, turnover - (turnover * 0.21 / 100) FROM gmv WHERE date BETWEEN  CAST('%s' AS DATE) AND  CAST('%s' AS DATE)";
}
