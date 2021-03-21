<?php

namespace Packaged\Tests\DalSchema;

use Exception;
use Packaged\Dal\DalResolver;
use Packaged\Dal\Exceptions\Connection\ConnectionException;
use Packaged\Dal\Ql\MySQLiConnection;
use Packaged\DalSchema\Databases\Mysql\MySQLCollation;
use Packaged\DalSchema\Databases\Mysql\MySQLColumn;
use Packaged\DalSchema\Databases\Mysql\MySQLColumnType;
use Packaged\DalSchema\Databases\Mysql\MySQLDatabase;
use Packaged\DalSchema\Databases\Mysql\MySQLIndex;
use Packaged\DalSchema\Databases\Mysql\MySQLKeyType;
use Packaged\DalSchema\Databases\Mysql\MySQLTable;
use Packaged\DalSchema\Parser\MySQL\MySQLParser;
use PHPUnit\Framework\TestCase;

class MigrateTest extends TestCase
{
  /**
   * @throws ConnectionException
   * @throws Exception
   */
  public function testMigration()
  {
    $conn = new MySQLiConnection();
    //todo: why do we need to set this?
    $conn->setResolver(new DalResolver());

    $conn->runQuery('DROP TABLE IF EXISTS `test_db`.`test_table`');

    $parser = new MySQLParser($conn);

    try
    {
      $checkTable = $parser->parseTable('test_db', 'test_table');
    }
    catch(Exception $exception)
    {
      $checkTable = null;
    }
    self::assertNull($checkTable);

    // create db
    $db = new MySQLDatabase('test_db', null, null, true);
    $createDbQuery = $db->writerCreate();
    self::assertEquals('CREATE DATABASE IF NOT EXISTS `test_db`', $createDbQuery);
    $conn->runQuery($createDbQuery);

    // create table
    $newTable = new MySQLTable(
      $db, 'test_table', 'my test table',
      [
        new MySQLColumn(
          'id',
          MySQLColumnType::INT_UNSIGNED(),
          null,
          false,
          null,
          MySQLColumn::EXTRA_AUTO_INCREMENT
        ),
        new MySQLColumn('field1', MySQLColumnType::VARCHAR(), 50),
        new MySQLColumn('field2', MySQLColumnType::TINY_INT_UNSIGNED()),
      ],
      [
        new MySQLIndex('my_pk', MySQLKeyType::PRIMARY(), 'id'),
      ],
      new MySQLCollation(MySQLCollation::UTF8_UNICODE_CI)
    );

    $createTableQuery = $newTable->writerCreate();
    self::assertEquals(
      'CREATE TABLE `test_db`.`test_table` (`id` int(10) unsigned NOT NULL auto_increment, `field1` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `field2` tinyint(3) unsigned NOT NULL, PRIMARY KEY (`id`)) ENGINE InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci',
      $createTableQuery
    );

    $conn->runQuery($createTableQuery);

    $checkDb = $parser->parseDatabase('test_db');
    self::assertEmpty($db->writerAlter($checkDb));

    // check alter is empty
    $checkTable = $parser->parseTable('test_db', 'test_table');
    self::assertEmpty($checkTable->writerAlter($checkTable));
  }
}
