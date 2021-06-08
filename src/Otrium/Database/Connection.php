<?php

namespace Otrium\Database;

use PDO;
use PDOStatement;
use Otrium\Database\Exceptions\ConnectionException;
use Otrium\Database\Contracts\Connection as ConnectionContract;

class Connection implements ConnectionContract
{
    /**
     * The database configurations.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The Data Objects instance.
     *
     * @var \PDO
     */
    protected $pdo;

    /**
     * Create new instance of database connection manager.
     *
     * @param array $config
     *
     * @return void
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Connect to the database using provided configurations.
     *
     * @return void
     */
    public function connect(): void
    {
        $this->pdo = new PDO(
            $this->constructDns($this->config),
            $this->config['username'],
            $this->config['password'],
            $this->config['options'] ?? [],
        );
    }

    /**
     * Create the DNS string.
     *
     * @param array $config
     *
     * @return string
     */
    public function constructDns(array $config): string
    {
        dd("mysql:host={$config['host']};dbname={$config['database']}");

        return "mysql:host={$config['host']};dbname={$config['database']}";
    }

    /**
     * Execute a read SQL statement on a particular fields.
     *
     * @param string $statement
     * @param array  $parameters
     *
     * @return array|string
     */
    public function readField(string $statement, array $parameters = [])
    {
        $PDOStatement = $this->execute($statement, $parameters);

        $record = $PDOStatement->fetch(PDO::FETCH_COLUMN);

        if (! $record) {
            return [];
        }

        return $record;
    }

    /**
     * Execute a read SQL statement on all fields.
     *
     * @param string $statement
     * @param array  $parameters
     *
     * @return array
     */
    public function readFields(string $statement, array $parameters = []): array
    {
        $pdoStatement = $this->execute($statement, $parameters);

        return $pdoStatement->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Execute a read SQL statement.
     *
     * @param string $statement
     * @param array  $parameters
     *
     * @return array
     */
    public function readRecord(string $statement, array $parameters = []): array
    {
        $pdoStatement = $this->execute($statement, $parameters);

        $record = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        if (! $record) {
            return [];
        }

        return $record;
    }

    /**
     * Execute a read statement in the table on the item belonging to the given ID>.
     *
     * @param string $table
     * @param int    $id
     *
     * @return array
     */
    public function readItem(string $table, int $id): array
    {
        return $this->find($table)->whereEqual('id', $id)->readRecord();
    }

    /**
     * Execute an update SQL statement.
     *
     * @param string $statement
     * @param array  $parameters
     *
     * @return int
     */
    public function update(string $statement, array $parameters = []): int
    {
        $pdoStatement = $this->execute($statement, $parameters);

        return $pdoStatement->rowCount();
    }

    /**
     * Update the entry belonging to the given ID in the given table.
     *
     * @param string $table
     * @param int    $id
     * @param array  $Data
     *
     * @return int
     */
    public function updateItem(string $table, $id, array $Data): int
    {
        return $this->find($table)->whereEqual('id', $id)->update($Data);
    }

    /**
     * Create a new entry in the given table with the given data.
     *
     * @param string $table
     * @param array  $Data
     *
     * @return int
     */
    public function createItem(string $table, array $Data): int
    {
        $statement = "INSERT INTO `$table` SET";

        $parameters = [];

        foreach ($Data as $name => $value) {
            $statement .= " `$name` = ?,";

            $parameters[] = $value;
        }

        $statement = substr($statement, 0, -1);

        $this->execute($statement, $parameters);

        return $this->lastId();
    }

    /**
     * Delete the item with the given ID from the given table.
     *
     * @param string $table
     * @param int    $id
     *
     * @return int
     */
    public function deleteItem(string $table, int $id): int
    {
        return $this->find($table)->whereEqual('id', $id)->delete();
    }

    /**
     * Create an instance of the database manager.
     *
     * @param string $table
     *
     * @return \Otrium\Database\Factory
     */
    public function find(string $table): Factory
    {
        return new Factory($this, $table);
    }

    /**
     * Execute a query through the given statement.
     *
     * @param string $statement
     * @param array  $parameters
     *
     * @return array
     */
    public function read(string $statement, array $parameters = []): array
    {
        $pdoStatement = $this->execute($statement, $parameters);

        $records = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        return $records;
    }

    /**
     * Execute a SQL statement.
     *
     * @param string $statement
     * @param array  $parameters
     *
     * @return \PDOStatement
     *
     * @throws \Otrium\Database\Exceptions\ConnectionException
     */
    public function execute(string $statement, array $parameters = []): PDOStatement
    {
        $pdoStatement = $this->pdo->prepare($statement);

        $successful = $pdoStatement->execute($parameters);

        if (! $successful) {
            $errorInfo = $pdoStatement->errorInfo();

            throw new ConnectionException($errorInfo[2] . ': ' . $statement);
        }

        return $pdoStatement;
    }

    /**
     * Get the ID of the last inserted data.
     *
     * @return int
     */
    public function lastId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Get the PDO instance.
     *
     * @return \PDO
     */
    public function pdo(): PDO
    {
        return $this->pdo;
    }
}
