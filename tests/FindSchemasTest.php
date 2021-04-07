<?php

namespace Packaged\Tests\DalSchema;

use Packaged\DalSchema\DalSchema;
use Packaged\DalSchema\Table;
use PHPUnit\Framework\TestCase;

class FindSchemasTest extends TestCase
{
  public function testFindSchemas()
  {
    $schemas = DalSchema::findSchemas(__DIR__, __NAMESPACE__);
    self::assertCount(1, $schemas);
    self::assertContainsOnlyInstancesOf(Table::class, $schemas);
    $alterTableQuery = $schemas[0]->writerCreate();
    self::assertEquals(
      'CREATE TABLE `my_test_db`.`defined_dao` (`id` int(10) unsigned NOT NULL auto_increment, `name` int(10) unsigned NOT NULL auto_increment, PRIMARY KEY (`id`)) ENGINE InnoDB',
      $alterTableQuery
    );
  }
}
