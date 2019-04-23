<?php
namespace Packaged\DalSchema\Engines\Cassandra;

use Packaged\DalSchema\Schema\AbstractTypedColumn;

class CassandraColumn extends AbstractTypedColumn
{
  public function __construct(string $name, CassandraColumnType $type)
  {
    $this->_setName($name);
    $this->_setType($type->getValue());
  }
}
