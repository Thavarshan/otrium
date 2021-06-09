<?php

namespace Otrium\Reports;

use Otrium\Reports\Contracts\Report as ReportContract;

class BrandReport extends Report implements ReportContract
{
    /**
     * The custom raw query statement use to generate report data.
     *
     * @var string
     */
    protected static $rawStatement = "SELECT brands.name, sum(gmv.turnover - (gmv.turnover * 0.21 / 100)) AS turnover FROM brands JOIN gmv ON gmv.brand_id = brands.id WHERE gmv.date BETWEEN  CAST('%s' AS DATE) AND CAST('%s' AS DATE) GROUP BY brands.name";

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
