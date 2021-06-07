<?php

namespace Otrium\Database;

use Otrium\Database\Contracts\Connection;

class Factory
{
    /**
     * The database connection instance.
     *
     * @var \Otrium\Database\Contracts\Connection
     */
    protected $connection;

    /**
     * The table to be queried.
     *
     * @var string
     */
    protected $table;

    /**
     * Set of parameters.
     *
     * @var array
     */
    protected $parameters = [];

    protected $tableClause;
    protected $whereClause;
    protected $groupClause;
    protected $limitClause;
    protected $orderClause;

    /**
     * Create enw instance of database manager.
     *
     * @param \Otrium\Database\Contracts\Connection $connection
     * @param string                                $table
     *
     * @return void
     */
    public function __construct(Connection $connection, string $table)
    {
        $this->connection = $connection;
        $this->table = $table;

        $this->tableClause = "`$table`";
        $this->whereClause = '1';
    }

    /**
     * Select records which match the given condition.
     *
     * @param string $condition
     * @param array  $values
     *
     * @return $this
     */
    public function where(string $condition, array $values = [])
    {
        $this->whereClause .= " AND {$condition}";

        foreach ($values as $value) {
            $this->parameters[] = $value;
        }

        return $this;
    }

    /**
     * Select records where given field is equal to given value.
     *
     * @param string $field
     * @param mixed  $value
     * @param bool   $reverse
     *
     * @return $this
     */
    public function whereEqual(string $field, $value, bool $reverse = false)
    {
        $field = $this->escapeField($field);

        $operator = $reverse ? '!=' : '=';

        $this->whereClause .= " AND $field $operator ?";

        $this->parameters[] = $value;

        return $this;
    }

    /**
     * Select records where given field is not equal to given value.
     *
     * @param string $field
     * @param mixed  $value
     *
     * @return $this
     */
    public function whereNotEqual(string $field, $value)
    {
        $this->whereEqual($field, $value, true);

        return $this;
    }

    /**
     * Select records where given field is in.
     *
     * @param string $field
     * @param array  $values
     * @param bool   $reverse
     *
     * @return $this
     */
    public function whereIn(string $field, array $values, bool $reverse = false)
    {
        $field = $this->escapeField($field);

        $operator = $reverse ? 'NOT IN' : 'IN';

        $this->whereClause .= " AND $field $operator (";

        foreach ($values as $value) {
            $this->whereClause .= '?, ';

            $this->parameters[] = $value;
        }

        $this->whereClause = substr_replace($this->whereClause, ')', -2);

        return $this;
    }

    /**
     * Select records where given field is not in.
     *
     * @param string $field
     * @param array  $values
     *
     * @return $this
     */
    public function whereNotIn(string $field, array $values)
    {
        $this->whereIn($field, $values, true);

        return $this;
    }

    /**
     * Select records where given field is null.
     *
     * @param string $field
     * @param bool   $reverse
     *
     * @return $this
     */
    public function whereNull(string $field, bool $reverse = false)
    {
        $field = $this->escapeField($field);

        $operator = $reverse ? 'IS NOT' : 'IS';

        $this->whereClause .= " AND $field $operator NULL";

        return $this;
    }

    /**
     * Select records where given field is not null.
     *
     * @param string $field
     *
     * @return $this
     */
    public function whereNotNull(string $field)
    {
        $this->whereNull($field, true);

        return $this;
    }

    /**
     * Group results by prefered grouping clause.
     *
     * @param string $group
     *
     * @return $this
     */
    public function group(string $group)
    {
        $this->groupClause = $group;

        return $this;
    }

    /**
     * Limit the number of resuts.
     *
     * @param string $limit
     *
     * @return $this
     */
    public function limit(string $limit)
    {
        $this->limitClause = $limit;

        return $this;
    }

    /**
     * Order results by prefered order.
     *
     * @param string $order
     *
     * @return $this
     */
    public function order(string $order)
    {
        $this->orderClause = $order;

        return $this;
    }

    /**
     * Order results in ascending order by given field.
     *
     * @param string $field
     *
     * @return $this
     */
    public function orderAsc(string $field)
    {
        $field = $this->escapeField($field);

        $this->orderClause = $field . ' ASC';

        return $this;
    }

    /**
     * Order results by given field.
     *
     * @param string $field
     *
     * @return $this
     */
    public function orderDesc(string $field)
    {
        $field = $this->escapeField($field);

        $this->orderClause = $field . ' DESC';

        return $this;
    }

    /**
     * Perform read statment action.
     *
     * @param string|null $selectExpression
     *
     * @return array
     */
    public function read(?string $selectExpression = null)
    {
        $statement = $this->composeReadStatement($selectExpression);

        return $this->connection->read($statement, $this->parameters);
    }

    /**
     * Perform read statment for specific record.
     *
     * @param string|null $selectExpression
     *
     * @return array
     */
    public function readRecord(?string $selectExpression = null): array
    {
        $statement = $this->composeReadStatement($selectExpression);

        return $this->connection->readRecord($statement, $this->parameters);
    }

    /**
     * Perform read statment for specific field.
     *
     * @param string $selectExpression
     *
     * @return array
     */
    public function readField(?string $selectExpression = null): array
    {
        $statement = $this->composeReadStatement($selectExpression);

        return $this->connection->readField($statement, $this->parameters);
    }

    /**
     * @param string|null $selectExpression
     *
     * @return array
     */
    public function readFields(?string $selectExpression = null): array
    {
        $statement = $this->composeReadStatement($selectExpression);

        return $this->connection->readFields($statement, $this->parameters);
    }

    /**
     * Count results.
     *
     * @return int
     */
    public function count(): int
    {
        $count = (int) $this->readField('COUNT(*)');

        return $count;
    }

    /**
     * Perform update operation.
     *
     * @param array $data
     *
     * @return int
     */
    public function update(array $data): int
    {
        $statement = "UPDATE `{$this->table}` SET ";

        foreach (array_keys($data) as $field) {
            $statement .= "`{$this->table}`.`$field` = ?, ";
        }

        $statement = substr_replace($statement, " WHERE {$this->whereClause}", -2);

        $this->limitClause and $statement .= " LIMIT {$this->limitClause}";

        $parameters = array_values($data);
        $parameters = array_merge($parameters, $this->parameters);

        return $this->connection->update($statement, $parameters);
    }

    /**
     * Execute a deletion operation.
     *
     * @return int
     */
    public function delete(): int
    {
        $statement = "DELETE FROM {$this->tableClause} WHERE {$this->whereClause}";

        $this->orderClause and $statement .= " ORDER BY $this->orderClause";
        $this->limitClause and $statement .= " LIMIT $this->limitClause";

        $impactedRecordCount = $this->connection->update($statement, $this->parameters);

        return $impactedRecordCount;
    }

    /**
     * Compose a valid SQL read statement.
     *
     * @param string|null $selectExpression
     *
     * @return string
     */
    protected function composeReadStatement(?string $selectExpression = null): string
    {
        $selectExpression = $selectExpression ?: "`$this->table`.*";

        $query = "SELECT {$selectExpression} FROM {$this->tableClause} WHERE {$this->whereClause}";

        $this->groupClause and $query .= " GROUP BY $this->groupClause";
        $this->orderClause and $query .= " ORDER BY $this->orderClause";
        $this->limitClause and $query .= " LIMIT $this->limitClause";

        return $query;
    }

    /**
     * Cleanup input fields.
     *
     * @param string $field
     *
     * @return string
     */
    protected function escapeField(string $field): string
    {
        $field = str_replace('`', '', $field);
        $field = str_replace('.', '`.`', $field);
        $field = '`' . $field . '`';

        return $field;
    }
}
