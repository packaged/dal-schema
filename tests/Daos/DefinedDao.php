<?php

namespace Packaged\Tests\DalSchema\Daos;

use Packaged\DalSchema\DalSchemaProvider;
use Packaged\DalSchema\Databases\Mysql\MySQLColumn;
use Packaged\DalSchema\Databases\Mysql\MySQLColumnType;
use Packaged\DalSchema\Databases\Mysql\MySQLDatabase;
use Packaged\DalSchema\Databases\Mysql\MySQLKey;
use Packaged\DalSchema\Databases\Mysql\MySQLKeyType;
use Packaged\DalSchema\Databases\Mysql\MySQLTable;
use Packaged\DalSchema\Table;

class DefinedDao implements DalSchemaProvider
{
  public int $id;
  public string $name;

  public function getDaoSchema(): Table
  {
    return MySQLTable::i(new MySQLDatabase('my_test_db'), 'defined_dao')
      ->addColumn(
        new MySQLColumn('id', MySQLColumnType::INT_UNSIGNED(), null, false, null, MySQLColumn::EXTRA_AUTO_INCREMENT),
        new MySQLColumn('name', MySQLColumnType::INT_UNSIGNED(), null, false, null, MySQLColumn::EXTRA_AUTO_INCREMENT)
      )
      ->addKey(new MySQLKey('id', MySQLKeyType::PRIMARY(), 'id'));
  }
}
