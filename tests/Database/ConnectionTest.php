<?php

namespace Otrium\Tests\Database;

use Otrium\Database\Connection;
use PHPUnit\Framework\TestCase;
use Otrium\Tests\Concerns\InteractsWithSystem;

/**
 * Test Skipped on CI to offer multi-platform testing.
 *
 * @group database
 */
class ConnectionTest extends TestCase
{
    use InteractsWithSystem;

    /**
     * The database connection instance.
     *
     * @var \Otrium\Database\Contracts\Connection
     */
    protected $connection;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->isWindows()) {
            $this->markTestSkipped('Skipping since operating system is Windows');
        }

        $this->makeConnection();

        $this->connection->pdo()->beginTransaction();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->connection->pdo()->rollBack();
    }

    public function testPreparedStatement()
    {
        $impactedRecordCount = $this->connection->update('UPDATE user SET last_name = ?', ['Smith']);

        $this->assertEquals(2, $impactedRecordCount);

        $records = $this->connection->read('SELECT * FROM user');

        $expectedRecords = [
            [
                'id' => '1',
                'username' => 'john.doe',
                'first_name' => 'John',
                'last_name' => 'Smith',
            ],
            [
                'id' => '2',
                'username' => 'jane.doe',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
            ],
        ];

        $this->assertEquals($expectedRecords, $records);
    }

    public function testConnectionFactory()
    {
        $impactedRecordCount = $this->connection->find('user')->update([
            'last_name' => 'Smith',
        ]);

        $this->assertEquals(2, $impactedRecordCount);

        $records = $this->connection->find('user')->read();

        $expectedRecords = [
            [
                'id' => '1',
                'username' => 'john.doe',
                'first_name' => 'John',
                'last_name' => 'Smith',
            ],
            [
                'id' => '2',
                'username' => 'jane.doe',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
            ],
        ];

        $this->assertEquals($expectedRecords, $records);

        $impactedRecordCount = $this->connection->find('user')
            ->whereEqual('username', 'john.doe')
            ->delete();

        $this->assertEquals(1, $impactedRecordCount);

        $records = $this->connection->find('user')->read();

        $expectedRecords = [
            [
                'id' => '2',
                'username' => 'jane.doe',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
            ],
        ];

        $this->assertEquals($expectedRecords, $records);
    }

    public function testConnectionFactoryRecord()
    {
        $record = $this->connection->find('user')->readRecord();

        $expectedRecord = [
            'id' => '1',
            'username' => 'john.doe',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ];

        $this->assertEquals($expectedRecord, $record);

        $record = $this->connection->find('user')->readRecord('id, CONCAT(first_name, " ", last_name) as name');

        $expectedRecord = [
            'id' => '1',
            'name' => 'John Doe',
        ];

        $this->assertEquals($expectedRecord, $record);
    }

    public function testConnectionFactoryField()
    {
        $field = $this->connection->find('user')->readField();

        $this->assertEquals('1', $field);

        $field = $this->connection->find('user')->readField('COUNT(*)');

        $this->assertEquals('2', $field);
    }

    public function testConnectionFactoryCount()
    {
        $field = $this->connection->find('user')->count();

        $this->assertEquals(2, $field);
    }

    public function testConnectionFactoryFields()
    {
        $fields = $this->connection->find('user')->readFields();

        $this->assertEquals([1, 2], $fields);

        $fields = $this->connection->find('user')->readFields('first_name');

        $this->assertEquals(['John', 'Jane'], $fields);
    }

    public function testConnectionFactoryWhereClause()
    {
        $fields = $this->connection->find('user')
            ->where('id = 2')
            ->readFields('first_name');

        $this->assertEquals(['Jane'], $fields);

        $fields = $this->connection->find('user')
            ->whereEqual('id', 2)
            ->readFields('first_name');

        $this->assertEquals(['Jane'], $fields);

        $fields = $this->connection->find('user')
            ->whereNotEqual('id', 2)
            ->readFields('first_name');

        $this->assertEquals(['John'], $fields);

        $fields = $this->connection->find('post')
            ->whereIn('id', [1, 3])
            ->readFields();

        $this->assertEquals([1, 3], $fields);

        $fields = $this->connection->find('post')
            ->whereNotIn('id', [1, 3])
            ->readFields();

        $this->assertEquals([2, 4], $fields);
    }

    public function testConnectionFactoryOrderClause()
    {
        $fields = $this->connection->find('post')
            ->order('id DESC')
            ->readFields();

        $this->assertEquals([4, 3, 2, 1], $fields);

        $fields = $this->connection->find('post')
            ->orderAsc('id')
            ->readFields();

        $this->assertEquals([1, 2, 3, 4], $fields);

        $fields = $this->connection->find('post')
            ->orderDesc('id')
            ->readFields();

        $this->assertEquals([4, 3, 2, 1], $fields);
    }

    public function testConnectionFactoryClauseCombinations()
    {
        $fields = $this->connection->find('post')
            ->whereNotEqual('id', 2)
            ->whereNotIn('id', [1, 3])
            ->readFields();

        $this->assertEquals([4], $fields);

        $fields = $this->connection->find('post')
            ->whereIn('id', [1, 2])
            ->orderDesc('id')
            ->readFields();

        $this->assertEquals([2, 1], $fields);

        $fields = $this->connection->find('post')
            ->whereIn('id', [1, 2, 3])
            ->orderDesc('id')
            ->limit(2)
            ->readFields();

        $this->assertEquals([3, 2], $fields);

        $fields = $this->connection->find('post')
            ->whereIn('id', [1, 2, 3])
            ->orderDesc('id')
            ->limit('1, 2')
            ->readFields();

        $this->assertEquals([2, 1], $fields);
    }

    public function testItem()
    {
        $result = $this->connection->updateItem('user', 1, [
            'first_name' => 'J',
            'last_name' => 'D',
        ]);

        $this->assertEquals(1, $result);

        $Item = $this->connection->readItem('user', 1);

        $expectedRecord = [
            'id' => '1',
            'username' => 'john.doe',
            'first_name' => 'J',
            'last_name' => 'D',
        ];

        $this->assertEquals($expectedRecord, $Item);

        $result = $this->connection->createItem('user', [
            'id' => '3',
            'username' => 'james.smith',
            'first_name' => 'James',
            'last_name' => 'Smith',
        ]);

        $this->assertEquals(3, $result);

        $Item = $this->connection->readItem('user', 3);

        $expectedRecord = [
            'id' => '3',
            'username' => 'james.smith',
            'first_name' => 'James',
            'last_name' => 'Smith',
        ];

        $this->assertEquals($expectedRecord, $Item);

        $result = $this->connection->deleteItem('user', 1);

        $this->assertEquals(1, $result);

        $result = $this->connection->find('user')->count();

        $this->assertEquals(2, $result);
    }

    public function makeConnection()
    {
        $config = [
            'host' => '127.0.0.1',
            'port' => '33306',
            'database' => 'test',
            'username' => 'root',
            'password' => '',
        ];

        $connection = new Connection($config);

        $connection->connect();

        $this->connection = $connection;
    }
}
