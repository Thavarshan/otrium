<?php

namespace Otrium\Reports;

class BrandReport extends Report
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
                brands.name,
                sum(gmv.turnover - (gmv.turnover * 0.21 / 100)) AS turnover
            FROM
                brands
                JOIN gmv ON gmv.brand_id = brands.id
            WHERE
                gmv.date >= DATE(NOW()) + INTERVAL - 7 DAY
            GROUP BY
                brands.name'
        );
    }
}
