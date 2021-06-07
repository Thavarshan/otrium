<?php

namespace Otrium\Database\Contracts;

use PDO;
use PDOStatement;

interface Connection
{
    /**
     * Connect to the database using provided configurations.
     *
     * @return void
     */
    public function connect(): void;

    /**
     * Get the PDO instance.
     *
     * @return \PDO
     */
    public function pdo(): PDO;

    /**
     * Execute a read SQL statement on a particular fields.
     *
     * @param string $statement
     * @param array  $parameters
     *
     * @return array
     */
    public function readField(string $statement, array $parameters = []): array;

    /**
     * Execute a read SQL statement on all fields.
     *
     * @param string $statement
     * @param array  $parameters
     *
     * @return array
     */
    public function readFields(string $statement, array $parameters = []): array;

    /**
     * Execute a read SQL statement.
     *
     * @param string $statement
     * @param array  $parameters
     *
     * @return array
     */
    public function readRecord(string $statement, array $parameters = []): array;

    /**
     * Execute an update SQL statement.
     *
     * @param string $statement
     * @param array  $parameters
     *
     * @return int
     */
    public function update(string $statement, array $parameters = []): int;

    /**
     * Execute a query through the given statement.
     *
     * @param string $statement
     * @param array  $parameters
     *
     * @return array
     */
    public function read(string $statement, array $parameters = []): array;

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
    public function execute(string $statement, array $parameters = []): PDOStatement;
}
