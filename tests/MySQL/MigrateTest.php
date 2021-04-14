<?php

namespace Packaged\Tests\DalSchema\MySQL;

use Exception;
use Packaged\Dal\DalResolver;
use Packaged\Dal\Exceptions\Connection\ConnectionException;
use Packaged\Dal\Ql\MySQLiConnection;
use Packaged\DalSchema\DalSchema;
use Packaged\DalSchema\Databases\Mysql\MySQLCollation;
use Packaged\DalSchema\Databases\Mysql\MySQLColumn;
use Packaged\DalSchema\Databases\Mysql\MySQLColumnType;
use Packaged\DalSchema\Databases\Mysql\MySQLDatabase;
use Packaged\DalSchema\Databases\Mysql\MySQLKey;
use Packaged\DalSchema\Databases\Mysql\MySQLTable;
use Packaged\DalSchema\Parser\MySQL\MySQLParser;
use PHPUnit\Framework\TestCase;

class MigrateTest extends TestCase
{
  protected $_conn;
  protected $_testDb;

  public function __construct(?string $name = null, array $data = [], $dataName = '')
  {
    parent::__construct($name, $data, $dataName);
    $this->_conn = new MySQLiConnection();
    //todo: why do we need to set this?
    $this->_conn->setResolver(new DalResolver());

    $this->_testDb = new MySQLDatabase('test_db', null, null);
  }

  public function testMigrate()
  {
    $tblSchema = (new MySQLTable($this->_testDb, 'test_table_migrate', 'my test table'))
      ->addColumn(
        new MySQLColumn('id', MySQLColumnType::INT_UNSIGNED(), null, false, null, MySQLColumn::EXTRA_AUTO_INCREMENT),
        new MySQLColumn('field1', MySQLColumnType::VARCHAR(), 50),
        new MySQLColumn('field2', MySQLColumnType::TINY_INT_UNSIGNED()),
      )->addKey(
        MySQLKey::primary('my_pk', 'id')
      )
      ->setCollation(
        new MySQLCollation(MySQLCollation::UTF8_UNICODE_CI)
      );

    DalSchema::migrate($this->_conn, $tblSchema);

    $parser = new MySQLParser($this->_conn);
    $current = $parser->parseTable($tblSchema->getDatabase()->getName(), $tblSchema->getName());
    self::assertEmpty($tblSchema->writerAlter($current));
  }

  /**
   * @throws ConnectionException
   * @throws Exception
   */
  public function testManualMigration()
  {
    $tblSchema = (new MySQLTable($this->_testDb, 'test_table', 'my test table'))
      ->addColumn(
        new MySQLColumn('id', MySQLColumnType::INT_UNSIGNED(), null, false, null, MySQLColumn::EXTRA_AUTO_INCREMENT),
        new MySQLColumn('field1', MySQLColumnType::VARCHAR(), 50),
        new MySQLColumn('field2', MySQLColumnType::TINY_INT_UNSIGNED()),
      )->addKey(
        MySQLKey::primary('my_pk', 'id')
      )
      ->setCollation(
        new MySQLCollation(MySQLCollation::UTF8_UNICODE_CI)
      );

    $dbSchema = $tblSchema->getDatabase();
    $this->_conn->runQuery(
      'DROP TABLE IF EXISTS `' . $dbSchema->getName() . '`.`' . $tblSchema->getName() . '`'
    );

    $parser = new MySQLParser($this->_conn);

    try
    {
      $checkTable = $parser->parseTable($dbSchema->getName(), $tblSchema->getName());
    }
    catch(Exception $exception)
    {
      $checkTable = null;
    }
    self::assertNull($checkTable);

    // create db
    $createDbQuery = $dbSchema->writerCreate(false);
    self::assertEquals('CREATE DATABASE `test_db`', $createDbQuery);
    $createDbQuery = $dbSchema->writerCreate(true);
    self::assertEquals('CREATE DATABASE IF NOT EXISTS `test_db`', $createDbQuery);
    $this->_conn->runQuery($createDbQuery);

    // create table
    $createTableQuery = $tblSchema->writerCreate();
    self::assertEquals(
      'CREATE TABLE `test_db`.`test_table` (`id` int(10) unsigned NOT NULL auto_increment, `field1` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `field2` tinyint(3) unsigned NOT NULL, PRIMARY KEY (`id`)) ENGINE InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci',
      $createTableQuery
    );

    $this->_conn->runQuery($createTableQuery);

    $checkDb = $parser->parseDatabase($dbSchema->getName());
    self::assertEmpty($dbSchema->writerAlter($checkDb));

    // check alter is empty
    $checkTable = $parser->parseTable($dbSchema->getName(), $tblSchema->getName());
    self::assertEmpty($checkTable->writerAlter($checkTable));

    // --- migration: add index
    $tblSchema->addKey(MySQLKey::index('f1_idx', 'field1', 'field2'));

    $alterTableQuery = $tblSchema->writerAlter($checkTable);
    self::assertEquals(
      'ALTER TABLE `test_db`.`test_table` ADD INDEX `f1_idx` (`field1`,`field2`)',
      $alterTableQuery
    );
    $this->_conn->runQuery($alterTableQuery);

    $checkTable = $parser->parseTable($dbSchema->getName(), $tblSchema->getName());
    self::assertEmpty($checkTable->writerAlter($checkTable));

    // --- migration: change primary key
    $tblSchema = (new MySQLTable($this->_testDb, 'test_table', 'my test table'))
      ->addColumn(
        new MySQLColumn('id', MySQLColumnType::INT_UNSIGNED()),
        new MySQLColumn('field1', MySQLColumnType::VARCHAR(), 50),
        new MySQLColumn('field2', MySQLColumnType::TINY_INT_UNSIGNED()),
      )->addKey(
        MySQLKey::primary('my_pk', 'field1'),
        MySQLKey::index('f1_idx', 'field1', 'field2')
      )
      ->setCollation(
        new MySQLCollation(MySQLCollation::UTF8_UNICODE_CI)
      );

    $alterTableQuery = $tblSchema->writerAlter($checkTable);
    self::assertEquals(
      'ALTER TABLE `test_db`.`test_table` CHANGE COLUMN `id` `id` int(10) unsigned NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (`field1`)',
      $alterTableQuery
    );
    $this->_conn->runQuery($alterTableQuery);

    $checkTable = $parser->parseTable($dbSchema->getName(), $tblSchema->getName());
    self::assertEmpty($checkTable->writerAlter($checkTable));

    // --- migration: drop old primary key

    $tblSchema = (new MySQLTable($this->_testDb, 'test_table', 'my test table'))
      ->addColumn(
        new MySQLColumn('field1', MySQLColumnType::VARCHAR(), 50),
        new MySQLColumn('field2', MySQLColumnType::TINY_INT_UNSIGNED()),
      )
      ->addKey(
        MySQLKey::primary('my_pk', 'field1'),
      )
      ->setCollation(
        new MySQLCollation(MySQLCollation::UTF8_UNICODE_CI)
      );

    $alterTableQuery = $tblSchema->writerAlter($checkTable);
    self::assertEquals(
      'ALTER TABLE `test_db`.`test_table` DROP COLUMN `id`',
      $alterTableQuery
    );
    $this->_conn->runQuery($alterTableQuery);

    $checkTable = $parser->parseTable($dbSchema->getName(), $tblSchema->getName());
    self::assertEmpty($checkTable->writerAlter($checkTable));
  }
}
