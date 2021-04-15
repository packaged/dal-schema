<?php

namespace Packaged\Tests\DalSchema\Daos;

use Packaged\Dal\Ql\QlDao;
use Packaged\DalSchema\DalSchemaProvider;
use Packaged\DalSchema\Databases\Mysql\MySQLColumn;
use Packaged\DalSchema\Databases\Mysql\MySQLColumnType;
use Packaged\DalSchema\Databases\Mysql\MySQLDatabase;
use Packaged\DalSchema\Databases\Mysql\MySQLKey;
use Packaged\DalSchema\Databases\Mysql\MySQLKeyType;
use Packaged\DalSchema\Databases\Mysql\MySQLTable;
use Packaged\DalSchema\Table;

class DefinedDao extends QlDao implements DalSchemaProvider
{
  public ?int $id = null;
  public ?string $name = null;

  protected $_dataStoreName = '';

  public function getDaoSchema(): Table
  {
    return MySQLTable::i('defined_daos')
      ->addColumn(
        new MySQLColumn('id', MySQLColumnType::INT_UNSIGNED(), null, false, null, MySQLColumn::EXTRA_AUTO_INCREMENT),
        new MySQLColumn('name', MySQLColumnType::VARCHAR(), 20)
      )
      ->addKey(new MySQLKey('id', MySQLKeyType::PRIMARY(), 'id'));
  }
}
