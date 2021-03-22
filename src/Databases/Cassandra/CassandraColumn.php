<?php
namespace Packaged\DalSchema\Databases\Cassandra;

use Packaged\DalSchema\Abstracts\AbstractColumn;
use Packaged\DalSchema\Writer;

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

  public function writerCreate(): string
  {
    // TODO: Implement writerCreate() method.
  }

  public function writerAlter(Writer $old): string
  {
    // TODO: Implement writerAlter() method.
  }
}
