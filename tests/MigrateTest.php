<?php

namespace Packaged\Tests\DalSchema;

use Exception;
use Packaged\Dal\DalResolver;
use Packaged\Dal\Exceptions\Connection\ConnectionException;
use Packaged\Dal\Ql\MySQLiConnection;
use Packaged\DalSchema\Parser\MySQL\MySQLParser;
use Packaged\Tests\DalSchema\Daos\TestDao;
use Packaged\Tests\DalSchema\Daos\TestDao2;
use PHPUnit\Framework\TestCase;

class MigrateTest extends TestCase
{
  protected $_conn;

  public function __construct(?string $name = null, array $data = [], $dataName = '')
  {
    parent::__construct($name, $data, $dataName);
    $this->_conn = new MySQLiConnection();
    //todo: why do we need to set this?
    $this->_conn->setResolver(new DalResolver());
  }

  /**
   * @throws ConnectionException
   * @throws Exception
   */
  public function testMigration1()
  {
    $dao = new TestDao();
    $tblSchema = $dao->getDaoSchema();
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
  }

  /**
   * @depends testMigration1
   */
  public function testMigration2()
  {
    $dao = new TestDao2();
    $tblSchema = $dao->getDaoSchema();
    $dbSchema = $tblSchema->getDatabase();

    $parser = new MySQLParser($this->_conn);
    $parsed = $parser->parseTable($dbSchema->getName(), $tblSchema->getName());

    $alterTableQuery = $tblSchema->writerAlter($parsed);
    self::assertEquals(
      'ALTER TABLE `test_db`.`test_table` (`id` int(10) unsigned NOT NULL auto_increment, `field1` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `field2` tinyint(3) unsigned NOT NULL, PRIMARY KEY (`id`)) ENGINE InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci',
      $alterTableQuery
    );
  }
}
