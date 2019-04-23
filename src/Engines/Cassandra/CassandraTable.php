<?php
namespace Packaged\DalSchema\Engines\Cassandra;

use Packaged\DalSchema\Engines\SchemaEngine;
use Packaged\DalSchema\Schema\AbstractTable;

class CassandraTable extends AbstractTable
{
  public function getEngine(): SchemaEngine
  {
    return new CassandraEngine();
  }
}
