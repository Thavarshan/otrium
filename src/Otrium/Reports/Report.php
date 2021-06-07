<?php

namespace Otrium\Reports;

use Otrium\Database\Contracts\Connection as DatabaseManager;

abstract class Report
{
    /**
     * The database manager instance.
     *
     * @var \Otrium\Database\Contracts\Connection
     */
    protected $db;

    /**
     * Create new report generator instance.
     *
     * @param \Otrium\Database\Manager $db
     *
     * @return void
     */
    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * Generate the report.
     *
     * @return mixed
     */
    abstract public function generate();
}
