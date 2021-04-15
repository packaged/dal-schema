<?php

namespace Packaged\Tests\DalSchema;

use Packaged\Dal\DalResolver;
use Packaged\Dal\Foundation\Dao;
use Packaged\Dal\Ql\MySQLiConnection;
use Packaged\Dal\Ql\QlDataStore;
use Packaged\DalSchema\DalSchema;
use Packaged\DalSchema\Databases\Mysql\MySQLDatabase;
use Packaged\DalSchema\Table;
use Packaged\Tests\DalSchema\Daos\DefinedDao;
use PHPUnit\Framework\TestCase;

class FindSchemasTest extends TestCase
{
  public function testFindSchemas()
  {
    // find the daos
    $schemas = DalSchema::findSchemas(__DIR__, __NAMESPACE__);
    self::assertCount(1, $schemas);
    self::assertContainsOnlyInstancesOf(Table::class, $schemas);
    $alterTableQuery = $schemas[0]->writerCreate();
    self::assertEquals(
      'CREATE TABLE `defined_daos` (`id` int(10) unsigned NOT NULL auto_increment, `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE InnoDB',
      $alterTableQuery
    );

    // migrate them
    $resolver = new DalResolver();
    Dao::setDalResolver($resolver);

    $conn = new MySQLiConnection();
    $conn->getConfig()->addItem('database', 'test_db');
    $dataStore = new QlDataStore();
    $dataStore->setConnection($conn);
    $resolver->addDataStore('', $dataStore);
    $resolver->addConnection('', $conn);
    $conn->setResolver($resolver);

    DalSchema::migrateDatabases($conn, new MySQLDatabase('test_db'));
    DalSchema::migrateTables($conn, 'test_db', ...$schemas);

    $dao = new DefinedDao();
    $dao->name = 'test';
    $dao->save();
  }
}
