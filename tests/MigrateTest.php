<?php

namespace Packaged\Tests\DalSchema;

use Packaged\Dal\DalResolver;
use Packaged\Dal\Ql\MySQLiConnection;
use Packaged\DalSchema\Databases\Mysql\MySQLColumn;
use Packaged\DalSchema\Databases\Mysql\MySQLColumnType;
use Packaged\DalSchema\Databases\Mysql\MySQLDatabase;
use Packaged\DalSchema\Databases\Mysql\MySQLTable;
use Packaged\DalSchema\Parser\MySQL\MySQLParser;
use PHPUnit\Framework\TestCase;

class MigrateTest extends TestCase
{
  public function testMigration()
  {
    $conn = new MySQLiConnection();
    $conn->setResolver(new DalResolver());

    $parser = new MySQLParser($conn);

    try
    {
      $table = $parser->parseTable('test_db', 'test_table');
    }
    catch(\Exception $exception)
    {
      $table = null;
    }
    $this->assertNull($table);

    // create it
    $db = new MySQLDatabase('test_db');
    $table = new MySQLTable(
      $db, 'test_table', 'my test table',
      [
        new MySQLColumn(
          'id',
          new  MySQLColumnType(MySQLColumnType::INT_UNSIGNED),
          null,
          false,
          null,
          MySQLColumn::EXTRA_AUTO_INCREMENT
        ),
        new MySQLColumn('field1', new MySQLColumnType(MySQLColumnType::VARCHAR), 50),
        new MySQLColumn('field2', new MySQLColumnType(MySQLColumnType::TINY_INT_UNSIGNED)),
      ]
    );

    // parse again, and check equal
    $table = $parser->parseTable('test_db', 'test_table');
  }
}
