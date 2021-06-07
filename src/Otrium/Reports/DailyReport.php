<?php

namespace Otrium\Reports;

class DailyReport extends Report
{
    /**
     * Generate the report.
     *
     * @return mixed
     */
    public function generate()
    {
        return $this->db->read(
            'SELECT
                id,
                date,
                turnover - (turnover * 0.21 / 100)
            FROM
                gmv
            WHERE
                date >= DATE(NOW()) + INTERVAL - 7 DAY'
        );
    }
}
