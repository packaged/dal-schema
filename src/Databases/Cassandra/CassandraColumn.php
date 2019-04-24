<?php
namespace Packaged\DalSchema\Databases\Cassandra;

use Packaged\DalSchema\Abstracts\AbstractColumn;

class CassandraColumn extends AbstractColumn
{
  public function __construct(string $name, CassandraColumnType $type)
  {
    $this->_setName($name);
    $this->_setType($type);
  }

  private $_type;

  protected function _setType(CassandraColumnType $type)
  {
    $this->_type = $type;
    return $this;
  }
}
