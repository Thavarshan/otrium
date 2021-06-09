<?php

namespace Otrium\Reports;

use Carbon\Carbon;
use Otrium\Reports\Exceptions\ReportException;
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
    protected static $rawStatement;

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
     * @param string|null $from
     *
     * @return mixed
     */
    public function generate(?string $from = null)
    {
        [$from, $to] = array_values($this->parseDateRange($from));

        if (! $query = static::queryStatement()) {
            throw new ReportException('Report specific query statement has not been set.');
        }

        return $this->db->read(sprintf($query, $to, $from));
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

    /**
     * Calculate the from and to dates and parse them into proper formats.
     *
     * @param string|null $from
     *
     * @return array
     */
    public function parseDateRange(?string $from = null): array
    {
        if (is_null($from)) {
            $from = Carbon::now()->toDateString();
        }

        $to = Carbon::parse($from)->subDays(7)->toDateString();

        return compact('from', 'to');
    }
}
