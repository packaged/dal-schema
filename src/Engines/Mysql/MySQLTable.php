<?php
namespace Packaged\DalSchema\Engines\Mysql;

use Packaged\DalSchema\Engines\SchemaEngine;
use Packaged\DalSchema\Schema\AbstractTable;

class MySQLTable extends AbstractTable
{
  public function getEngine(): SchemaEngine
  {
    return new MySQLEngine();
  }
}
