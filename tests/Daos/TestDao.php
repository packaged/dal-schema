<?php

namespace Packaged\Tests\DalSchema\Daos;

use Packaged\DalSchema\DaoSchemaProvider;
use Packaged\DalSchema\Database;
use Packaged\DalSchema\Databases\Mysql\MySQLCollation;
use Packaged\DalSchema\Databases\Mysql\MySQLColumn;
use Packaged\DalSchema\Databases\Mysql\MySQLColumnType;
use Packaged\DalSchema\Databases\Mysql\MySQLDatabase;
use Packaged\DalSchema\Databases\Mysql\MySQLIndex;
use Packaged\DalSchema\Databases\Mysql\MySQLKeyType;
use Packaged\DalSchema\Databases\Mysql\MySQLTable;
use Packaged\DalSchema\Table;

class TestDao implements DaoSchemaProvider
{
  public function getDatabaseSchema(): Database
  {
    return new MySQLDatabase('test_db', null, null);
  }

  public function getDaoSchema(): Table
  {
    return new MySQLTable(
      $this->getDatabaseSchema(), 'test_table', 'my test table',
      [
        new MySQLColumn('id', MySQLColumnType::INT_UNSIGNED(), null, false, null, MySQLColumn::EXTRA_AUTO_INCREMENT),
        new MySQLColumn('field1', MySQLColumnType::VARCHAR(), 50),
        new MySQLColumn('field2', MySQLColumnType::TINY_INT_UNSIGNED()),
      ],
      [
        new MySQLIndex('my_pk', MySQLKeyType::PRIMARY(), 'id'),
      ],
      new MySQLCollation(MySQLCollation::UTF8_UNICODE_CI)
    );
  }
}
