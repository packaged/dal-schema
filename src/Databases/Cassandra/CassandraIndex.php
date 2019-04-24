<?php
namespace Packaged\DalSchema\Databases\Cassandra;

use Packaged\DalSchema\Schema\Index;
use Packaged\Helpers\Arrays;

class CassandraIndex implements Index
{
  protected $_name;
  protected $_type;
  protected $_columns;

  public function __construct(string $name, CassandraKeyType $type, ...$columnNames)
  {
    $this->_name = $name;
    $this->_type = $type;
    $this->_columns = Arrays::instancesOf($columnNames, 'string');
  }

  public function getName(): string
  {
    return $this->_name;
  }

  public function getColumns(): array
  {
    return $this->_columns;
  }

  public function getType(): CassandraKeyType
  {
    return $this->_type;
  }
}
