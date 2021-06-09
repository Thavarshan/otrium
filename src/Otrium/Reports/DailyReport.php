<?php

namespace Otrium\Reports;

class DailyReport extends Report
{
    /**
     * The custom raw query statement use to generate report data.
     *
     * First %s represents the "to" date and the second is the "from" date.
     *
     * @var string
     */
    protected static $rawStatement = "SELECT id, date, turnover - (turnover * 0.21 / 100) FROM gmv WHERE date BETWEEN  CAST('%s' AS DATE) AND  CAST('%s' AS DATE)";

    /**
     * Generate the report.
     *
     * @param string|null $from
     *
     * @return mixed
     */
    public function generate(?string $from = null)
    {
        [$from, $to] = array_values($this->parseDateRange($from));

        return $this->db->read(
            sprintf(static::queryStatement(), $to, $from)
        );
    }
}
