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
     * The custom raw query statement use to generate report data.
     *
     * @var string
     */
    protected static $rawStatement = '';

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
     * Set a custom raw query statement.
     *
     * @param string $statement
     *
     * @return void
     */
    public static function setQueryStatement(string $statement): void
    {
        static::$rawStatement = $statement;
    }

    /**
     * Set a custom raw query statement.
     *
     * @return string
     */
    public static function queryStatement(): string
    {
        return static::$rawStatement;
    }
}
