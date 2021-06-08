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
    protected static $rawStatement = 'SELECT brands.name, sum(gmv.turnover - (gmv.turnover * 0.21 / 100)) AS turnover FROM brands JOIN gmv ON gmv.brand_id = brands.id WHERE gmv.date >= DATE(NOW()) + INTERVAL - 7 DAY GROUP BY brands.name';

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
